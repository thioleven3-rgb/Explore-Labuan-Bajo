<?php
/**
 * Konfigurasi Koneksi Database
 * Sistem Rekomendasi Pariwisata Labuan Bajo - Collaborative Item-Based Filtering
 *
 * Sesuaikan konstanta di bawah ini dengan environment XAMPP Anda.
 * Default XAMPP: host=localhost, user=root, password="" (kosong)
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'labuan_bajo_rekomendasi');

function getConnection(): mysqli
{
    static $conn = null;

    if ($conn === null) {
        mysqli_report(MYSQLI_REPORT_OFF);
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($conn->connect_error) {
            die(
                '<div style="font-family: sans-serif; max-width:640px; margin:60px auto; padding:24px; border:1px solid #e0b4a3; background:#fff5f0; border-radius:8px;">'
                . '<h2 style="color:#c0392b;">Koneksi Database Gagal</h2>'
                . '<p>Pastikan MySQL di XAMPP sudah berjalan dan database <code>' . DB_NAME . '</code> sudah diimport dari file <code>database/labuan_bajo.sql</code>.</p>'
                . '<p style="color:#888; font-size:13px;">Detail: ' . htmlspecialchars($conn->connect_error) . '</p>'
                . '</div>'
            );
        }

        $conn->set_charset('utf8mb4');
    }

    return $conn;
}
