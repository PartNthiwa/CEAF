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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('paypal_order_id')->nullable()->unique();
            $table->string('paypal_order_status')->nullable();
            $table->timestamp('paypal_order_created_at')->nullable();
            $table->string('paypal_capture_id')->nullable()->unique();
            $table->timestamp('payment_initiated_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
             $table->dropColumn([
                'paypal_order_id',
                'paypal_order_status',
                'paypal_order_created_at',
                'paypal_capture_id',
                'payment_initiated_at',
            ]);
        });
    }
};
