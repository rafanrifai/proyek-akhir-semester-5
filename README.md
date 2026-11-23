# SIM BMN - Kantor Kementerian Agama Kabupaten Minahasa

Sistem Informasi Manajemen Barang Milik Negara (SIM BMN) berbasis Web dengan integrasi Machine Learning (Algoritma Decision Tree C4.5) untuk klasifikasi tingkat kerusakan aset dan pengelolaan riwayat inventarisasi di lingkungan Kantor Kementerian Agama Kabupaten Minahasa.

---

## 📌 Fitur & Modul Utama

Sistem ini mencakup 8 modul fungsional:

1. **Login dan Hak Akses (Multi-Role Authentication)**
   - Autentikasi berbasis peran: `Super Admin`, `Admin Kanwil`, `Admin Satker`, dan `Petugas Lapangan`.
   - Pembatasan hak akses rute, menu, dan aksi berdasarkan role.
   
2. **Dashboard Interaktif & Statistik Aset**
   - Ringkasan total aset BMN, nilai total aset, dan statistik kondisi barang.
   - Grafik distribusi kondisi fisik (Baik, Rusak Ringan, Rusak Sedang, Rusak Berat).
   - Widget pelacakan progres inventarisasi/sensus tahunan tiap unit kerja/KUA.
   - Panel peringatan dini untuk aset yang butuh perhatian atau mutasi.

3. **Data BMN (Inventarisasi Master Data)**
   - Manajemen katalog master BMN (Kode Barang, NUP, Nama Barang, Merk/Tipe, Tahun Perolehan, Lokasi/Ruangan, Nilai Perolehan).
   - Riwayat mutasi status dan lokasi aset.
   - Upload dokumen pendukung & foto fisik aset BMN.

4. **Data Kondisi Barang (Sensus Fisik)**
   - Pencatatan kondisi barang dengan 4 tingkatan standar pemerintah: **Baik**, **Rusak Ringan**, **Rusak Sedang**, dan **Rusak Berat**.
   - Input berkala oleh petugas inventarisasi di lapangan.

5. **Riwayat Kerusakan Barang**
   - Pencatatan log historis kerusakan barang (tanggal kejadian, deskripsi kerusakan, indikasi penyebab, dan tindakan/rekomendasi perbaikan).
   - Pelacakan riwayat pemeliharaan berkala untuk audit BMN.

6. **Klasifikasi Machine Learning (Decision Tree C4.5)**
   - Penentuan otomatis tingkat rekomendasi kelayakan/kerusakan BMN menggunakan Decision Tree C4.5 murni di backend PHP/Laravel.
   - Parameter evaluasi: Usia Ekonomis, Frekuensi Kerusakan, Persentase Kerusakan Fisik, Nilai Buku, dan Riwayat Pemeliharaan.
   - Visualisasi struktur pohon keputusan (Decision Tree Node Visualizer) interaktif di browser.

7. **Hasil dan Riwayat Klasifikasi**
   - Riwayat log seluruh hasil prediksi dan klasifikasi sistem.
   - Detail evaluasi entropy, information gain, dan aturan (rules) yang dieksekusi.
   - Opsi konfirmasi dan pembaruan status aset berdasarkan hasil klasifikasi.

8. **Laporan BMN & Ekspor Dokumen**
   - Cetak Berita Acara (BA) Inventarisasi Fisik BMN.
   - Ekspor Laporan Rekapitulasi BMN dan Riwayat Kerusakan ke format CSV / Print Preview PDF.
   - Arsip laporan digital tersimpan di sistem.

---

## 🛠️ Tech Stack & Requirements

- **PHP**: ^8.1
- **Framework**: Laravel 10
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templating, Tailwind CSS, Heroicons
- **Tools**: Composer, Git

---

## 🚀 Panduan Instalasi Lokal

1. **Clone repositori**:
   ```bash
   git clone https://github.com/rafan05/proyek-akhir-semester5.git
   cd proyek-akhir-semester5
   ```

2. **Install dependensi PHP**:
   ```bash
   composer install
   ```

3. **Salin konfigurasi environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database** di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sim_bmn
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan Migrasi & Database Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Development Server**:
   ```bash
   php artisan serve
   ```
   Akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## 👤 Akun Default Pengujian

| Role | Email | Password |
|---|---|---|
| **Super Admin** | `admin@kemenag.go.id` | `password` |
| **Admin Satker** | `satker@kemenag.go.id` | `password` |
| **Petugas Lapangan** | `petugas@kemenag.go.id` | `password` |

---

## 📄 Lisensi
Dikembangkan untuk keperluan Proyek Akhir Semester 5 - Program Studi Informatika / Sistem Informasi.
