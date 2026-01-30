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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('source_fund', ['seed', 'replenish']);
            $table->string('status')->default('pending'); 
            $table->string('transaction_id')->nullable();
            $table->timestamp('payout_at')->nullable();
            //   $table->timestamp('currency')->default('USD')->nullable();
                $table->timestamp('recipient_email')->nullable();
                  $table->timestamp('failure_reason')->nullable();
                    $table->timestamp('attempt_count')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
