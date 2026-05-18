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
        Schema::table('reports', function (Blueprint $table) {
            $table->decimal('min_result',8,2)->after('test_id');
            $table->decimal('max_result',8,2)->after('min_result');
            $table->unsignedBigInteger('unit_id')->after('max_result');
            $table->string('description')->after('unit_id');
            $table->dropColumn('result');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            //
        });
    }
};
