<?php
include 'layout/header.php';

// Fungsi untuk mendapatkan base URL dinamis
function get_base_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    // Hapus backslash di akhir jika ada, dan pastikan tidak ada double slash
    return rtrim($protocol . $host . $script, '/');
}

define('BASE_URL', get_base_url());

$apiUrl = BASE_URL . '/api/index.php?endpoint=surah';
$response = @file_get_contents($apiUrl);
$surahs = $response ? json_decode($response, true) : null;

?>

<div class="text-center mb-8">
    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white">Daftar Surah Al-Qur'an</h1>
    <p class="text-slate-600 dark:text-slate-400 mt-2">Pilih surah untuk dibaca.</p>
</div>

<?php if ($surahs && $surahs['code'] == 200): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <?php foreach ($surahs['data'] as $surah): ?>
            <a href="surah.php?nomor=<?= $surah['nomor'] ?>" class="block p-5 bg-white dark:bg-slate-800 rounded-lg shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-slate-700 rounded-full font-bold text-teal-600 dark:text-teal-400">
                            <?= $surah['nomor'] ?>
                        </div>
                        <div>
                            <p class="font-semibold text-lg text-slate-800 dark:text-slate-100"><?= htmlspecialchars($surah['namaLatin']) ?></p>
                            <p class="text-sm text-slate-500 dark:text-slate-400"><?= htmlspecialchars($surah['arti']) ?> (<?= $surah['jumlahAyat'] ?> ayat)</p>
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
    <div class="text-center p-8 bg-red-100 dark:bg-red-900/50 rounded-lg">
        <p class="text-red-600 dark:text-red-300">Gagal memuat daftar surah. Silakan coba lagi nanti.</p>
    </div>
<?php endif; ?>

<?php include 'layout/footer.php'; ?>