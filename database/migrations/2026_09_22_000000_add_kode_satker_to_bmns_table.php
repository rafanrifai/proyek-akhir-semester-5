<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bmns', function (Blueprint $table) {
            $table->string('kode_satker', 50)->nullable()->after('unit_id')->index();
        });

        // Existing records use their current unit code as the initial value.
        // New records can then enter the owning/origin satker independently.
        DB::statement(
            'UPDATE bmns INNER JOIN units ON units.id = bmns.unit_id '
            . 'SET bmns.kode_satker = units.code '
            . 'WHERE bmns.kode_satker IS NULL OR bmns.kode_satker = \'\''
        );
    }

    public function down(): void
    {
        Schema::table('bmns', function (Blueprint $table) {
            $table->dropIndex(['kode_satker']);
            $table->dropColumn('kode_satker');
        });
    }
};
