# Quran_App
Sebuah aplikasi web yang simpel, cepat, dan modern untuk membaca Al-Qur'an, dibangun dengan PHP native dan Tailwind CSS. Proyek ini berfungsi sebagai antarmuka yang ramah pengguna untuk API `equran.id` dan dilengkapi dengan chatbot AI terintegrasi untuk menjawab pertanyaan seputar Islam, didukung oleh Together AI API.

## ✨ Tentang Proyek Ini

Aplikasi ini menyediakan platform yang bersih dan responsif untuk menjelajahi, membaca, dan mencari ayat-ayat suci Al-Qur'an. Proyek ini dirancang agar ringan dan mudah diinstal, berjalan di server PHP standar tanpa memerlukan database atau framework yang rumit. Antarmuka pengguna (UI) ditata dengan Tailwind CSS untuk tampilan yang modern, dan dilengkapi dengan fitur-fitur seperti mode gelap, pemutaran audio untuk setiap ayat, dan fungsi pencarian yang kuat.

Fitur utamanya adalah asisten AI pop-up yang dapat menjawab pertanyaan pengguna dalam Bahasa Indonesia, memberikan informasi yang relevan secara kontekstual, termasuk referensi Surah dan ayat jika memungkinkan.

### 🔑 Fitur Utama

* **Daftar Surah Lengkap**: Menampilkan semua 114 surah Al-Qur'an.
* **Tampilan Detail Surah**: Membaca surah dengan teks Arab, transliterasi Latin, dan terjemahan Bahasa Indonesia.
* **Pemutar Audio**: Mendengarkan lantunan (qira'at) setiap ayat oleh Misyari Rasyid Al-Afasy.
* **Pencarian Canggih**: Mencari berdasarkan nama surah atau kata kunci dalam terjemahan Bahasa Indonesia. Pencarian memprioritaskan kecocokan nama surah terlebih dahulu.
* **Asisten Chatbot AI**: Chatbot pop-up terintegrasi untuk menjawab pertanyaan seputar Islam dan Al-Qur'an.
* **Mode Gelap (Dark Mode)**: Tampilan gelap yang elegan dan nyaman di mata, yang dapat diaktifkan secara manual atau mengikuti preferensi sistem.
* **Sepenuhnya Responsif**: Tampilan yang beradaptasi dengan indah di semua ukuran layar, dari ponsel hingga desktop.
* **Ringan & Cepat**: Dibangun dengan PHP native, membuatnya cepat dan mudah di-deploy di shared hosting atau server mana pun yang mendukung PHP.

### 🚀 Teknologi yang Digunakan

* **Backend**: PHP Native
* **Frontend**: HTML, Tailwind CSS (via CDN), Vanilla JavaScript
* **API**:
    * [API equran.id](https://equran.id) untuk data Al-Qur'an (surah, ayat, audio).
    * [API Together AI](https://together.ai) untuk fungsionalitas chatbot AI (menggunakan model `meta-llama/Llama-3-8b-chat-hf`).
* **Library**:
    * [Marked.js](https://marked.js.org/) untuk merender respons Markdown dari AI.

## ⚙️ Panduan Memulai

Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah sederhana berikut.

### Prasyarat

Anda memerlukan web server lokal atau remote yang mendukung PHP (misalnya, Apache, Nginx dengan PHP-FPM). Aplikasi seperti XAMPP atau MAMP akan bekerja dengan sempurna untuk pengembangan lokal.

### Instalasi

1.  **Unduh Proyek**
    Lakukan `git clone` repositori ini atau unduh kode sumbernya dan letakkan di direktori root web server Anda (misalnya, `htdocs/` untuk XAMPP).

2.  **Konfigurasi Kunci API (API Key) Chatbot**
    Ini adalah langkah **paling penting** untuk mengaktifkan chatbot AI.

    * Buka file: `api/api/chatbot_handler.php`.
    * Temukan baris berikut:
        ```php
        $apiKey = 'GANTI_DENGAN_API_KEY_ANDA';
        ```
    * Ganti `'GANTI_DENGAN_API_KEY_ANDA'` dengan kunci API Anda yang sebenarnya dari [Together AI](https://api.together.ai/).

    ⚠️ **Penting**: Chatbot AI tidak akan berfungsi tanpa kunci API yang valid. Jika kunci tidak diatur, chatbot akan mengembalikan pesan error.

3.  **Jalankan Aplikasi**
    Nyalakan web server Anda dan buka URL proyek di browser (misalnya, `http://localhost/nama-folder-proyek/`).
