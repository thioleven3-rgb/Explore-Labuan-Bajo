<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$conn = getConnection();
$pageTitle = 'Beranda';

$totalItems = $conn->query("SELECT COUNT(*) c FROM items")->fetch_assoc()['c'];
$totalUsers = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];
$totalRatings = $conn->query("SELECT COUNT(*) c FROM ratings")->fetch_assoc()['c'];
$avgRating = $conn->query("SELECT ROUND(AVG(rating),2) a FROM ratings")->fetch_assoc()['a'];

$categories = $conn->query("SELECT category, COUNT(*) c FROM items GROUP BY category");
$catCounts = [];
while ($row = $categories->fetch_assoc()) {
    $catCounts[$row['category']] = $row['c'];
}

$topItems = $conn->query("SELECT * FROM items ORDER BY avg_rating DESC, total_reviews DESC LIMIT 6");

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="wrap hero-inner">
    <div>
      <span class="eyebrow"><i class="fa-solid fa-compass"></i> Kawasan Destinasi Super Prioritas</span>
      <h1>Jelajahi Labuan Bajo<br>lewat <span class="accent">rekomendasi cerdas</span></h1>
      <p class="hero-lead">Prototipe sistem rekomendasi pariwisata berbasis metode <strong>Collaborative Item-Based Filtering</strong> — dibangun dari data rating wisatawan untuk menemukan destinasi yang paling mirip minat Anda.</p>
      <div class="hero-actions">
        <a href="wisatawan.php" class="btn btn-primary"><i class="fa-solid fa-passport"></i> Coba Rekomendasi</a>
        <a href="destinasi.php" class="btn btn-ghost"><i class="fa-solid fa-map-location-dot"></i> Lihat Semua Destinasi</a>
      </div>
    </div>

    <div class="hero-card">
      <div class="hero-card-label">
        <span>Ringkasan Dataset</span>
        <span><i class="fa-solid fa-database"></i></span>
      </div>
      <h3>Data Penelitian</h3>
      <p class="small">Diambil dari simulasi rating wisatawan terhadap destinasi Labuan Bajo.</p>
      <div class="hero-stats">
        <div class="hero-stat"><b><?= $totalItems ?></b><span>Destinasi</span></div>
        <div class="hero-stat"><b><?= $totalUsers ?></b><span>Wisatawan</span></div>
        <div class="hero-stat"><b><?= $totalRatings ?></b><span>Rating</span></div>
        <div class="hero-stat"><b><?= $avgRating ?></b><span>Avg. Rating</span></div>
      </div>
    </div>
  </div>

  <svg class="wave-divider" viewBox="0 0 1200 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M0,30 C150,60 350,0 500,30 C650,60 850,0 1000,30 C1100,50 1150,20 1200,30 L1200,60 L0,60 Z" fill="#FAF6EC"/>
  </svg>
</section>

<section class="section" style="padding-top:0;">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow"><i class="fa-solid fa-layer-group"></i> Kategori</span>
        <h2>Empat kawasan, empat pengalaman</h2>
        <p class="section-lead">Destinasi dikelompokkan mengikuti struktur data penelitian, dari pulau &amp; pantai hingga kuliner khas Labuan Bajo.</p>
      </div>
    </div>

    <div class="grid">
      <?php foreach (['island_beach','diving_snorkeling','nature_adventure','accommodation_culinary'] as $cat):
        $meta = categoryMeta($cat); ?>
        <a href="destinasi.php?kategori=<?= urlencode($cat) ?>" class="dest-card <?= $meta['class'] ?>">
          <div class="dest-banner">
            <i class="fa-solid <?= $meta['icon'] ?> banner-icon"></i>
            <span class="banner-cat"><?= count($catCounts) ? ($catCounts[$cat] ?? 0) : 0 ?> destinasi</span>
          </div>
          <div class="dest-body">
            <h3><?= e($meta['label']) ?></h3>
            <p class="desc">Lihat seluruh destinasi dalam kategori ini beserta rating dan rekomendasi terkait.</p>
            <div class="dest-footer-link">Jelajahi <i class="fa-solid fa-arrow-right"></i></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" style="background:var(--paper); border-top:1px solid var(--line); border-bottom:1px solid var(--line);">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow"><i class="fa-solid fa-star"></i> Terpopuler</span>
        <h2>Destinasi dengan rating tertinggi</h2>
      </div>
      <a href="destinasi.php" class="btn btn-ghost btn-sm" style="color:var(--navy); border-color:var(--line);">Lihat semua <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="grid">
      <?php while ($item = $topItems->fetch_assoc()):
        $meta = categoryMeta($item['category']);
        $photo = getItemImage($item['item_id']); ?>
        <a href="detail.php?id=<?= e($item['item_id']) ?>" class="dest-card <?= $meta['class'] ?>">
          <div class="dest-banner <?= $photo ? 'has-photo' : '' ?>">
            <?php if ($photo): ?>
              <img src="<?= e($photo) ?>" alt="<?= e($item['item_name']) ?>" loading="lazy">
            <?php endif; ?>
            <i class="fa-solid <?= $meta['icon'] ?> banner-icon"></i>
            <span class="banner-cat"><?= e($meta['label']) ?></span>
          </div>
          <div class="dest-body">
            <h3><?= e($item['item_name']) ?></h3>
            <p class="desc"><?= e($item['description']) ?></p>
            <div class="dest-meta">
              <span class="stars"><?= renderStars((float)$item['avg_rating']) ?><span class="rating-num"><?= number_format($item['avg_rating'],2) ?></span></span>
              <span class="price-tag"><?= e($item['price_range']) ?></span>
            </div>
            <div class="dest-footer-link">Lihat Detail <i class="fa-solid fa-arrow-right"></i></div>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow"><i class="fa-solid fa-passport"></i> Mulai di sini</span>
        <h2>Pilih wisatawan, dapatkan rekomendasi</h2>
        <p class="section-lead">Sistem menghitung kemiripan antar destinasi dari histori rating, lalu merekomendasikan destinasi baru yang paling relevan untuk wisatawan terpilih.</p>
      </div>
      <a href="wisatawan.php" class="btn btn-primary btn-sm">Pilih Wisatawan <i class="fa-solid fa-arrow-right"></i></a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
