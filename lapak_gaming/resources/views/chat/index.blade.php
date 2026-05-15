
@extends('layouts.app')

@section('content')
<div class="flex h-[calc(100vh-80px)] bg-gray-100 overflow-hidden rounded-lg shadow-xl border border-gray-200 m-4 font-sans">
    <!-- Sidebar: Daftar Chat -->
    <div class="w-1/3 bg-white border-r flex flex-col">
        <div class="p-4 border-b bg-white flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Pesan</h2>
        </div>
        
        <div class="p-3 border-b">
            <input type="text" placeholder="Cari percakapan..." class="w-full px-4 py-2 bg-gray-100 border-none rounded-lg text-sm focus:ring-2 focus:ring-emerald-500">
        </div>

        <div id="conversation-list" class="flex-1 overflow-y-auto divide-y divide-gray-50">
            <!-- Render via JS -->
        </div>
    </div>

    <!-- Chat Window -->
    <div class="flex-1 flex flex-col bg-[#e5ddd5] relative">
        <!-- Welcome Screen -->
        <div id="chat-welcome" class="absolute inset-0 z-20 bg-[#f8f9fa] flex flex-col items-center justify-center text-center p-6">
            <div class="w-24 h-24 bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700">Lapak Gaming Chat</h3>
            <p class="text-gray-500 mt-2 max-w-xs">Kirim dan terima pesan secara realtime dengan aman.</p>
        </div>

        <!-- Chat Header -->
        <div id="chat-header" class="hidden p-3 bg-[#ededed] border-b flex items-center justify-between z-10 shadow-sm">
            <div class="flex items-center space-x-3">
                <img id="active-avatar" src="" class="w-10 h-10 rounded-full bg-gray-300">
                <div>
                    <h3 id="active-name" class="font-bold text-gray-800 text-sm"></h3>
                    <p id="active-status" class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Offline</p>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messages-container" class="hidden flex-1 overflow-y-auto p-4 flex flex-col-reverse space-y-reverse space-y-2">
            <!-- Messages injected here -->
        </div>

        <!-- Input Area -->
        <div id="chat-footer" class="hidden p-3 bg-[#f0f0f0] border-t">
            <form id="chat-form" class="flex items-center space-x-2">
                <input type="text" id="message-input" autocomplete="off" placeholder="Ketik pesan..." 
                       class="flex-1 border-none rounded-lg px-4 py-2.5 focus:ring-0 text-sm shadow-sm">
                <button type="submit" class="bg-emerald-600 text-white p-2.5 rounded-full hover:bg-emerald-700 transition shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentConversationId = null;
    let authId = {{ auth()->id() }};
    const listEl = document.getElementById('conversation-list');
    const msgContainer = document.getElementById('messages-container');
    const chatForm = document.getElementById('chat-form');
    const msgInput = document.getElementById('message-input');

    // 1. Ambil daftar percakapan
    async function loadConversations() {
        const res = await fetch('/chat/conversations');
        const data = await res.json();
        listEl.innerHTML = data.map(c => `
            <div onclick="openChat(${c.id}, '${c.partner_name}', '${c.partner_avatar}')" 
                 class="p-4 hover:bg-gray-50 cursor-pointer transition flex items-center space-x-3 ${currentConversationId === c.id ? 'bg-emerald-50' : ''}">
                <img src="${c.partner_avatar || 'https://ui-avatars.com/api/?name='+c.partner_name}" class="w-12 h-12 rounded-full border">
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-800 truncate text-sm">${c.partner_name}</span>
                        <span class="text-[10px] text-gray-400">${c.last_message_time || ''}</span>
                    </div>
                    <p class="text-xs text-gray-500 truncate">${c.last_message || 'Belum ada pesan'}</p>
                </div>
                ${c.unread_count > 0 ? `<span class="bg-emerald-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">${c.unread_count}</span>` : ''}
            </div>
        `).join('');
    }

    // 2. Buka Chat
    window.openChat = async function(id, name, avatar) {
        currentConversationId = id;
        document.getElementById('chat-welcome').classList.add('hidden');
        document.getElementById('chat-header').classList.remove('hidden');
        document.getElementById('messages-container').classList.remove('hidden');
        document.getElementById('chat-footer').classList.remove('hidden');
        
        document.getElementById('active-name').innerText = name;
        document.getElementById('active-avatar').src = avatar || 'https://ui-avatars.com/api/?name='+name;
        
        msgContainer.innerHTML = '<div class="text-center p-4 text-xs text-gray-400">Memuat pesan...</div>';
        
        const res = await fetch(\`/chat/conversations/\${id}/messages\`);
        const data = await res.json();
        renderMessages(data.messages);
        
        // Listen Realtime via Laravel Echo
        window.Echo.private(\`chat.\${id}\`)
            .listen('MessageSent', (e) => {
                if(currentConversationId === id) {
                    appendMessage(e, false);
                }
            });
    };

    function renderMessages(messages) {
        msgContainer.innerHTML = messages.map(m => messageHtml(m)).join('');
    }

    function appendMessage(m, isMine) {
        const div = document.createElement('div');
        div.innerHTML = messageHtml(m);
        msgContainer.prepend(div.firstElementChild);
    }

    function messageHtml(m) {
        const isMine = m.sender_id === authId;
        return `
            <div class="flex ${isMine ? 'justify-end' : 'justify-start'} mb-1">
                <div class="max-w-[75%] rounded-lg px-3 py-1.5 shadow-sm relative ${isMine ? 'bg-[#dcf8c6] text-gray-800' : 'bg-white text-gray-800'}">
                    <p class="text-sm">${m.message}</p>
                    <div class="flex items-center justify-end space-x-1 mt-0.5">
                        <span class="text-[9px] text-gray-500">${m.time}</span>
                        ${isMine ? \`<span class="text-blue-500">\${m.is_read ? '✓✓' : '✓'}</span>\` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // 3. Kirim Pesan
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = msgInput.value.trim();
        if(!msg || !currentConversationId) return;

        msgInput.value = '';
        const res = await fetch('/chat/messages', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                conversation_id: currentConversationId,
                message: msg
            })
        });
        const newMsg = await res.json();
        appendMessage(newMsg, true);
    });

    loadConversations();
});
</script>

<style>
    /* WhatsApp style scrollbar */
    #messages-container::-webkit-scrollbar { width: 6px; }
    #messages-container::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    #conversation-list::-webkit-scrollbar { width: 5px; }
    #conversation-list::-webkit-scrollbar-thumb { background: #ccc; }
</style>
@endsection
