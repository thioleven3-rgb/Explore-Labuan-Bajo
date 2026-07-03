# Labuan Bajo Explorer
Website implementasi dataset penelitian **"Sistem Rekomendasi Pariwisata Kawasan Destinasi Super Prioritas Labuan Bajo Menggunakan Metode Collaborative Item-Based Filtering"**.

Dibangun dengan **PHP native (MySQLi)** + **MySQL**, tanpa framework — mudah dijalankan di XAMPP.

---

## 1. Struktur Folder

```
labuan-bajo-web/
├── config/
│   └── database.php          # konfigurasi koneksi MySQL
├── classes/
│   └── CollaborativeFiltering.php   # inti algoritma Cosine Similarity + prediksi rating
├── includes/
│   ├── header.php / footer.php
│   └── functions.php         # helper tampilan (ikon kategori, bintang, dll)
├── assets/
│   ├── css/style.css
│   └── js/main.js
├── database/
│   └── labuan_bajo.sql       # schema + data asli dari notebook penelitian (20 destinasi, 50 wisatawan, 96 rating)
├── index.php                 # beranda
├── destinasi.php             # daftar semua destinasi + filter kategori
├── detail.php                # detail destinasi + "destinasi serupa" (item-based CF)
├── wisatawan.php             # daftar 50 wisatawan demo dari dataset
├── rekomendasi.php           # hasil rekomendasi personal per wisatawan
└── tentang.php                # penjelasan metode untuk keperluan sidang
```

## 2. Instalasi di XAMPP

1. Copy folder `labuan-bajo-web` ke `C:\xampp\htdocs\` (atau `/Applications/XAMPP/htdocs/` di Mac).
2. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel.
3. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
4. Klik tab **Import** → pilih file `database/labuan_bajo.sql` → klik **Go**.
   - Ini otomatis membuat database `labuan_bajo_rekomendasi` beserta 3 tabel (`items`, `users`, `ratings`) dan mengisinya dengan data asli dari notebook riset Anda.
5. Cek `config/database.php` — default sudah sesuai XAMPP standar (`host: localhost`, `user: root`, `password: ""`). Ubah jika konfigurasi MySQL Anda berbeda.
6. Buka browser: `http://localhost/labuan-bajo-web/`

Tidak perlu `composer install` atau dependency tambahan apapun — murni PHP + MySQLi bawaan.

## 3. Alur Pemakaian

- **Beranda** → ringkasan dataset & kategori destinasi.
- **Destinasi** → semua 20 destinasi, bisa difilter per kategori.
- Klik destinasi → **Detail** → menampilkan "Wisatawan Serupa Juga Menyukai" (dihitung live dari Cosine Similarity antar item).
- **Wisatawan** → pilih salah satu dari 50 wisatawan dataset (U001–U050).
- Klik wisatawan → **Rekomendasi** → menampilkan:
  - Destinasi yang **sudah pernah dinilai** wisatawan tersebut.
  - **Top-5 rekomendasi baru** hasil prediksi item-based CF, lengkap dengan skor prediksi.
- **Tentang Metode** → penjelasan rumus, alur algoritma, dan statistik dataset — bisa dipakai sebagai referensi visual saat sidang.

## 4. Tentang Algoritma

Logika di `classes/CollaborativeFiltering.php` mereplikasi persis alur pada notebook penelitian:

1. Bangun **User-Item Matrix** dari tabel `ratings`.
2. Hitung **Cosine Similarity** antar setiap pasang destinasi berdasarkan vektor rating.
3. Prediksi rating destinasi yang belum dinilai user memakai **weighted sum**:
   `pred(u,i) = Σ(sim(i,j) × rating(u,j)) / Σ|sim(i,j)|`
4. Urutkan hasil prediksi → ambil **Top-N** sebagai rekomendasi.

Similarity dihitung *live* saat halaman diakses (bukan precomputed), karena ukuran dataset (20 item) sangat kecil sehingga performanya tetap instan.

## 5. Catatan

- Dataset yang dipakai adalah **dataset simulasi** dari notebook riset Anda (`Labuan_Bajo_Scraper.ipynb`), bukan hasil scraping asli — sesuai catatan pada notebook, hal ini perlu disebutkan secara transparan di bagian metodologi paper.
- Tidak ada fitur login/registrasi — sesuai kebutuhan Anda, website ini fokus mendemonstrasikan mesin rekomendasi menggunakan data wisatawan yang sudah ada di dataset (U001–U050).
- Tidak ada halaman admin — jika suatu saat dibutuhkan CRUD destinasi, tinggal beri tahu saya.
