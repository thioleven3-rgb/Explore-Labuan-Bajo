<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$conn = getConnection();
$pageTitle = 'Destinasi';

$activeCat = $_GET['kategori'] ?? 'all';
$items = $conn->query("SELECT * FROM items ORDER BY category, avg_rating DESC");

$catCounts = [];
$countRes = $conn->query("SELECT category, COUNT(*) c FROM items GROUP BY category");
while ($row = $countRes->fetch_assoc()) {
    $catCounts[$row['category']] = $row['c'];
}
$totalAll = array_sum($catCounts);

$allItems = [];
while ($row = $items->fetch_assoc()) {
    $allItems[] = $row;
}

include __DIR__ . '/includes/header.php';
?>

<section class="section" style="padding-top:48px;">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow"><i class="fa-solid fa-map-location-dot"></i> Direktori</span>
        <h2>Semua Destinasi</h2>
        <p class="section-lead">20 destinasi di kawasan Labuan Bajo, dikelompokkan berdasarkan kategori pada dataset penelitian.</p>
      </div>
    </div>

    <div class="cat-tabs">
      <button class="cat-tab <?= $activeCat === 'all' ? 'active' : '' ?>" data-filter="all">Semua <span class="count">(<?= $totalAll ?>)</span></button>
      <?php foreach (['island_beach','diving_snorkeling','nature_adventure','accommodation_culinary'] as $cat):
        $meta = categoryMeta($cat); ?>
        <button class="cat-tab <?= $activeCat === $cat ? 'active' : '' ?>" data-filter="<?= $cat ?>">
          <i class="fa-solid <?= $meta['icon'] ?>"></i> <?= e($meta['label']) ?> <span class="count">(<?= $catCounts[$cat] ?? 0 ?>)</span>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="grid">
      <?php foreach ($allItems as $item):
        $meta = categoryMeta($item['category']);
        $photo = getItemImage($item['item_id']); ?>
        <a href="detail.php?id=<?= e($item['item_id']) ?>" class="dest-card <?= $meta['class'] ?>" data-category="<?= e($item['category']) ?>">
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
              <span class="price-tag"><?= e($item['price_range']) ?> &middot; <?= e(priceLabel($item['price_range'])) ?></span>
            </div>
            <div class="dest-footer-link">Lihat Detail <i class="fa-solid fa-arrow-right"></i></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
  // Apply category from URL query on load
  document.addEventListener('DOMContentLoaded', function () {
    var active = <?= json_encode($activeCat) ?>;
    if (active !== 'all') {
      document.querySelectorAll('[data-category]').forEach(function (card) {
        card.style.display = card.getAttribute('data-category') === active ? '' : 'none';
      });
    }
  });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
