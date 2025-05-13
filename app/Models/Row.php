<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $date
 */
class Row extends Model
{
    use HasFactory;

	public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'date',
    ];

	protected function casts(): array
	{
		return [
			'date' => 'date',
		];
	}
}
