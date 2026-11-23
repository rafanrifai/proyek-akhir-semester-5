<?php

namespace App\Services;

class DecisionTreeService
{
    /**
     * Dataset latih (Training Data) untuk pembentukan Pohon Keputusan C4.5
     */
    protected array $trainingData = [
        // Usia: BARU, SEDANG, TUA
        // Intensitas: RENDAH, SEDANG, TINGGI
        // Fisik: BAIK, CACAT_RINGAN, RUSAK_SEBAGIAN, RUSAK_TOTAL
        // Biaya: KECIL, SEDANG, BESAR
        // Riwayat: TIDAK_ADA, 1_2_KALI, LEBIH_2_KALI
        // Label: BAIK, RUSAK_RINGAN, RUSAK_SEDANG, RUSAK_BERAT

        ['usia' => 'BARU',   'intensitas' => 'RENDAH', 'fisik' => 'BAIK',           'biaya' => 'KECIL',  'riwayat' => 'TIDAK_ADA',     'label' => 'BAIK'],
        ['usia' => 'BARU',   'intensitas' => 'SEDANG', 'fisik' => 'BAIK',           'biaya' => 'KECIL',  'riwayat' => 'TIDAK_ADA',     'label' => 'BAIK'],
        ['usia' => 'BARU',   'intensitas' => 'TINGGI', 'fisik' => 'CACAT_RINGAN',   'biaya' => 'KECIL',  'riwayat' => 'TIDAK_ADA',     'label' => 'RUSAK_RINGAN'],
        ['usia' => 'BARU',   'intensitas' => 'SEDANG', 'fisik' => 'CACAT_RINGAN',   'biaya' => 'KECIL',  'riwayat' => '1_2_KALI',      'label' => 'RUSAK_RINGAN'],
        ['usia' => 'BARU',   'intensitas' => 'TINGGI', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'SEDANG', 'riwayat' => '1_2_KALI',      'label' => 'RUSAK_SEDANG'],
        ['usia' => 'BARU',   'intensitas' => 'RENDAH', 'fisik' => 'RUSAK_TOTAL',    'biaya' => 'BESAR',  'riwayat' => 'TIDAK_ADA',     'label' => 'RUSAK_BERAT'],

        ['usia' => 'SEDANG', 'intensitas' => 'RENDAH', 'fisik' => 'BAIK',           'biaya' => 'KECIL',  'riwayat' => 'TIDAK_ADA',     'label' => 'BAIK'],
        ['usia' => 'SEDANG', 'intensitas' => 'SEDANG', 'fisik' => 'BAIK',           'biaya' => 'KECIL',  'riwayat' => '1_2_KALI',      'label' => 'BAIK'],
        ['usia' => 'SEDANG', 'intensitas' => 'TINGGI', 'fisik' => 'CACAT_RINGAN',   'biaya' => 'KECIL',  'riwayat' => '1_2_KALI',      'label' => 'RUSAK_RINGAN'],
        ['usia' => 'SEDANG', 'intensitas' => 'SEDANG', 'fisik' => 'CACAT_RINGAN',   'biaya' => 'KECIL',  'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_RINGAN'],
        ['usia' => 'SEDANG', 'intensitas' => 'SEDANG', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'SEDANG', 'riwayat' => '1_2_KALI',      'label' => 'RUSAK_SEDANG'],
        ['usia' => 'SEDANG', 'intensitas' => 'TINGGI', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'SEDANG', 'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_SEDANG'],
        ['usia' => 'SEDANG', 'intensitas' => 'TINGGI', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'BESAR',  'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_BERAT'],
        ['usia' => 'SEDANG', 'intensitas' => 'RENDAH', 'fisik' => 'RUSAK_TOTAL',    'biaya' => 'BESAR',  'riwayat' => '1_2_KALI',      'label' => 'RUSAK_BERAT'],
        ['usia' => 'SEDANG', 'intensitas' => 'TINGGI', 'fisik' => 'RUSAK_TOTAL',    'biaya' => 'BESAR',  'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_BERAT'],

        ['usia' => 'TUA',    'intensitas' => 'RENDAH', 'fisik' => 'BAIK',           'biaya' => 'KECIL',  'riwayat' => 'TIDAK_ADA',     'label' => 'BAIK'],
        ['usia' => 'TUA',    'intensitas' => 'SEDANG', 'fisik' => 'CACAT_RINGAN',   'biaya' => 'KECIL',  'riwayat' => '1_2_KALI',      'label' => 'RUSAK_RINGAN'],
        ['usia' => 'TUA',    'intensitas' => 'TINGGI', 'fisik' => 'CACAT_RINGAN',   'biaya' => 'SEDANG', 'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_SEDANG'],
        ['usia' => 'TUA',    'intensitas' => 'RENDAH', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'SEDANG', 'riwayat' => '1_2_KALI',      'label' => 'RUSAK_SEDANG'],
        ['usia' => 'TUA',    'intensitas' => 'SEDANG', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'BESAR',  'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_BERAT'],
        ['usia' => 'TUA',    'intensitas' => 'TINGGI', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'BESAR',  'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_BERAT'],
        ['usia' => 'TUA',    'intensitas' => 'RENDAH', 'fisik' => 'RUSAK_TOTAL',    'biaya' => 'BESAR',  'riwayat' => '1_2_KALI',      'label' => 'RUSAK_BERAT'],
        ['usia' => 'TUA',    'intensitas' => 'SEDANG', 'fisik' => 'RUSAK_TOTAL',    'biaya' => 'BESAR',  'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_BERAT'],
        ['usia' => 'TUA',    'intensitas' => 'TINGGI', 'fisik' => 'RUSAK_TOTAL',    'biaya' => 'BESAR',  'riwayat' => 'LEBIH_2_KALI',  'label' => 'RUSAK_BERAT'],

        ['usia' => 'BARU',   'intensitas' => 'TINGGI', 'fisik' => 'BAIK',           'biaya' => 'KECIL',  'riwayat' => '1_2_KALI',      'label' => 'BAIK'],
        ['usia' => 'SEDANG', 'intensitas' => 'RENDAH', 'fisik' => 'CACAT_RINGAN',   'biaya' => 'KECIL',  'riwayat' => 'TIDAK_ADA',     'label' => 'RUSAK_RINGAN'],
        ['usia' => 'BARU',   'intensitas' => 'SEDANG', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'KECIL',  'riwayat' => 'TIDAK_ADA',     'label' => 'RUSAK_RINGAN'],
        ['usia' => 'TUA',    'intensitas' => 'SEDANG', 'fisik' => 'RUSAK_SEBAGIAN', 'biaya' => 'SEDANG', 'riwayat' => '1_2_KALI',      'label' => 'RUSAK_SEDANG'],
        ['usia' => 'SEDANG', 'intensitas' => 'TINGGI', 'fisik' => 'CACAT_RINGAN',   'biaya' => 'SEDANG', 'riwayat' => '1_2_KALI',      'label' => 'RUSAK_SEDANG'],
        ['usia' => 'BARU',   'intensitas' => 'TINGGI', 'fisik' => 'RUSAK_TOTAL',    'biaya' => 'BESAR',  'riwayat' => '1_2_KALI',      'label' => 'RUSAK_BERAT'],
    ];

