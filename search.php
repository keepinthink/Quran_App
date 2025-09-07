<?php
include 'layout/header.php';

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($query)) {
    echo '<p class="text-center">Silakan masukkan kata kunci pencarian.</p>';
    include 'layout/footer.php';
    exit;
}

function get_base_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    return rtrim($protocol . $host . $script, '/');
}
define('BASE_URL', get_base_url());

$apiUrl = BASE_URL . "/api/index.php?endpoint=search&q=" . urlencode($query);
$response = @file_get_contents($apiUrl);
$results = $response ? json_decode($response, true) : null;

function highlight_keyword($text, $keyword) {
    return preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<span class="bg-yellow-200 dark:bg-yellow-600 font-bold">$1</span>', $text);
}
?>

<div class="text-center mb-8">
    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Hasil Pencarian</h1>
    <p class="text-slate-600 dark:text-slate-400 mt-2">Menampilkan hasil untuk: "<strong><?= htmlspecialchars($query) ?></strong>"</p>
</div>

<?php if ($results && $results['code'] == 200 && !empty($results['data'])): ?>
    
    <?php if (isset($results['data']['type']) && $results['data']['type'] === 'surah_match'): ?>
        <h2 class="text-xl font-semibold mb-4 text-slate-800 dark:text-slate-200">Surah Ditemukan:</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            <?php foreach ($results['data']['matches'] as $surah): ?>
                <a href="surah.php?nomor=<?= $surah['nomor'] ?>" class="block p-5 bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-slate-700 rounded-full font-bold text-teal-600 dark:text-teal-400">
                                <?= $surah['nomor'] ?>
                            </div>
                            <div>
                                <p class="font-semibold text-lg text-slate-800 dark:text-slate-100"><?= highlight_keyword(htmlspecialchars($surah['namaLatin']), $query) ?></p>
                                <p class="text-sm text-slate-500 dark:text-slate-400"><?= htmlspecialchars($surah['arti']) ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-arabic text-2xl text-slate-900 dark:text-white"><?= $surah['nama'] ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    
    <?php else: ?>
        <h2 class="text-xl font-semibold mb-4 text-slate-800 dark:text-slate-200">Ayat Ditemukan:</h2>
        <div class="space-y-6">
            <?php foreach ($results['data'] as $result): ?>
                <div class="p-5 bg-white dark:bg-slate-800 rounded-lg shadow-md">
                    <a href="surah.php?nomor=<?= $result['surat']['nomor'] ?>#ayat-<?= $result['nomorAyat'] ?>" class="text-lg font-semibold text-teal-600 dark:text-teal-400 hover:underline">
                        <?= htmlspecialchars($result['surat']['namaLatin']) ?>: Ayat <?= $result['nomorAyat'] ?>
                    </a>
                    <p class="font-arabic text-2xl text-right my-3 text-slate-900 dark:text-white"><?= $result['teksArab'] ?></p>
                    <p class="text-slate-700 dark:text-slate-300">
                        <?= highlight_keyword(htmlspecialchars($result['teksIndonesia']), $query) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php else: ?>
    <div class="text-center p-8 bg-amber-100 dark:bg-amber-900/50 rounded-lg">
        <p class="text-amber-700 dark:text-amber-300">Tidak ada hasil yang ditemukan untuk "<strong><?= htmlspecialchars($query) ?></strong>".</p>
        <a href="index.php" class="mt-4 inline-block px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Kembali ke Beranda</a>
    </div>
<?php endif; ?>

<?php include 'layout/footer.php'; ?>