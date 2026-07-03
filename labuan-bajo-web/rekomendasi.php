<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/classes/CollaborativeFiltering.php';

$conn = getConnection();
$userId = $_GET['user_id'] ?? '';

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param('s', $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    $pageTitle = 'Wisatawan tidak ditemukan';
    include __DIR__ . '/includes/header.php';
    echo '<div class="wrap section"><div class="empty-state"><i class="fa-solid fa-user-slash"></i><p>Wisatawan tidak ditemukan.</p><a href="wisatawan.php" class="btn btn-primary btn-sm">Kembali ke Daftar Wisatawan</a></div></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = 'Rekomendasi untuk ' . $user['display_name'];

$cf = new CollaborativeFiltering($conn);
$ratedRaw = $cf->getUserRatings($userId);
$recommendedRaw = $cf->recommendForUser($userId, 5);

function fetchItemsByIds(mysqli $conn, array $ids): array
{
    if (empty($ids)) return [];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("SELECT * FROM items WHERE item_id IN ($placeholders)");
    $types = str_repeat('s', count($ids));
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $res = $stmt->get_result();
    $lookup = [];
    while ($row = $res->fetch_assoc()) {
        $lookup[$row['item_id']] = $row;
    }
    return $lookup;
}

$ratedLookup = fetchItemsByIds($conn, array_keys($ratedRaw));
$recLookup = fetchItemsByIds($conn, array_keys($recommendedRaw));

include __DIR__ . '/includes/header.php';
?>

<section class="section" style="padding-top:48px;">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow"><i class="fa-solid fa-passport"></i> Profil Wisatawan</span>
        <h2>
          <span class="user-avatar" style="background:<?= avatarColor($user['user_id']) ?>; display:inline-flex; width:38px; height:38px; font-size:13px; vertical-align:middle; margin-right:10px;">
            <?= e(substr($user['user_id'], -2)) ?>
          </span>
          <?= e($user['display_name']) ?>
        </h2>
        <p class="section-lead">Rekomendasi dihitung dari kemiripan pola rating antar destinasi (Collaborative Item-Based Filtering), berdasarkan destinasi yang sudah pernah dinilai wisatawan ini.</p>
      </div>
      <a href="wisatawan.php" class="btn btn-ghost btn-sm" style="color:var(--navy); border-color:var(--line);"><i class="fa-solid fa-arrow-left"></i> Ganti Wisatawan</a>
    </div>

    <div class="detail-grid">
      <div>
        <div class="info-card">
          <h4><i class="fa-solid fa-wand-magic-sparkles" style="color:var(--coral);"></i> Rekomendasi untuk Anda</h4>

          <?php if (empty($recommendedRaw)): ?>
            <div class="empty-state">
              <i class="fa-solid fa-circle-info"></i>
              <p>Belum ada rekomendasi yang bisa dihitung &mdash; wisatawan ini mungkin sudah menilai semua destinasi, atau data rating masih terlalu sedikit untuk menghitung kemiripan.</p>
            </div>
          <?php else: ?>
            <div class="rec-list">
              <?php $i = 1; foreach ($recommendedRaw as $iid => $score):
                if (!isset($recLookup[$iid])) continue;
                $ritem = $recLookup[$iid];
                $rmeta = categoryMeta($ritem['category']);
                $rphoto = getItemImage($iid);
                $predicted = max(0, min(5, $score)); ?>
                <a href="detail.php?id=<?= e($iid) ?>" class="rec-item">
                  <span class="rank">#<?= $i++ ?></span>
                  <div class="rec-icon <?= $rmeta['class'] ?> <?= $rphoto ? 'has-photo' : '' ?>">
                    <?php if ($rphoto): ?><img src="<?= e($rphoto) ?>" alt=""><?php endif; ?>
                    <i class="fa-solid <?= $rmeta['icon'] ?>"></i>
                  </div>
                  <div class="rec-info">
                    <h4><?= e($ritem['item_name']) ?></h4>
                    <p class="rec-sub"><?= e($rmeta['label']) ?> &middot; rating umum <?= number_format($ritem['avg_rating'],1) ?></p>
                  </div>
                  <div class="stamp">
                    <b><?= number_format($predicted, 1) ?></b>
                    <span>prediksi</span>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div>
        <div class="info-card">
          <h4><i class="fa-solid fa-clock-rotate-left"></i> Sudah Pernah Dinilai</h4>
          <?php if (empty($ratedRaw)): ?>
            <p style="font-size:13.5px; color:var(--ink-soft);">Wisatawan ini belum memberi rating apapun.</p>
          <?php else: ?>
            <div class="rec-list">
              <?php foreach ($ratedRaw as $iid => $rating):
                if (!isset($ratedLookup[$iid])) continue;
                $ritem = $ratedLookup[$iid];
                $rmeta = categoryMeta($ritem['category']);
                $rphoto = getItemImage($iid); ?>
                <a href="detail.php?id=<?= e($iid) ?>" class="rec-item">
                  <div class="rec-icon <?= $rmeta['class'] ?> <?= $rphoto ? 'has-photo' : '' ?>">
                    <?php if ($rphoto): ?><img src="<?= e($rphoto) ?>" alt=""><?php endif; ?>
                    <i class="fa-solid <?= $rmeta['icon'] ?>"></i>
                  </div>
                  <div class="rec-info">
                    <h4><?= e($ritem['item_name']) ?></h4>
                    <p class="rec-sub"><?= e($rmeta['label']) ?></p>
                  </div>
                  <div class="stamp stamp-gold" style="width:56px; height:56px;">
                    <b style="font-size:13px;"><?= number_format($rating,0) ?></b>
                    <span style="font-size:7px;">rating</span>
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
