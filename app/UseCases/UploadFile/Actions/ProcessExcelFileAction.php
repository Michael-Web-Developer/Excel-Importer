<?php

declare(strict_types=1);

namespace App\UseCases\UploadFile\Actions;

use App\Jobs\ProcessExcelRowsJob;
use App\UseCases\UploadFile\DTOs\FeedRowDTO;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Throwable;

class ProcessExcelFileAction
{
    public function execute(string $filePath): void {
        try {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);

            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();

            $collection = collect();
            $cacheKey = 'import:' . Str::uuid()->toString();
            foreach ($worksheet->getRowIterator(2) as $row) {
                if ($collection->count() === 1000) {
                    ProcessExcelRowsJob::dispatch($collection, $cacheKey);

                    $collection = collect();
                }

                $cellIterator = $row->getCellIterator('A', 'C');

                $rowData = [];
                foreach ($cellIterator as $cell) {
                    $rowData[] = $cell->getValue();
                }

                $collection->push(new FeedRowDTO(
                    rowNumber: $row->getRowIndex(),
                    id: (int)$rowData[0],
                    name: (string)$rowData[1],
                    date: (string)$rowData[2],
                ));
            }
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            Log::error('Failed read file: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            throw $e;
        } catch (Throwable $e) {
            Log::error('Failed to process file: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            throw $e;
        }
    }
}
