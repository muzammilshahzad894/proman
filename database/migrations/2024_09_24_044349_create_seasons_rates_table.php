<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons_rates', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->unsignedBigInteger('season_id'); 
            $table->unsignedBigInteger('property_id');
            $table->double('daily_rate')->nullable();
            $table->double('weekly_rate')->nullable();
            $table->double('monthly_rate')->nullable();
            $table->double('deposit')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons_rates');
    }
};
