<?php
// Set header sebagai JSON dan izinkan akses dari mana saja
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

define('API_BASE_URL', 'https://equran.id/api/v2/');

$endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : '';

switch ($endpoint) {
    case 'surah':
        $surahId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $url = $surahId > 0 ? API_BASE_URL . 'surat/' . $surahId : API_BASE_URL . 'surat';
        break;

    // [PERUBAHAN UTAMA DIMULAI DI SINI]
    case 'search':
        $query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
        if (empty($query)) {
            echo json_encode(['code' => 400, 'status' => 'Bad Request', 'message' => 'Query pencarian tidak boleh kosong.']);
            exit;
        }

        // 1. Coba cari kecocokan dengan nama surah terlebih dahulu
        $allSurahResponse = @file_get_contents(API_BASE_URL . 'surat');
        if ($allSurahResponse) {
            $allSurahData = json_decode($allSurahResponse, true);
            $foundSurahs = [];
            foreach ($allSurahData['data'] as $surah) {
                // Mencari jika query adalah bagian dari nama latin surah (case-insensitive)
                if (strpos(strtolower($surah['namaLatin']), $query) !== false) {
                    $foundSurahs[] = $surah;
                }
            }

            // Jika ada surah yang cocok, kembalikan hasil khusus ini
            if (!empty($foundSurahs)) {
                echo json_encode([
                    'code' => 200,
                    'status' => 'OK',
                    'message' => 'Surah ditemukan berdasarkan nama.',
                    'data' => [
                        'type' => 'surah_match', // Penanda tipe hasil
                        'matches' => $foundSurahs
                    ]
                ]);
                exit; // Hentikan skrip di sini
            }
        }

        // 2. Jika tidak ada nama surah yang cocok, baru cari di terjemahan
        $url = API_BASE_URL . 'search/' . urlencode($query);
        break;
    // [PERUBAHAN SELESAI]
    
    default:
        http_response_code(404);
        echo json_encode(['code' => 404, 'status' => 'Not Found', 'message' => 'Endpoint tidak valid.']);
        exit;
}

$response = @file_get_contents($url);

if ($response === FALSE) {
    http_response_code(500);
    echo json_encode(['code' => 500, 'status' => 'Internal Server Error', 'message' => 'Gagal mengambil data dari sumber.']);
} else {
    echo $response;
}
?>