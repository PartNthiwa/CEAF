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
       Schema::create('payment_cycles', function (Blueprint $table) {
            $table->id();

            $table->enum('type', [
                'seed',
                'replenishment',
            ]);

            $table->year('year');

            $table->decimal('amount_per_member', 10, 2);

            $table->date('start_date');
            $table->date('due_date');
            $table->date('late_deadline');

            $table->enum('status', [
                'open',
                'closed',
            ])->default('open');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_cycles');
    }
};
