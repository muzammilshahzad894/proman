<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('title', 500);
            $table->integer('from_month');
            $table->integer('from_day');
            $table->integer('to_month');
            $table->integer('to_day');
            $table->string('type', 10);
            $table->tinyInteger('show_on_frontend')->default(0);
            $table->tinyInteger('allow_weekly_rates')->default(0);
            $table->tinyInteger('allow_monthly_rates')->default(0);
            $table->integer('balance_payment_days')->default(0);
            $table->integer('minimum_nights')->default(0);
            $table->integer('display_order')->default(0);
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
        Schema::dropIfExists('seasons');
    }
};
