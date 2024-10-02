<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSentEmailsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sentemails', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->mediumText('sentto');
            $table->mediumText('gemail');
            $table->mediumText('guest');
            $table->mediumText('subject');
            $table->mediumText('status');
            $table->mediumText('attachment');
            $table->mediumText('body');
            $table->text('eemail');
            $table->integer('reservation_id');
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
        Schema::dropIfExists('sentemails');
    }
};
