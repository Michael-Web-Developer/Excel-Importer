<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\RowCreatedEvent;
use App\Models\Row;
use App\UseCases\UploadFile\DTOs\FeedRowDTO;
use DateTime;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcessExcelRowsJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param Collection<FeedRowDTO> $rows
     */
	public function __construct(
        private readonly Collection $rows,
        private readonly string $cacheKey,
    )
    {
        $this->queue = 'process_excel_rows';
    }

	public function handle(): void
	{
        $this->rows->each(function (FeedRowDTO $row) {
            $validator = Validator::make($row->toArray(), [
                'id' => ['required', 'integer', 'min:0'],
                'name' => 'required|string|regex:/^[A-Za-z ]+$/',
                'date' => 'required|date_format:d.m.Y',
            ]);

            if ($validator->fails()) {
                $this->logError("{$row->rowNumber} - " . implode(', ', $validator->errors()->all()));

                return;
            }

            try {
                if( Row::where('id', $row->id)->exists()) {
                    $this->logError("{$row->rowNumber} - Duplicate id");

                    return;
                }

                $rowModel = Row::create([
                    'id' => $row->id,
                    'name' => $row->name,
                    'date' => DateTime::createFromFormat('d.m.Y', $row->date)->format('Y-m-d'),
                ]);

                event(new RowCreatedEvent($rowModel));

                Redis::client()->hIncrBy($this->cacheKey, 'processedRow', 1);
            } catch (Exception $e) {
                Storage::disk('public')->append('result.txt', "{$row->rowNumber} - " . $e->getMessage());
            }
        });
	}

    private function logError(string $message): void
    {
        Storage::disk('public')->append('result.txt', $message);

        Redis::client()->hIncrBy($this->cacheKey, 'errorRow', 1);
    }
}
