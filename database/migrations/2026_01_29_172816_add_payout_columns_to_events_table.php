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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('ready_for_payout')
                  ->default(false)
                  ->after('paid_from_seed')
                  ->comment('True if event is approved and ready for payout');

            $table->timestamp('payout_at')
                  ->nullable()
                  ->after('ready_for_payout');

            $table->string('payout_status')
                  ->nullable()
                  ->after('payout_at');

            $table->string('paypal_transaction_id')
                  ->nullable()
                  ->after('payout_status')
                  ->comment('PayPal transaction ID for audit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'ready_for_payout',
                'payout_at',
                'payout_status',
                'paypal_transaction_id',
            ]);
        });
    }
};
