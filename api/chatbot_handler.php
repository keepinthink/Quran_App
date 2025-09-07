<?php
// FILE INI HANYA BERTUGAS MENANGANI LOGIKA BACKEND CHATBOT

header('Content-Type: application/json');

// Cek untuk memastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Metode request tidak valid.']);
    exit;
}

// Mengambil input JSON dari frontend
$json_input = file_get_contents('php://input');
$input_data = json_decode($json_input, true);

// Validasi input
if (!isset($input_data['message']) || empty(trim($input_data['message']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Pesan tidak boleh kosong.']);
    exit;
}

$userInput = trim($input_data['message']);

// Fungsi untuk berkomunikasi dengan API AI
function getAIResponse($input) {
 
   // !!! PENTING: Ganti dengan API Key Anda di sini !!!
    $apiKey = 'GANTI_DENGAN_API_KEY_ANDA';

    if ($apiKey === 'GANTI_DENGAN_API_KEY_ANDA') {
        return "Error: API Key belum diatur di file api/chatbot_handler.php.";
    }

    $url = 'https://api.together.xyz/v1/chat/completions';
    $data = [
        'model' => 'meta-llama/Llama-3-8b-chat-hf', 
        'max_tokens' => 1024,
      
   'messages' => [
            ['role' => 'system', 'content' => 'Kamu adalah asisten AI Islami berbahasa Indonesia, bernama MaktabahIslamAI. Jawab semua pertanyaan dengan sopan, informatif, dan ramah. Jika ada hubungannya dengan Al-Qur\'an, sebutkan nama surah dan nomor ayatnya. Format jawabanmu menggunakan markdown sederhana jika perlu (misal: **bold**).'],
       
     ['role' => 'user', 'content' => $input]
        ]
    ];

    // Persiapan dan eksekusi cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 40);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Mengembalikan hasil
    if ($error) {
        return "Error cURL: " . $error;
    }
    
    if ($httpcode == 200) {
        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? 'Tidak menerima jawaban yang valid.';
    } else {
        return "Error: Gagal menghubungi AI. Status: " . $httpcode;
    }
}

// Mengirim balasan dalam format JSON
echo json_encode(['reply' => getAIResponse($userInput)]);
exit;