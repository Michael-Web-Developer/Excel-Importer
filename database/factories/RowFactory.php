<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RowFactory extends Factory
{
    protected $model = \App\Models\Row::class;

	/**
	 * @inheritDoc
	 */
	public function definition()
	{
		return [
            'id' => $this->faker->unique()->randomNumber(),
            'name' => $this->faker->name(),
            'date' => $this->faker->date(),
        ];
	}
}
