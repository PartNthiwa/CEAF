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
       Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->nullable()->nullOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('contact');

            $table->unsignedTinyInteger('percentage'); // 0–100

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
