<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->integer('edited')->default(0);
            $table->integer('from_admin')->default(0);
            $table->integer('property_id');
            $table->integer('customer_id');
            $table->boolean('is_an_owner_reservation')->default(0);
            $table->string('address', 1000)->nullable();
            $table->string('city', 500)->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->integer('adults')->nullable();
            $table->integer('children')->nullable();
            $table->integer('pets')->nullable();
            $table->timestamp('arrival')->nullable();
            $table->timestamp('departure')->nullable();
            $table->boolean('special_rate')->default(0);
            $table->text('special_rate_note')->nullable();
            $table->boolean('is_non_profit_reservation')->default(0);
            $table->boolean('is_add_pet_fee')->default(0);
            $table->double('sales_tax', 8, 2)->nullable()->default(0.00);
            $table->double('cancelled', 8, 2)->nullable()->default(0.00);
            $table->double('lodgers_tax', 8, 2)->nullable()->default(0.00);
            $table->double('pet_fee', 8, 2)->nullable()->default(0.00);
            $table->double('cleaning_fee', 8, 2)->nullable()->default(0.00);
            $table->double('lodging_amount', 8, 2)->nullable()->default(0.00);
            $table->double('total_amount', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->integer('housekeeper_id')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('send_email')->default(0);
            $table->string('customer_card', 255)->nullable();
            $table->string('customer_profile', 255)->nullable();
            $table->string('customer_payment_profile', 255)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
