<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klasifikasi_bmns', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klasifikasi')->unique();
            $table->foreignId('bmn_id')->nullable()->constrained('bmns')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->string('nama_barang');
            $table->integer('usia_barang')->default(0);
            $table->string('kategori_usia'); // BARU, SEDANG, TUA
            $table->string('intensitas_pemakaian'); // RENDAH, SEDANG, TINGGI
            $table->string('kondisi_fisik'); // BAIK, CACAT_RINGAN, RUSAK_SEBAGIAN, RUSAK_TOTAL
            $table->string('estimasi_biaya'); // KECIL, SEDANG, BESAR
            $table->string('riwayat_kerusakan'); // TIDAK_ADA, 1_2_KALI, LEBIH_2_KALI
            $table->string('hasil_klasifikasi'); // BAIK, RUSAK_RINGAN, RUSAK_SEDANG, RUSAK_BERAT
            $table->decimal('confidence', 5, 2)->default(1.0);
            $table->json('rule_path')->nullable();
            $table->text('rule_text')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klasifikasi_bmns');
    }
};

