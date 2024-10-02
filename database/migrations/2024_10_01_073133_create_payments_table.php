<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->unsignedInteger('reservation_id');
            $table->integer('lodging_amount')->nullable()->default(0);
            $table->double('total')->default(0);
            $table->double('cleaning_fee')->nullable()->default(0);
            $table->double('line_items_total')->nullable()->default(0);
            $table->double('pet_fee')->default(0);
            $table->double('lodgers_tax')->default(0);
            $table->double('sales_tax')->nullable()->default(0);
            $table->double('amount_deposited')->nullable()->default(0);
            $table->string('payment_mode', 255);
            $table->string('transaction_id', 255)->nullable();
            $table->string('card_last_four', 255)->nullable();
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
        Schema::dropIfExists('payments');
    }
};
