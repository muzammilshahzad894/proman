<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');

			//   the key which will be used to
			// refer to this specific setting
			$table->string('key')->unique();

			// name of the view which must be rendered for the setting
			$table->string('view_name');

			// title and description for setting
			$table->string('title');
			$table->string('description')->nullable();

			// key => value pairs
			$table->text('config')->nullable();

			// key => value validation_rules
			$table->text('validation_rules')->nullable();

			

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
		Schema::drop('settings');
	}

}
