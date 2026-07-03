<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?>Labuan Bajo Explorer</title>
<meta name="description" content="Sistem Rekomendasi Pariwisata Labuan Bajo menggunakan Collaborative Item-Based Filtering">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700;9..144,900&family=Manrope:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="chart-lines" aria-hidden="true"></div>

<header class="site-header">
  <div class="wrap header-inner">
    <a href="index.php" class="brand">
      <span class="brand-mark"><i class="fa-solid fa-water"></i></span>
      <span class="brand-text">LB <em>Explorer</em></span>
    </a>

    <nav class="main-nav" aria-label="Navigasi utama">
      <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">Beranda</a>
      <a href="destinasi.php" class="<?= $currentPage === 'destinasi.php' || $currentPage === 'detail.php' ? 'active' : '' ?>">Destinasi</a>
      <a href="wisatawan.php" class="<?= $currentPage === 'wisatawan.php' || $currentPage === 'rekomendasi.php' ? 'active' : '' ?>">Wisatawan</a>
      <a href="tentang.php" class="<?= $currentPage === 'tentang.php' ? 'active' : '' ?>">Tentang Metode</a>
    </nav>

    <button class="nav-toggle" aria-label="Buka menu navigasi">
      <i class="fa-solid fa-bars"></i>
    </button>
  </div>
</header>

<main>
