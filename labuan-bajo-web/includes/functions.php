<?php
/**
 * Helper functions untuk tampilan (mapping kategori -> ikon/warna, format, dsb)
 */

function categoryMeta(string $category): array
{
    $map = [
        'island_beach' => [
            'label' => 'Pulau & Pantai',
            'icon'  => 'fa-umbrella-beach',
            'class' => 'cat-island',
        ],
        'diving_snorkeling' => [
            'label' => 'Diving & Snorkeling',
            'icon'  => 'fa-water',
            'class' => 'cat-diving',
        ],
        'nature_adventure' => [
            'label' => 'Alam & Petualangan',
            'icon'  => 'fa-mountain',
            'class' => 'cat-nature',
        ],
        'accommodation_culinary' => [
            'label' => 'Akomodasi & Kuliner',
            'icon'  => 'fa-utensils',
            'class' => 'cat-culinary',
        ],
    ];

    return $map[$category] ?? [
        'label' => ucwords(str_replace('_', ' ', $category)),
        'icon'  => 'fa-map-marker-alt',
        'class' => 'cat-island',
    ];
}

/**
 * Cek apakah ada file foto untuk sebuah destinasi di assets/img/destinations/
 * Naming convention: {item_id}.jpg / .jpeg / .png / .webp
 * Return: path relatif untuk dipakai di <img src="..."> ATAU null jika tidak ada foto
 */
function getItemImage(string $itemId): ?string
{
    $extensions = ['jpg', 'jpeg', 'png', 'webp'];
    foreach ($extensions as $ext) {
        $relativePath = "assets/img/destinations/{$itemId}.{$ext}";
        $fullPath = __DIR__ . '/../' . $relativePath;
        if (file_exists($fullPath)) {
            return $relativePath;
        }
    }
    return null;
}

function priceLabel(string $priceRange): string
{
    $labels = [
        '$'   => 'Terjangkau',
        '$$'  => 'Menengah',
        '$$$' => 'Premium',
    ];
    return $labels[$priceRange] ?? $priceRange;
}

function renderStars(float $rating): string
{
    $full = (int) floor($rating);
    $half = ($rating - $full) >= 0.5;
    $html = '';

    for ($i = 0; $i < $full; $i++) {
        $html .= '<i class="fa-solid fa-star"></i>';
    }
    if ($half) {
        $html .= '<i class="fa-solid fa-star-half-stroke"></i>';
    }
    $empty = 5 - $full - ($half ? 1 : 0);
    for ($i = 0; $i < $empty; $i++) {
        $html .= '<i class="fa-regular fa-star"></i>';
    }

    return $html;
}

/** Warna avatar konsisten berdasarkan hash user_id, untuk kartu wisatawan */
function avatarColor(string $userId): string
{
    $palette = ['#1B7A8C', '#E8734A', '#2E8B57', '#C9A227', '#6E5AA0', '#C0447A'];
    $index = crc32($userId) % count($palette);
    return $palette[$index];
}

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
