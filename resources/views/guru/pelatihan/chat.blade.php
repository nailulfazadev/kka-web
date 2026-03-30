@extends('layouts.app')
@section('title', ($training->is_ecourse ? 'Asisten AI Pribadi' : 'Forum Diskusi') . ' - ' . $training->title)
@section('content')

<section class="absolute inset-x-0 flex flex-col" style="top: 60px; bottom: 80px;">

    {{-- HEADER --}}
    <div class="px-4 pt-3 pb-2 shrink-0">
        <a href="{{ route('guru.pelatihan.show', $training->registrations()->where('user_id', auth()->id())->first()->id ?? 0) }}"
            class="inline-flex items-center gap-1 text-on-surface-variant text-sm mb-2">
            <i class="fa-solid fa-arrow-left text-sm"></i> {{ $training->title }}
        </a>
        <h2 class="text-base font-bold text-on-surface">
            @if($training->is_ecourse) 🤖 Asisten AI Pribadi @else 💬 Forum Diskusi @endif
        </h2>
    </div>

    {{-- MESSAGES AREA (scrollable) --}}
    <div id="chat-messages" class="flex-1 overflow-y-auto min-h-0 px-4 pb-3 space-y-3 hide-scrollbar">
        {{-- Messages will be rendered by JS --}}
    </div>

    {{-- REPLY INDICATOR --}}
    <div id="reply-indicator" class="hidden shrink-0 mx-4 mb-1 px-3 py-2 bg-surface-container-highest rounded-xl flex items-center justify-between border border-primary/20">
        <div class="text-xs text-on-surface-variant">
            Membalas <span id="reply-name" class="font-bold text-primary"></span>
        </div>
        <button type="button" onclick="cancelReply()" class="text-xs text-error hover:opacity-80 p-1">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    {{-- INPUT FOOTER (sticky di atas bottom nav) --}}
    <div class="shrink-0 px-4 py-2 border-t border-white/5">
        <form id="chat-form" class="flex gap-2 w-full items-center">
            <input type="hidden" id="reply_to_id" name="reply_to_id" value="">

            @if($training->is_ecourse)
            {{--<button type="button" onclick="insertTag()"
                class="shrink-0 px-2 h-10 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl text-[10px] font-bold text-indigo-400 hover:bg-indigo-500/20 transition-colors flex items-center gap-1"
                title="Tanya AI KKA">
                <i class="fa-solid fa-robot"></i> @kka
            </button>--}}
            @endif

            <input id="chat-input" name="message"
                class="flex-1 min-w-0 bg-surface-container-highest border-none rounded-2xl py-2.5 px-4 text-on-surface placeholder:text-outline focus:ring-1 focus:ring-primary/40 text-sm"
                placeholder="Tulis pesan..." required autocomplete="off">

            <button type="submit" id="chat-send"
                class="w-10 h-10 shrink-0 rounded-full bg-gradient-to-r from-primary-dim to-primary-container flex items-center justify-center active:scale-90 transition-transform">
                <i class="fa-solid fa-paper-plane text-on-primary-container"></i>
            </button>
        </form>
    </div>

</section>

