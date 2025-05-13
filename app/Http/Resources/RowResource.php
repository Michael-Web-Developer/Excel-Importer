<?php

namespace App\Http\Resources;

use App\Models\Row;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Row $resource
 */
class RowResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'date' => $this->resource->date->format('d.m.Y'),
		];
	}
}
