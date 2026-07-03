<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$conn = getConnection();
$pageTitle = 'Tentang Metode';

$totalItems = $conn->query("SELECT COUNT(*) c FROM items")->fetch_assoc()['c'];
$totalUsers = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalRatings = $conn->query("SELECT COUNT(*) c FROM ratings")->fetch_assoc()['c'];
$sparsity = round((1 - ($totalRatings / ($totalItems * $totalUsers))) * 100, 1);

include __DIR__ . '/includes/header.php';
?>

<section class="section" style="padding-top:48px;">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow"><i class="fa-solid fa-flask"></i> Metodologi Penelitian</span>
        <h2>Collaborative Item-Based Filtering</h2>
        <p class="section-lead">Sistem Rekomendasi Pariwisata Kawasan Destinasi Super Prioritas Labuan Bajo Menggunakan Metode Collaborative Item-Based Filtering.</p>
      </div>
    </div>

    <div class="detail-grid">
      <div>
        <div class="info-card">
          <h4><i class="fa-solid fa-list-ol"></i> Alur Perhitungan</h4>
          <div class="step-list">
            <div class="step-item">
              <span class="step-num"></span>
              <div>
                <h4 style="margin-bottom:4px; font-size:15px;">Bangun User-Item Matrix</h4>
                <p style="font-size:13.5px; color:var(--ink-soft); margin:0;">Data rating wisatawan disusun menjadi matriks, baris = wisatawan, kolom = destinasi, nilai sel = rating (kosong jika belum dinilai).</p>
              </div>
            </div>
            <div class="step-item">
              <span class="step-num"></span>
              <div>
                <h4 style="margin-bottom:4px; font-size:15px;">Hitung Item Similarity</h4>
                <p style="font-size:13.5px; color:var(--ink-soft); margin:0;">Kemiripan antar destinasi dihitung dari vektor rating menggunakan Cosine Similarity.</p>
              </div>
            </div>
            <div class="step-item">
              <span class="step-num"></span>
              <div>
                <h4 style="margin-bottom:4px; font-size:15px;">Prediksi Rating</h4>
                <p style="font-size:13.5px; color:var(--ink-soft); margin:0;">Rating destinasi yang belum dinilai diprediksi dari weighted sum similarity terhadap destinasi yang sudah dinilai wisatawan.</p>
              </div>
            </div>
            <div class="step-item">
              <span class="step-num"></span>
              <div>
                <h4 style="margin-bottom:4px; font-size:15px;">Top-N Recommendation</h4>
                <p style="font-size:13.5px; color:var(--ink-soft); margin:0;">Hasil prediksi diurutkan, lalu N destinasi dengan skor tertinggi ditampilkan sebagai rekomendasi.</p>
              </div>
            </div>
          </div>
        </div>

        <div class="formula-box">
          <span class="fname">Cosine Similarity antar item A dan B</span>
          sim(A, B) = (A &middot; B) / (||A|| &times; ||B||)
        </div>

        <div class="formula-box">
          <span class="fname">Prediksi rating item i untuk user u</span>
          pred(u, i) = &Sigma; [ sim(i, j) &times; rating(u, j) ] / &Sigma; | sim(i, j) |
          <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;untuk setiap j = destinasi yang sudah dinilai user u
        </div>
      </div>

      <div>
        <div class="info-card">
          <h4><i class="fa-solid fa-database"></i> Statistik Dataset</h4>
          <div class="metric-grid" style="grid-template-columns: 1fr 1fr;">
            <div class="metric-card"><b><?= $totalItems ?></b><span>Destinasi</span></div>
            <div class="metric-card"><b><?= $totalUsers ?></b><span>Wisatawan</span></div>
            <div class="metric-card"><b><?= $totalRatings ?></b><span>Rating</span></div>
            <div class="metric-card"><b><?= $sparsity ?>%</b><span>Sparsity</span></div>
          </div>
        </div>

        <div class="info-card">
          <h4><i class="fa-solid fa-gauge-high"></i> Evaluasi Model</h4>
          <p style="font-size:13px; color:var(--ink-soft);">Dihitung menggunakan Leave-One-Out Cross-Validation pada notebook penelitian.</p>
          <div class="metric-grid" style="grid-template-columns: 1fr 1fr;">
            <div class="metric-card"><b>0.57</b><span>MAE</span></div>
            <div class="metric-card"><b>0.93</b><span>RMSE</span></div>
          </div>
        </div>

        <div class="info-card">
          <h4><i class="fa-solid fa-code"></i> Implementasi Teknis</h4>
          <p style="font-size:13.5px; color:var(--ink-soft);">Backend: PHP native + MySQLi<br>Database: MySQL (MySQLi, prepared statements)<br>Algoritma: PHP murni (class <code>CollaborativeFiltering</code>), identik dengan notebook penelitian (Python/pandas/scikit-learn)</p>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