    protected array $attributes = [
        'fisik',
        'biaya',
        'usia',
        'intensitas',
        'riwayat'
    ];

    /**
     * Dapatkan dataset training
     */
    public function getTrainingData(): array
    {
        return $this->trainingData;
    }

    /**
     * Hitung Entropy dari suatu subset data
     */
    public function calculateEntropy(array $data): float
    {
        $total = count($data);
        if ($total === 0) {
            return 0.0;
        }

        $counts = [];
        foreach ($data as $row) {
            $label = $row['label'];
            $counts[$label] = ($counts[$label] ?? 0) + 1;
        }

        $entropy = 0.0;
        foreach ($counts as $count) {
            $p = $count / $total;
            $entropy -= $p * log($p, 2);
        }

        return round($entropy, 4);
    }

    /**
     * Hitung Information Gain & Gain Ratio untuk satu atribut
     */
    public function calculateGain(array $data, string $attribute): array
    {
        $totalEntropy = $this->calculateEntropy($data);
        $totalCount = count($data);

        // Pisahkan data per nilai atribut
        $subsets = [];
        foreach ($data as $row) {
            $val = $row[$attribute];
            $subsets[$val][] = $row;
        }

        $subsetEntropySum = 0.0;
        $splitInfo = 0.0;

        foreach ($subsets as $val => $subset) {
            $subsetCount = count($subset);
            $weight = $subsetCount / $totalCount;
            $subEntropy = $this->calculateEntropy($subset);
            $subsetEntropySum += $weight * $subEntropy;

            if ($weight > 0) {
                $splitInfo -= $weight * log($weight, 2);
            }
        }

        $gain = $totalEntropy - $subsetEntropySum;
        $gainRatio = ($splitInfo > 0) ? ($gain / $splitInfo) : $gain;

        return [
            'attribute' => $attribute,
            'gain' => round($gain, 4),
            'split_info' => round($splitInfo, 4),
            'gain_ratio' => round($gainRatio, 4),
            'subsets_count' => array_map('count', $subsets)
        ];
    }

