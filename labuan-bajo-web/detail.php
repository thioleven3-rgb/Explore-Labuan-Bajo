<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/classes/CollaborativeFiltering.php';

$conn = getConnection();
$itemId = $_GET['id'] ?? '';

$stmt = $conn->prepare("SELECT * FROM items WHERE item_id = ?");
$stmt->bind_param('s', $itemId);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    $pageTitle = 'Destinasi tidak ditemukan';
    include __DIR__ . '/includes/header.php';
    echo '<div class="wrap section"><div class="empty-state"><i class="fa-solid fa-map-pin"></i><p>Destinasi tidak ditemukan.</p><a href="destinasi.php" class="btn btn-primary btn-sm">Kembali ke Destinasi</a></div></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $item['item_name'];
$meta = categoryMeta($item['category']);
$photo = getItemImage($item['item_id']);

$cf = new CollaborativeFiltering($conn);
$similarRaw = $cf->getSimilarItems($itemId, 4);

$similarItems = [];
if (!empty($similarRaw)) {
    $ids = array_keys($similarRaw);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt2 = $conn->prepare("SELECT * FROM items WHERE item_id IN ($placeholders)");
    $types = str_repeat('s', count($ids));
    $stmt2->bind_param($types, ...$ids);
    $stmt2->execute();
    $res = $stmt2->get_result();
    $lookup = [];
    while ($row = $res->fetch_assoc()) {
        $lookup[$row['item_id']] = $row;
    }
    foreach ($similarRaw as $sid => $score) {
        if (isset($lookup[$sid])) {
            $similarItems[] = ['item' => $lookup[$sid], 'score' => $score];
        }
    }
}

// Recent reviewers count of this item (for context)
$stmt3 = $conn->prepare("SELECT COUNT(*) c FROM ratings WHERE item_id = ?");
$stmt3->bind_param('s', $itemId);
$stmt3->execute();
$reviewCount = $stmt3->get_result()->fetch_assoc()['c'];

include __DIR__ . '/includes/header.php';
?>

<section class="section" style="padding-bottom:0;">
  <div class="wrap">
    <div class="detail-hero <?= $meta['class'] ?> <?= $photo ? 'has-photo' : '' ?>">
      <?php if ($photo): ?>
        <img src="<?= e($photo) ?>" alt="<?= e($item['item_name']) ?>">
      <?php endif; ?>
      <div class="detail-hero-inner">
        <div>
          <span class="badge-cat"><i class="fa-solid <?= $meta['icon'] ?>"></i> <?= e($meta['label']) ?></span>
          <h1><?= e($item['item_name']) ?></h1>
          <p class="loc"><i class="fa-solid fa-location-dot"></i> <?= e($item['location']) ?></p>
        </div>
        <div class="stamp stamp-gold">
          <b><?= number_format($item['avg_rating'], 1) ?></b>
          <span>/ 5 rating</span>
        </div>
      </div>
    </div>

    <div class="detail-grid">
      <div>
        <div class="info-card">
          <h4><i class="fa-solid fa-align-left"></i> Deskripsi</h4>
          <p><?= e($item['description']) ?></p>
        </div>

        <div class="stat-row">
          <div class="stat-block"><b><?= number_format($item['avg_rating'], 2) ?></b><span>Rata-rata Rating</span></div>
          <div class="stat-block"><b><?= (int)$item['total_reviews'] ?></b><span>Total Ulasan (dataset)</span></div>
          <div class="stat-block"><b><?= (int)$reviewCount ?></b><span>Rating Tercatat</span></div>
          <div class="stat-block"><b><?= e($item['price_range']) ?></b><span><?= e(priceLabel($item['price_range'])) ?></span></div>
        </div>

        <div class="info-card">
          <h4><i class="fa-solid fa-diagram-project"></i> Bagaimana kemiripan dihitung?</h4>
          <p style="font-size:13.5px; color:var(--ink-soft);">Sistem membandingkan pola rating destinasi ini dengan destinasi lain menggunakan <strong>Cosine Similarity</strong> pada vektor rating antar wisatawan. Semakin mirip pola penilaiannya, semakin tinggi skor kemiripan.</p>
        </div>
      </div>

      <div>
        <div class="info-card">
          <h4><i class="fa-solid fa-star-half-stroke"></i> Wisatawan Serupa Juga Menyukai</h4>
          <?php if (empty($similarItems)): ?>
            <p style="font-size:13.5px; color:var(--ink-soft);">Belum cukup data rating untuk menghitung kemiripan destinasi ini.</p>
          <?php else: ?>
            <div class="rec-list">
              <?php foreach ($similarItems as $row):
                $simMeta = categoryMeta($row['item']['category']);
                $simPhoto = getItemImage($row['item']['item_id']);
                $pct = round($row['score'] * 100); ?>
                <a href="detail.php?id=<?= e($row['item']['item_id']) ?>" class="rec-item">
                  <div class="rec-icon <?= $simMeta['class'] ?> <?= $simPhoto ? 'has-photo' : '' ?>">
                    <?php if ($simPhoto): ?><img src="<?= e($simPhoto) ?>" alt=""><?php endif; ?>
                    <i class="fa-solid <?= $simMeta['icon'] ?>"></i>
                  </div>
                  <div class="rec-info">
                    <h4><?= e($row['item']['item_name']) ?></h4>
                    <p class="rec-sub"><?= e($simMeta['label']) ?> &middot; <?= number_format($row['item']['avg_rating'],1) ?> <i class="fa-solid fa-star" style="color:var(--gold); font-size:10px;"></i></p>
                  </div>
                  <div class="stamp stamp-teal" style="width:56px; height:56px;">
                    <b style="font-size:13px;"><?= $pct ?>%</b>
                    <span style="font-size:7px;">mirip</span>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
