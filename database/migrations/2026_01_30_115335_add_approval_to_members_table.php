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
        Schema::table('members', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->after('member_number');

            $table->foreignId('approved_by')
                ->nullable()
                ->after('approved_at')
                ->constrained('ceaf')
                ->nullOnDelete();
        });
   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['member_number', 'approved_at', 'approved_by']);
        });
    }
};