@push('scripts')
<script>
(function () {
    const container = document.getElementById('chat-messages');
    const form      = document.getElementById('chat-form');
    const input     = document.getElementById('chat-input');
    const sendBtn   = document.getElementById('chat-send');

    const currentUserId = {{ auth()->id() }};
    const csrfToken     = '{{ csrf_token() }}';
    const fetchUrl      = '{{ route("guru.pelatihan.chat.fetch", $training->id) }}';
    const storeUrl      = '{{ route("guru.pelatihan.chat.store", $training->id) }}';

    let lastMessageId = 0;
    let isPolling     = true;

    const userColors = [
        { text: 'text-red-400',     bg: 'bg-red-500/10' },
        { text: 'text-orange-400',  bg: 'bg-orange-500/10' },
        { text: 'text-amber-400',   bg: 'bg-amber-500/10' },
        { text: 'text-green-400',   bg: 'bg-green-500/10' },
        { text: 'text-emerald-400', bg: 'bg-emerald-500/10' },
        { text: 'text-teal-400',    bg: 'bg-teal-500/10' },
        { text: 'text-cyan-400',    bg: 'bg-cyan-500/10' },
        { text: 'text-blue-400',    bg: 'bg-blue-500/10' },
        { text: 'text-indigo-400',  bg: 'bg-indigo-500/10' },
        { text: 'text-violet-400',  bg: 'bg-violet-500/10' },
        { text: 'text-purple-400',  bg: 'bg-purple-500/10' },
        { text: 'text-fuchsia-400', bg: 'bg-fuchsia-500/10' },
        { text: 'text-pink-400',    bg: 'bg-pink-500/10' },
    ];

    function getUserColor(userId) {
        if (!userId) return { text: 'text-primary', bg: 'bg-primary/10' };
        return userColors[userId % userColors.length];
    }

    window.replyTo = function (msgId, userName) {
        document.getElementById('reply_to_id').value = msgId;
        document.getElementById('reply-name').textContent = userName;
        document.getElementById('reply-indicator').classList.remove('hidden');
        input.focus();
    };

    window.cancelReply = function () {
        document.getElementById('reply_to_id').value = '';
        document.getElementById('reply-indicator').classList.add('hidden');
    };

    window.insertTag = function () {
        input.value = input.value ? input.value + ' @kka ' : '@kka ';
        input.focus();
    };

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    function timeAgo(dateStr) {
        const now     = new Date();
        const date    = new Date(dateStr);
        const diffSec = Math.floor((now - date) / 1000);
        if (diffSec < 10)  return 'baru saja';
        if (diffSec < 60)  return diffSec + ' detik lalu';
        const diffMin  = Math.floor(diffSec / 60);
        if (diffMin < 60)  return diffMin + ' menit lalu';
        const diffHour = Math.floor(diffMin / 60);
        if (diffHour < 24) return diffHour + ' jam lalu';
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
    }

    function renderMessage(msg) {
        const isMe  = msg.user_id === currentUserId;
        const isBot = msg.is_bot;

        const initial = isBot
            ? '<i class="fa-solid fa-robot text-[10px]"></i>'
            : (msg.user ? msg.user.name.charAt(0).toUpperCase() : '?');

        const name = isBot
            ? 'Asisten AI KKA'
            : (msg.user ? msg.user.name : 'Unknown');

        const time = timeAgo(msg.created_at);

        let color       = isMe ? { text: 'text-primary', bg: 'bg-primary/10' } : getUserColor(msg.user_id);
        let bubbleClass = isMe ? 'glass-card bg-primary/10' : 'glass-card';

        if (isBot) {
            color       = { text: 'text-indigo-300', bg: 'bg-indigo-500/20' };
            bubbleClass = 'glass-card bg-indigo-500/10 border-indigo-500/30';
        }

        let parentHtml = '';
        if (msg.parent) {
            const parentName = msg.parent.is_bot
                ? 'Asisten AI KKA'
                : (msg.parent.user ? msg.parent.user.name : 'Unknown');
            const pMsgText = escapeHtml(msg.parent.message)
                .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" class="text-blue-400 hover:underline">$1</a>');
            parentHtml = `
                <div class="mb-2 p-2 rounded bg-black/20 border-l-2 border-primary/50 text-xs text-on-surface-variant line-clamp-2">
                    <span class="font-bold text-primary/80">${escapeHtml(parentName)}</span><br>${pMsgText}
                </div>`;
        }

        const msgText = escapeHtml(msg.message)
            .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" class="text-blue-400 hover:underline">$1</a>');

        return `
        <div class="flex gap-2 ${isMe ? 'flex-row-reverse' : ''} chat-msg" data-id="${msg.id}">
            <div class="w-8 h-8 rounded-full ${color.bg} flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold ${color.text}">${initial}</span>
            </div>
            <div class="max-w-[78%] ${bubbleClass} rounded-2xl px-3 py-2 border border-white/5">
                <p class="text-xs font-semibold ${isBot ? 'text-indigo-400' : color.text} mb-1 flex items-center gap-1">
                    ${escapeHtml(name)}${isBot ? ' <i class="fa-solid fa-circle-check text-[9px]"></i>' : ''}
                </p>
                ${parentHtml}
                <p class="text-[13px] text-on-surface leading-snug whitespace-pre-wrap">${msgText}</p>
                <div class="flex items-center justify-between gap-4 mt-1 pt-1 border-t border-white/5">
                    <p class="text-[9px] text-outline">${time}</p>
                    <button type="button"
                        onclick="replyTo(${msg.id}, '${escapeHtml(name).replace(/'/g, "\\'")}')"
                        class="text-[10px] uppercase tracking-wider font-bold text-primary/60 hover:text-primary transition-colors">
                        Balas
                    </button>
                </div>
            </div>
        </div>`;
    }

    function scrollToBottom() {
        container.scrollTop = container.scrollHeight;
    }

    // Load initial messages
    const initialMessages = @json($messages->items());
    initialMessages.forEach(msg => {
        container.insertAdjacentHTML('beforeend', renderMessage(msg));
        if (msg.id > lastMessageId) lastMessageId = msg.id;
    });
    scrollToBottom();

    // Polling tiap 2 detik
    async function pollMessages() {
        if (!isPolling) return;
        try {
            const resp = await fetch(fetchUrl + '?after=' + lastMessageId, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            if (resp.ok) {
                const msgs = await resp.json();
                if (msgs.length > 0) {
                    const wasAtBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 80;
                    msgs.forEach(msg => {
                        if (!document.querySelector(`.chat-msg[data-id="${msg.id}"]`)) {
                            container.insertAdjacentHTML('beforeend', renderMessage(msg));
                        }
                        if (msg.id > lastMessageId) lastMessageId = msg.id;
                    });
                    if (wasAtBottom) scrollToBottom();
                }
            }
        } catch (e) { /* retry diam-diam */ }
        setTimeout(pollMessages, 2000);
    }
    setTimeout(pollMessages, 2000);

    // Submit pesan
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        const replyId    = document.getElementById('reply_to_id').value;
        const isBotQuery = message.toLowerCase().includes('@kka');

        input.value      = '';
        sendBtn.disabled = true;

        if (isBotQuery) {
            sendBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-on-primary-container"></i>';
        }

        // Optimistic UI
        const tempMsg = {
            id:         'temp-' + Date.now(),
            user_id:    currentUserId,
            message:    message,
            created_at: new Date().toISOString(),
            is_bot:     false,
            user:       { name: '{{ auth()->user()->name }}' },
            parent:     null,
        };
        container.insertAdjacentHTML('beforeend', renderMessage(tempMsg));
        scrollToBottom();

        try {
            const resp = await fetch(storeUrl, {
                method:  'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ message: message, reply_to_id: replyId || null }),
            });
            if (resp.ok) {
                const tempEl = document.querySelector(`.chat-msg[data-id="${tempMsg.id}"]`);
                if (tempEl) tempEl.remove();
                cancelReply();
            }
        } catch (e) {
            console.error('Gagal kirim:', e);
        }

        sendBtn.disabled  = false;
        sendBtn.innerHTML = '<i class="fa-solid fa-paper-plane text-on-primary-container"></i>';
        input.focus();
    });

    // Stop polling saat tab tidak aktif
    document.addEventListener('visibilitychange', () => {
        isPolling = !document.hidden;
        if (isPolling) pollMessages();
    });
})();
</script>
@endpush
@endsection