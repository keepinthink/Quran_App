<!DOCTYPE html>
<html lang="id" class=""> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Al-Qur'an</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Menambahkan font family kustom */
        .font-arabic { font-family: 'Amiri', serif; }
        .font-body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 font-body text-slate-800 dark:text-slate-200 transition-colors duration-300">

    <header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="text-xl md:text-2xl font-bold text-teal-600 dark:text-teal-400">
                📖 Qur'an App
            </a>
            <div class="flex items-center space-x-4">
                <form action="search.php" method="GET" class="hidden md:block">
                    <input type="text" name="q" placeholder="Cari surah/terjemahan..." class="w-40 lg:w-64 px-3 py-1.5 bg-gray-100 dark:bg-slate-800 border border-transparent focus:outline-none focus:ring-2 focus:ring-teal-500 rounded-lg text-sm">
                </form>
                <button id="darkModeToggle" class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-slate-700">
                    <svg id="moonIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    <svg id="sunIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </button>
            </div>
        </div>
        <div class="container mx-auto px-4 pb-3 md:hidden">
             <form action="search.php" method="GET">
                <input type="text" name="q" placeholder="Cari surah atau terjemahan..." class="w-full px-3 py-1.5 bg-gray-100 dark:bg-slate-800 border border-transparent focus:outline-none focus:ring-2 focus:ring-teal-500 rounded-lg text-sm">
            </form>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">