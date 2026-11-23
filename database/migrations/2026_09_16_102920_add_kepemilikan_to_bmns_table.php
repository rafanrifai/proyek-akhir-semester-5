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
        Schema::table('bmns', function (Blueprint $table) {
            $table->string('kepemilikan')->default('MILIK SENDIRI')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bmns', function (Blueprint $table) {
            $table->dropColumn('kepemilikan');
        });
    }
};
