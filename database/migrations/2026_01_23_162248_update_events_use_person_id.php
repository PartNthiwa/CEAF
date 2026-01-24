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
            if (Schema::hasColumn('events', 'dependent_id')) {
                $table->dropColumn('dependent_id');
            }

            $table->foreignId('person_id')
                ->after('member_id')
                ->constrained('persons')
                ->cascadeOnDelete();

            $table->unique('person_id');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('events', function (Blueprint $table) {

            $table->dropUnique(['person_id']);
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');

       
            $table->foreignId('dependent_id')
                ->nullable()
                ->constrained()
                ->nullableOnDelete();
    });
    }

};
