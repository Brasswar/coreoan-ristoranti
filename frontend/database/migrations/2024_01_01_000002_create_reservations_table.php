<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->date('reservation_date')->index();
            $table->time('reservation_time');
            $table->integer('guests_count');
            $table->foreignId('table_id')->nullable()->constrained('tables')->nullOnDelete();
            $table->enum('shift', ['lunch', 'dinner'])->index();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending')->index();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
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
