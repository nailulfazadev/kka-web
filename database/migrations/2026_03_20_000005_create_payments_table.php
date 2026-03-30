<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['registration', 'facility'])->default('registration');
            $table->string('tripay_reference')->nullable();
            $table->string('merchant_ref')->unique();
            $table->string('method')->nullable();
            $table->decimal('amount', 10, 0)->default(0);
            $table->enum('status', ['unpaid', 'paid', 'failed', 'expired', 'refund'])->default('unpaid');
            $table->text('tripay_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
