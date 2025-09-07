</main>

    <button id="chat-toggler" class="fixed bottom-4 right-4 md:bottom-8 md:right-8 z-50 flex h-14 w-14 md:h-16 md:w-16 items-center justify-center rounded-full bg-teal-600 text-white shadow-lg transition-transform duration-300 hover:scale-110 hover:bg-teal-700">
        <svg id="chat-icon" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
        <svg id="close-icon" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 md:h-8 md:w-8 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
    </button>

    <div id="chat-popup" class="fixed z-40 hidden flex-col rounded-2xl border bg-white shadow-2xl transition-all duration-300 ease-out 
        dark:bg-slate-800 dark:border-slate-700
        bottom-20 right-4 w-[calc(100vw-2rem)] h-[70vh] max-h-[550px]
        md:bottom-28 md:right-8 md:w-96 md:h-[70vh] md:max-h-[600px]">
        
        <div class="flex flex-shrink-0 items-center justify-between rounded-t-2xl bg-teal-600 p-4 text-white shadow-md">
            <h3 class="text-lg font-semibold">Tanya Asisten AI</h3>
        </div>
        
        <div id="chat-box" class="flex-grow space-y-4 overflow-y-auto p-4">
            <div class="flex justify-start">
                <div class="max-w-xs rounded-2xl rounded-bl-none bg-gray-200 p-3 text-gray-800 lg:max-w-md dark:bg-slate-700 dark:text-slate-200">
                    <p>Assalamualaikum! Ada yang bisa dibantu?</p>
                </div>
            </div>
        </div>
        
        <div class="flex flex-shrink-0 items-center space-x-2 border-t border-gray-200 p-4 bg-white rounded-b-2xl dark:bg-slate-800 dark:border-slate-700">
            <input type="text" id="user-input" placeholder="Ketik pertanyaan Anda..." class="w-full rounded-full border-gray-300 px-4 py-2 focus:border-teal-500 focus:ring-2 focus:ring-teal-500 transition duration-200 dark:bg-slate-700 dark:border-slate-600 dark:text-white">
            <button id="send-btn" class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-teal-600 text-white transition-colors hover:bg-teal-700 disabled:bg-teal-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" /></svg>
            </button>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatToggler = document.getElementById('chat-toggler');
        const chatPopup = document.getElementById('chat-popup');
        const sendBtn = document.getElementById('send-btn');
        const userInput = document.getElementById('user-input');
        const chatBox = document.getElementById('chat-box');
        const chatIcon = document.getElementById('chat-icon');
        const closeIcon = document.getElementById('close-icon');

        // Tampilkan atau sembunyikan popup dan ganti ikon
        chatToggler.addEventListener('click', () => {
            chatPopup.classList.toggle('hidden');
            chatPopup.classList.toggle('flex');
            chatIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });

        const sendMessage = async () => {
            const messageText = userInput.value.trim();
            if (messageText === '') return;

            appendMessage('user', messageText);
            userInput.value = '';
            userInput.disabled = true;
            sendBtn.disabled = true;
            
            const typingIndicator = showTypingIndicator();

            try {
                // Mengirim request ke backend handler
                const response = await fetch('api/chatbot_handler.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ message: messageText })
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                
                const data = await response.json();
                // Menggunakan library Marked untuk memformat jawaban AI
                const formattedReply = marked.parse(data.reply || '');
                appendMessage('bot', formattedReply, true);
            } catch (error) {
                console.error("Fetch Error:", error);
                appendMessage('bot', 'Maaf, terjadi kesalahan. Silakan coba lagi nanti.');
            } finally {
                typingIndicator.remove();
                userInput.disabled = false;
                sendBtn.disabled = false;
                userInput.focus();
            }
        };

        const appendMessage = (sender, content, isHTML = false) => {
            const messageWrapper = document.createElement('div');
            messageWrapper.className = 'flex';
            const messageBubble = document.createElement('div');
            const messageContent = document.createElement('div');

            if (sender === 'user') {
                messageWrapper.classList.add('justify-end');
                messageBubble.className = 'rounded-2xl rounded-br-none bg-teal-600 p-3 text-white max-w-xs lg:max-w-md';
                messageContent.textContent = content; // Aman dari XSS
            } else {
                messageWrapper.classList.add('justify-start');
                // Kelas "prose" dari Tailwind untuk styling teks
                messageBubble.className = 'rounded-2xl rounded-bl-none bg-gray-200 p-3 text-gray-800 max-w-xs lg:max-w-md dark:bg-slate-700 dark:text-slate-200 prose prose-sm dark:prose-invert';
                if (isHTML) {
                    messageContent.innerHTML = content;
                } else {
                    messageContent.textContent = content;
                }
            }
            
            messageBubble.appendChild(messageContent);
            messageWrapper.appendChild(messageBubble);
            chatBox.appendChild(messageWrapper);
            chatBox.scrollTop = chatBox.scrollHeight;
            return messageWrapper;
        };
        
        // Menampilkan indikator "sedang mengetik..."
        const showTypingIndicator = () => {
             const indicatorHTML = `<div class="flex justify-start"><div class="rounded-2xl rounded-bl-none bg-gray-200 dark:bg-slate-700 p-3 text-gray-500"><div class="flex items-center space-x-1"><div class="h-2 w-2 animate-bounce rounded-full bg-gray-400 [animation-delay:-0.3s]"></div><div class="h-2 w-2 animate-bounce rounded-full bg-gray-400 [animation-delay:-0.15s]"></div><div class="h-2 w-2 animate-bounce rounded-full bg-gray-400"></div></div></div></div>`;
            chatBox.insertAdjacentHTML('beforeend', indicatorHTML);
            chatBox.scrollTop = chatBox.scrollHeight;
            return chatBox.lastElementChild;
        };

        sendBtn.addEventListener('click', sendMessage);
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
    });
    </script>
    
    <footer class="text-center py-6 text-sm text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800 mt-8">
        <p>Qur'an App &copy; <?= date('Y') ?>. Dibuat dengan PHP Native & Tailwind CSS.</p>
        <p>Data disediakan oleh <a href="https://equran.id" target="_blank" class="text-teal-600 hover:underline">equran.id</a></p>
    </footer>

    <script src="assets/js/main.js"></script>
    
</body>
</html>