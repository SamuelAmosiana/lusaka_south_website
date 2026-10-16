<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Transaction ID from Swish / the mobile network (shown to the payer as proof of payment)
            $table->string('provider_transaction_id', 100)->nullable()->after('swish_method')->index();
            $table->timestamp('paid_at')->nullable()->after('provider_transaction_id');
            $table->string('confirmed_via', 100)->nullable()->after('paid_at'); // callback | status_check | admin:<name>
            $table->text('notes')->nullable()->after('confirmed_via');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['provider_transaction_id']);
            $table->dropColumn(['provider_transaction_id', 'paid_at', 'confirmed_via', 'notes']);
        });
    }
};
