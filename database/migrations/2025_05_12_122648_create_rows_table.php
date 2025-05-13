<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up(): void
	{
		Schema::create('rows', function (Blueprint $table) {
			$table->unsignedBigInteger('id');
			$table->string('name');
			$table->date('date');

            $table->primary('id');
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('rows');
	}
};
