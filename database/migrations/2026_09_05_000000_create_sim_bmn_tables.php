<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. units
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type'); // UTAMA, CABANG
            $table->foreignId('parent_id')->nullable()->constrained('units')->onDelete('cascade');
            $table->timestamps();
        });

        // 2. users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('CABANG'); // ADMIN, USER_UTAMA, CABANG
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->rememberToken();
            $table->timestamps();
        });

        // 3. pegawais
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique()->nullable();
            $table->string('name');
            $table->string('jabatan')->nullable();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->timestamps();
        });

        // 4. kategori_bmns
        Schema::create('kategori_bmns', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // 5. lokasis
        Schema::create('lokasis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gedung')->nullable();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->timestamps();
        });

        // 6. bmns
        Schema::create('bmns', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nup')->nullable();
            $table->string('name');
            $table->string('merk')->nullable();
            $table->integer('tahun_perolehan')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('kondisi')->default('BAIK'); // BAIK, RUSAK_RINGAN, RUSAK_BERAT
            $table->string('status')->default('AKTIF'); // AKTIF, DIHAPUSKAN, MUTASI
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_bmns')->onDelete('set null');
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->onDelete('set null');
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });

        // 7. bmn_fotos
        Schema::create('bmn_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bmn_id')->constrained('bmns')->onDelete('cascade');
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        // 8. bmn_dokumens
        Schema::create('bmn_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bmn_id')->constrained('bmns')->onDelete('cascade');
            $table->string('file_path');
            $table->string('nama_dokumen');
            $table->timestamps();
        });

        // 9. mutasi_bmns
        Schema::create('mutasi_bmns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bmn_id')->constrained('bmns')->onDelete('cascade');
            $table->foreignId('asal_unit_id')->constrained('units');
            $table->foreignId('tujuan_unit_id')->constrained('units');
            $table->foreignId('user_id')->constrained('users');
            $table->date('tanggal_mutasi');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('PENDING'); // PENDING, DISETUJUI, DITOLAK
            $table->timestamps();
        });

        // 10. inventarisasis
        Schema::create('inventarisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users');
            $table->string('periode');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('status')->default('PROSES'); // PROSES, SELESAI
            $table->timestamps();
        });

        // 11. riwayat_kondisis
        Schema::create('riwayat_kondisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bmn_id')->constrained('bmns')->onDelete('cascade');
            $table->foreignId('inventarisasi_id')->nullable()->constrained('inventarisasis')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users');
            $table->string('kondisi_sebelumnya');
            $table->string('kondisi_baru');
            $table->text('catatan')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->timestamps();
        });

        // 12. log_aktivitas
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('aktivitas');
            $table->string('modul');
            $table->text('detail')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('riwayat_kondisis');
        Schema::dropIfExists('inventarisasis');
        Schema::dropIfExists('mutasi_bmns');
        Schema::dropIfExists('bmn_dokumens');
        Schema::dropIfExists('bmn_fotos');
        Schema::dropIfExists('bmns');
        Schema::dropIfExists('lokasis');
        Schema::dropIfExists('kategori_bmns');
        Schema::dropIfExists('pegawais');
        Schema::dropIfExists('users');
        Schema::dropIfExists('units');
    }
};
