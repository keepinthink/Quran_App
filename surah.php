<?php
include 'layout/header.php';

$nomorSurah = isset($_GET['nomor']) ? (int)$_GET['nomor'] : 0;

if ($nomorSurah < 1 || $nomorSurah > 114) {
    echo '<p class="text-center text-red-500">Nomor surah tidak valid.</p>';
    include 'layout/footer.php';
    exit;
}

// Fungsi untuk mendapatkan base URL dinamis
function get_base_url() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    return rtrim($protocol . $host . $script, '/');
}

define('BASE_URL', get_base_url());

$apiUrl = BASE_URL . "/api/index.php?endpoint=surah&id=$nomorSurah";
$response = @file_get_contents($apiUrl);
$detail = $response ? json_decode($response, true) : null;
$surah = $detail['data'] ?? null;
?>

<?php if ($surah): ?>
    <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-lg mb-8">
        <div class="text-center">
            <h1 class="font-arabic text-4xl md:text-5xl text-slate-900 dark:text-white mb-2"><?= htmlspecialchars($surah['nama']) ?></h1>
            <h2 class="text-2xl md:text-3xl font-bold text-teal-600 dark:text-teal-400"><?= htmlspecialchars($surah['namaLatin']) ?></h2>
            <p class="text-slate-600 dark:text-slate-400 mt-2 text-lg">"<?= htmlspecialchars($surah['arti']) ?>"</p>
            <div class="mt-4 text-sm text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                <span><?= htmlspecialchars($surah['tempatTurun']) ?></span> &bull;
                <span><?= $surah['jumlahAyat'] ?> AYAT</span>
            </div>
        </div>
    </div>
    
    <div class="space-y-12">
        <?php foreach ($surah['ayat'] as $ayat): ?>
            <div id="ayat-<?= $ayat['nomorAyat'] ?>" class="py-6 border-b border-gray-200 dark:border-slate-700">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-bold text-teal-600 dark:text-teal-400"><?= $surah['nomor'] ?>:<?= $ayat['nomorAyat'] ?></span>
                    <div class="flex items-center space-x-2">
                        <button class="play-audio-btn p-2 rounded-full hover:bg-gray-200 dark:hover:bg-slate-700" data-audio-id="audio-<?= $ayat['nomorAyat'] ?>">
                            <svg class="w-5 h-5 play-icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                            <svg class="w-5 h-5 pause-icon hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </button>
                        <audio id="audio-<?= $ayat['nomorAyat'] ?>" src="<?= htmlspecialchars($ayat['audio']['05']) ?>" preload="none"></audio>
                    </div>
                </div>

                <p class="font-arabic text-3xl md:text-4xl text-right leading-loose mb-6 text-slate-900 dark:text-white"><?= $ayat['teksArab'] ?></p>
                <p class="text-md italic text-teal-700 dark:text-teal-300 mb-2 leading-relaxed">[ <?= $ayat['teksLatin'] ?> ]</p>
                <p class="text-md text-slate-700 dark:text-slate-300 leading-relaxed"><?= $ayat['teksIndonesia'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>

<?php else: ?>
     <div class="text-center p-8 bg-red-100 dark:bg-red-900/50 rounded-lg">
        <p class="text-red-600 dark:text-red-300">Gagal memuat detail surah. Surah tidak ditemukan atau terjadi kesalahan.</p>
        <a href="index.php" class="mt-4 inline-block px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700">Kembali ke Daftar Surah</a>
    </div>
<?php endif; ?>

<?php include 'layout/footer.php'; ?>