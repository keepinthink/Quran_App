document.addEventListener('DOMContentLoaded', () => {
    // === DARK MODE LOGIC ===
    const darkModeToggle = document.getElementById('darkModeToggle');
    const moonIcon = document.getElementById('moonIcon');
    const sunIcon = document.getElementById('sunIcon');
    const html = document.documentElement;

    // Cek tema dari localStorage saat halaman dimuat
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        moonIcon.classList.add('hidden');
        sunIcon.classList.remove('hidden');
    } else {
        html.classList.remove('dark');
        moonIcon.classList.remove('hidden');
        sunIcon.classList.add('hidden');
    }

    // Tambahkan event listener ke tombol toggle
    darkModeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        moonIcon.classList.toggle('hidden');
        sunIcon.classList.toggle('hidden');

        // Simpan preferensi tema ke localStorage
        if (html.classList.contains('dark')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });

    // === AUDIO PLAYER LOGIC ===
    // Fungsi untuk memastikan hanya satu audio yang diputar pada satu waktu
    let currentAudio = null;

    document.body.addEventListener('click', function(event) {
        // Cek apakah yang diklik adalah tombol play audio
        if (event.target.matches('.play-audio-btn')) {
            const audioId = event.target.getAttribute('data-audio-id');
            const audioPlayer = document.getElementById(audioId);
            const playIcon = event.target.querySelector('.play-icon');
            const pauseIcon = event.target.querySelector('.pause-icon');

            if (audioPlayer) {
                // Jika ada audio lain yang sedang diputar, jeda audio tersebut
                if (currentAudio && currentAudio !== audioPlayer) {
                    currentAudio.pause();
                }

                if (audioPlayer.paused) {
                    audioPlayer.play();
                    currentAudio = audioPlayer;
                } else {
                    audioPlayer.pause();
                }

                // Event listener untuk handle state audio player
                audioPlayer.onplay = () => {
                    // Reset semua ikon tombol lain
                    document.querySelectorAll('.play-audio-btn').forEach(btn => {
                        btn.querySelector('.play-icon').classList.remove('hidden');
                        btn.querySelector('.pause-icon').classList.add('hidden');
                    });
                    // Tampilkan ikon pause pada tombol yang aktif
                    playIcon.classList.add('hidden');
                    pauseIcon.classList.remove('hidden');
                };

                audioPlayer.onpause = () => {
                    playIcon.classList.remove('hidden');
                    pauseIcon.classList.add('hidden');
                    if (currentAudio === audioPlayer) {
                        currentAudio = null;
                    }
                };
                 audioPlayer.onended = () => {
                    playIcon.classList.remove('hidden');
                    pauseIcon.classList.add('hidden');
                    currentAudio = null;
                };
            }
        }
    });
});