    /**
     * Hitung Gain dari semua atribut untuk dataset saat ini
     */
    public function evaluateAllAttributes(array $data, array $availableAttributes): array
    {
        $results = [];
        foreach ($availableAttributes as $attr) {
            $results[$attr] = $this->calculateGain($data, $attr);
        }

        // Urutkan berdasarkan gain ratio tertinggi
        uasort($results, fn($a, $b) => $b['gain_ratio'] <=> $a['gain_ratio']);

        return $results;
    }

    /**
     * Membangun Pohon Keputusan C4.5 secara rekursif
     */
    public function buildTree(array $data = null, array $availableAttributes = null): array
    {
        if ($data === null) {
            $data = $this->trainingData;
        }
        if ($availableAttributes === null) {
            $availableAttributes = $this->attributes;
        }

        // 1. Cek apakah semua sampel memiliki label yang sama
        $labels = array_column($data, 'label');
        $uniqueLabels = array_unique($labels);

        if (count($uniqueLabels) === 1) {
            return [
                'type' => 'leaf',
                'label' => reset($uniqueLabels),
                'count' => count($data),
                'confidence' => 1.0,
            ];
        }

        // 2. Jika tidak ada atribut tersisa, kembalikan label mayoritas
        if (empty($availableAttributes)) {
            $counts = array_count_values($labels);
            arsort($counts);
            $majority = array_key_first($counts);
            return [
                'type' => 'leaf',
                'label' => $majority,
                'count' => count($data),
                'confidence' => round($counts[$majority] / count($data), 2),
            ];
        }

        // 3. Evaluasi atribut terbaik menggunakan Gain Ratio
        $evaluations = $this->evaluateAllAttributes($data, $availableAttributes);
        $bestAttr = array_key_first($evaluations);
        $bestGainRatio = $evaluations[$bestAttr]['gain_ratio'];

        if ($bestGainRatio <= 0) {
            $counts = array_count_values($labels);
            arsort($counts);
            $majority = array_key_first($counts);
            return [
                'type' => 'leaf',
                'label' => $majority,
                'count' => count($data),
                'confidence' => round($counts[$majority] / count($data), 2),
            ];
        }

        // 4. Buat node percabangan
        $remainingAttributes = array_diff($availableAttributes, [$bestAttr]);
        $branches = [];

        // Kelompokkan data per nilai atribut terbaik
        $subsets = [];
        foreach ($data as $row) {
            $val = $row[$bestAttr];
            $subsets[$val][] = $row;
        }

        foreach ($subsets as $attrVal => $subset) {
            $branches[$attrVal] = $this->buildTree($subset, $remainingAttributes);
        }

        return [
            'type' => 'node',
            'attribute' => $bestAttr,
            'gain_ratio' => $bestGainRatio,
            'branches' => $branches,
        ];
    }

