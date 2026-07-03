<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$conn = getConnection();
$pageTitle = 'Wisatawan';

$sql = "SELECT u.user_id, u.display_name, COUNT(r.rating_id) total_rating, ROUND(AVG(r.rating),2) avg_given
        FROM users u
        LEFT JOIN ratings r ON r.user_id = u.user_id
        GROUP BY u.user_id, u.display_name
        ORDER BY u.user_id";
$users = $conn->query($sql);

$userList = [];
while ($row = $users->fetch_assoc()) {
    $userList[] = $row;
}

include __DIR__ . '/includes/header.php';
?>

<section class="section" style="padding-top:48px;">
  <div class="wrap">
    <div class="section-head">
      <div>
        <span class="eyebrow"><i class="fa-solid fa-passport"></i> Data Penelitian</span>
        <h2>Pilih Wisatawan</h2>
        <p class="section-lead">50 wisatawan dari dataset simulasi penelitian. Pilih salah satu untuk melihat histori rating dan rekomendasi destinasi yang dihasilkan sistem.</p>
      </div>
    </div>

    <div class="user-grid">
      <?php foreach ($userList as $u): ?>
        <a href="rekomendasi.php?user_id=<?= e($u['user_id']) ?>" class="user-card">
          <div class="user-avatar" style="background:<?= avatarColor($u['user_id']) ?>;">
            <?= e(substr($u['user_id'], -2)) ?>
          </div>
          <h4><?= e($u['display_name']) ?></h4>
          <p class="user-sub"><?= (int)$u['total_rating'] ?> destinasi dinilai<?= $u['avg_given'] ? ' &middot; rata-rata ' . number_format($u['avg_given'],1) : '' ?></p>
          <span class="user-chip"><i class="fa-solid fa-wand-magic-sparkles"></i> Lihat Rekomendasi</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
