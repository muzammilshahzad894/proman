<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('title', 1000);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('bedroom_id')->nullable();
            $table->unsignedBigInteger('bathroom_id')->nullable();
            $table->unsignedBigInteger('sleep_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_featured')->default(0);
            $table->tinyInteger('is_pet_friendly')->default(0);
            $table->tinyInteger('is_online_booking')->default(0);
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->double('commision');
            $table->unsignedBigInteger('housekeeper_id')->nullable();
            $table->unsignedBigInteger('hottub_id')->nullable();
            $table->double('clearing_fee')->nullable();
            $table->tinyInteger('clearing_fee_active')->default(0);
            $table->double('pet_fee')->nullable();
            $table->tinyInteger('pet_fee_active')->default(0);
            $table->double('lodger_tax')->nullable();
            $table->tinyInteger('lodger_tax_active')->default(0);
            $table->double('sales_tax')->nullable();
            $table->tinyInteger('sales_tax_active')->default(0);
            $table->tinyInteger('is_calendar_active')->default(0);
            $table->unsignedBigInteger('owner')->nullable();
            $table->tinyInteger('is_vacation')->default(0);
            $table->tinyInteger('is_long_term')->default(0);
            $table->string('pdf', 500)->nullable();
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
        Schema::dropIfExists('properties');
    }
};
