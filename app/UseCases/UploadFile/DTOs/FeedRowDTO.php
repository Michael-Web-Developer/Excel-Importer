<?php

declare(strict_types=1);

namespace App\UseCases\UploadFile\DTOs;

use DateTime;

readonly class FeedRowDTO
{
    public function __construct(
        public int $rowNumber,
        public int $id,
        public string $name,
        public string $date,
    ) {
    }

    public function toArray(): array
    {
        return [
            'rowNumber' => $this->rowNumber,
            'id' => $this->id,
            'name' => $this->name,
            'date' => $this->date,
        ];
    }
}