    /**
     * Prediksi satu data masukan menggunakan Pohon Keputusan
     */
    public function predict(array $input): array
    {
        $tree = $this->buildTree();
        $currentNode = $tree;
        $rulePath = [];

        while ($currentNode['type'] === 'node') {
            $attr = $currentNode['attribute'];
            $val = $input[$attr] ?? null;

            $rulePath[] = [
                'attribute' => $attr,
                'value' => $val,
            ];

            if ($val !== null && isset($currentNode['branches'][$val])) {
                $currentNode = $currentNode['branches'][$val];
            } else {
                // Fallback jika nilai tidak ditemukan pada cabang spesifik
                $firstBranch = reset($currentNode['branches']);
                $currentNode = $firstBranch;
            }
        }

        $predictedLabel = $currentNode['label'];
        $confidence = $currentNode['confidence'] ?? 0.85;

        // Buat rekomendasi operasional
        $rekomendasi = $this->generateRecommendation($predictedLabel, $input);

        return [
            'hasil_klasifikasi' => $predictedLabel,
            'confidence' => $confidence,
            'rule_path' => $rulePath,
            'rule_text' => $this->formatRuleText($rulePath, $predictedLabel),
            'rekomendasi' => $rekomendasi,
        ];
    }

    /**
     * Konversi alur pohon keputusan menjadi teks terbaca manusia
     */
    protected function formatRuleText(array $path, string $label): string
    {
        $conditions = [];
        $labelsMap = [
            'usia' => 'Usia Barang',
            'intensitas' => 'Intensitas Pemakaian',
            'fisik' => 'Kondisi Fisik',
            'biaya' => 'Estimasi Biaya',
            'riwayat' => 'Riwayat Kerusakan',
        ];

        foreach ($path as $step) {
            $attrName = $labelsMap[$step['attribute']] ?? $step['attribute'];
            $val = str_replace('_', ' ', $step['value']);
            $conditions[] = "{$attrName} = {$val}";
        }

        $ruleStr = implode(' DAN ', $conditions);
        return "JIKA " . $ruleStr . " MAKA Kategori Kerusakan: " . str_replace('_', ' ', $label);
    }

    /**
     * Rekomendasi manajerial & teknis berdasarkan hasil prediksi
     */
    protected function generateRecommendation(string $label, array $input): string
    {
        switch ($label) {
            case 'BAIK':
                return 'Aset dalam kondisi optimal. Disarankan untuk dilakukan pemeliharaan berkala (preventive maintenance) dan pencatatan inventaris secara rutin.';
            case 'RUSAK_RINGAN':
                return 'Aset mengalami penurunan minor pada komponen pendukung. Disarankan tindakan perbaikan internal segera agar tidak merambat menjadi kerusakan yang lebih parah.';
            case 'RUSAK_SEDANG':
                return 'Aset mengalami gangguan pada sebagian fungsi operasional. Disarankan segera dijadwalkan servis besar / pergantian suku cadang oleh teknisi atau pihak ketiga.';
            case 'RUSAK_BERAT':
                return 'Aset mengalami kerusakan total / biaya perbaikan melebihi nilai ekonomis. Disarankan untuk tidak digunakan lebih lanjut dan diusulkan dalam proses penghapusan aset (SK Penghapusan BMN).';
            default:
                return 'Perlu peninjauan fisik langsung oleh petugas BMN.';
        }
    }
}

