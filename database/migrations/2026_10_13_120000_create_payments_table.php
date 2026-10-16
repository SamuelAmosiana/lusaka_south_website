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
            $table->string('transaction_reference', 100)->unique();
            $table->string('payer_name')->nullable();
            $table->string('phone_number', 30)->default('');
            $table->decimal('amount', 10, 2);
            $table->string('status', 30)->default('pending'); // pending | confirmed | rejected
            $table->string('category', 100)->nullable();       // Payment | Donation
            $table->string('description')->nullable();
            $table->string('swish_trans_link_id', 100)->nullable()->index();
            $table->string('swish_method', 20)->nullable();    // ussd | push | qr | card
            $table->timestamps();
        });

        Schema::create('payment_logs', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_reference', 100)->nullable()->index();
            $table->text('raw_response')->nullable();
            $table->string('action_taken', 50);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
        Schema::dropIfExists('payments');
    }
};
