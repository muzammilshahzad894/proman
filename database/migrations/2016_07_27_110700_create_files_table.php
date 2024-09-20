<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->increments('id');

			// fields for polymorphic relationships
			$table->unsignedInteger('fileable_id');
			$table->string('fileable_type');

			$table->string('file_name')->unique();
			$table->string('file_extension');
			$table->integer('file_display_order')->default(0);

			$table->nullableTimestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('files');
	}

}
