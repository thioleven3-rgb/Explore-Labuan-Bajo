<?php
/**
 * CollaborativeFiltering.php
 *
 * Implementasi PHP dari metode Collaborative Item-Based Filtering
 * yang digunakan pada penelitian:
 * "Sistem Rekomendasi Pariwisata Kawasan Destinasi Super Prioritas
 *  Labuan Bajo Menggunakan Metode Collaborative Item-Based Filtering"
 *
 * Alur (identik dengan notebook riset):
 * 1. Bangun User-Item Matrix dari tabel ratings
 * 2. Hitung Item Similarity antar destinasi menggunakan Cosine Similarity
 * 3. Prediksi rating item yang belum dinilai user (weighted sum)
 * 4. Urutkan hasil prediksi -> Top-N rekomendasi
 */

class CollaborativeFiltering
{
    private mysqli $conn;

    /** @var array<string, array<string, float>> user_id => [item_id => rating] */
    private array $userItemMatrix = [];

    /** @var string[] daftar semua item_id yang punya rating */
    private array $itemIds = [];

    /** @var string[] daftar semua user_id yang punya rating */
    private array $userIds = [];

    /** @var array<string, array<string, float>> cache similarity antar item */
    private array $similarityCache = [];

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->buildUserItemMatrix();
    }

    /**
     * Langkah 1: Bangun User-Item Matrix dari database
     * Setara dengan ratings_df.pivot_table(index='user_id', columns='item_name', values='rating')
     */
    private function buildUserItemMatrix(): void
    {
        $result = $this->conn->query("SELECT user_id, item_id, rating FROM ratings");

        $itemSet = [];
        $userSet = [];

        while ($row = $result->fetch_assoc()) {
            $u = $row['user_id'];
            $i = $row['item_id'];
            $r = (float) $row['rating'];

            $this->userItemMatrix[$u][$i] = $r;
            $itemSet[$i] = true;
            $userSet[$u] = true;
        }

        $this->itemIds = array_keys($itemSet);
        $this->userIds = array_keys($userSet);
    }

    /**
     * Ambil vektor rating suatu item di seluruh user (0 jika belum dirating)
     * Setara dengan mengambil satu kolom dari user_item_matrix
     */
    private function getItemVector(string $itemId): array
    {
        $vector = [];
        foreach ($this->userIds as $u) {
            $vector[] = $this->userItemMatrix[$u][$itemId] ?? 0.0;
        }
        return $vector;
    }

    /**
     * Langkah 2: Cosine Similarity antara dua vektor item
     *
     *              A . B
     * sim(A,B) = -----------
     *            |A| x |B|
     */
    private function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        $n = count($a);
        for ($k = 0; $k < $n; $k++) {
            $dot += $a[$k] * $b[$k];
            $normA += $a[$k] * $a[$k];
            $normB += $b[$k] * $b[$k];
        }

        if ($normA == 0.0 || $normB == 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Similarity antara dua item tertentu, dengan cache supaya efisien
     */
    public function itemSimilarity(string $itemA, string $itemB): float
    {
        if ($itemA === $itemB) {
            return 1.0;
        }

        $key = $itemA < $itemB ? "$itemA|$itemB" : "$itemB|$itemA";

        if (isset($this->similarityCache[$key])) {
            return $this->similarityCache[$key];
        }

        $vecA = $this->getItemVector($itemA);
        $vecB = $this->getItemVector($itemB);
        $sim = $this->cosineSimilarity($vecA, $vecB);

        $this->similarityCache[$key] = $sim;
        return $sim;
    }

    /**
     * Cari Top-N item paling mirip dengan sebuah item (untuk halaman detail destinasi)
     * Return: [ [item_id, similarity], ... ] terurut descending
     */
    public function getSimilarItems(string $itemId, int $topN = 4): array
    {
        $scores = [];
        foreach ($this->itemIds as $other) {
            if ($other === $itemId) {
                continue;
            }
            $scores[$other] = $this->itemSimilarity($itemId, $other);
        }

        arsort($scores);
        return array_slice($scores, 0, $topN, true);
    }

    /**
     * Langkah 3 & 4: Prediksi rating untuk item yang belum dinilai user,
     * lalu kembalikan Top-N rekomendasi.
     *
     *                  Σ ( sim(i, j) x rating(u, j) )   untuk j = item yang sudah dirating user u
     * predicted(u,i) = -------------------------------
     *                  Σ | sim(i, j) |
     *
     * Return: [ item_id => predicted_score, ... ] terurut descending, sebanyak topN
     */
    public function recommendForUser(string $userId, int $topN = 5): array
    {
        if (!isset($this->userItemMatrix[$userId])) {
            return [];
        }

        $ratedItems = $this->userItemMatrix[$userId]; // item_id => rating
        $unratedItems = array_diff($this->itemIds, array_keys($ratedItems));

        $predictions = [];

        foreach ($unratedItems as $candidate) {
            $weightedSum = 0.0;
            $similaritySum = 0.0;

            foreach ($ratedItems as $ratedItem => $rating) {
                $sim = $this->itemSimilarity($candidate, $ratedItem);
                $weightedSum += $sim * $rating;
                $similaritySum += abs($sim);
            }

            if ($similaritySum > 0) {
                $predictions[$candidate] = $weightedSum / $similaritySum;
            }
        }

        arsort($predictions);
        return array_slice($predictions, 0, $topN, true);
    }

    /**
     * Item-item yang sudah dirating oleh seorang user, terurut dari rating tertinggi
     * Return: [ item_id => rating, ... ]
     */
    public function getUserRatings(string $userId): array
    {
        if (!isset($this->userItemMatrix[$userId])) {
            return [];
        }
        $ratings = $this->userItemMatrix[$userId];
        arsort($ratings);
        return $ratings;
    }

    public function getTotalUsers(): int
    {
        return count($this->userIds);
    }

    public function getTotalItemsRated(): int
    {
        return count($this->itemIds);
    }
}
