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
       Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->decimal('amount_per_event', 10, 2); 
            $table->integer('number_of_events');
            $table->timestamps();

            $table->unique('year');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
