@extends('layouts.app')
@section('title', 'Forum Diskusi - ' . $training->title)
@section('content')
<section class="px-6 pt-6 flex flex-col" style="height: calc(100vh - 160px);">
    <a href="{{ route('guru.pelatihan.show', $training->registrations()->where('user_id', auth()->id())->first()->id ?? 0) }}" class="inline-flex items-center gap-1 text-on-surface-variant text-sm mb-3"><i class="fa-solid fa-arrow-left text-sm"></i> {{ $training->title }}</a>
    <h2 class="text-lg font-bold text-on-surface mb-4">💬 Forum Diskusi</h2>
    <div id="chat-messages" class="flex-1 overflow-y-auto space-y-3 mb-4 hide-scrollbar">
        {{-- Messages will be rendered by JS --}}
    </div>
    <form id="chat-form" class="flex gap-3">
        <input id="chat-input" name="message" class="flex-1 bg-surface-container-highest border-none rounded-2xl py-3 px-4 text-on-surface placeholder:text-outline focus:ring-1 focus:ring-primary/40 text-sm" placeholder="Tulis pesan..." required autocomplete="off">
        <button type="submit" id="chat-send" class="w-12 h-12 rounded-full bg-gradient-to-r from-primary-dim to-primary-container flex items-center justify-center active:scale-90 transition-transform">
            <i class="fa-solid fa-paper-plane text-on-primary-container"></i>
        </button>
    </form>
</section>
@push('scripts')
<script>
(function() {
    const container = document.getElementById('chat-messages');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const sendBtn = document.getElementById('chat-send');
    const trainingId = {{ $training->id }};
    const currentUserId = {{ auth()->id() }};
    const csrfToken = '{{ csrf_token() }}';
    const fetchUrl = '{{ route("guru.pelatihan.chat.fetch", $training->id) }}';
    const storeUrl = '{{ route("guru.pelatihan.chat.store", $training->id) }}';
    let lastMessageId = 0;
    let isPolling = true;

    const userColors = [
        { text: 'text-red-400', bg: 'bg-red-500/10' },
        { text: 'text-orange-400', bg: 'bg-orange-500/10' },
        { text: 'text-amber-400', bg: 'bg-amber-500/10' },
        { text: 'text-green-400', bg: 'bg-green-500/10' },
        { text: 'text-emerald-400', bg: 'bg-emerald-500/10' },
        { text: 'text-teal-400', bg: 'bg-teal-500/10' },
        { text: 'text-cyan-400', bg: 'bg-cyan-500/10' },
        { text: 'text-blue-400', bg: 'bg-blue-500/10' },
        { text: 'text-indigo-400', bg: 'bg-indigo-500/10' },
        { text: 'text-violet-400', bg: 'bg-violet-500/10' },
        { text: 'text-purple-400', bg: 'bg-purple-500/10' },
        { text: 'text-fuchsia-400', bg: 'bg-fuchsia-500/10' },
        { text: 'text-pink-400', bg: 'bg-pink-500/10' }
    ];

    function getUserColor(userId) {
        if (!userId) return { text: 'text-primary', bg: 'bg-primary/10' };
        return userColors[userId % userColors.length];
    }

    function renderMessage(msg) {
        const isMe = msg.user_id === currentUserId;
        const initial = msg.user ? msg.user.name.charAt(0) : '?';
        const name = msg.user ? msg.user.name : 'Unknown';
        const time = timeAgo(msg.created_at);
        const color = isMe ? { text: 'text-primary', bg: 'bg-primary/10' } : getUserColor(msg.user_id);

        return `
        <div class="flex gap-3 ${isMe ? 'flex-row-reverse' : ''} chat-msg" data-id="${msg.id}">
            <div class="w-8 h-8 rounded-full ${color.bg} flex items-center justify-center flex-shrink-0">
                <span class="text-xs font-bold ${color.text}">${initial}</span>
            </div>
            <div class="max-w-[70%] ${isMe ? 'glass-card bg-primary/10' : 'glass-card'} rounded-2xl p-3 border border-white/5">
                <p class="text-xs font-semibold ${color.text} mb-1">${escapeHtml(name)}</p>
                <p class="text-sm text-on-surface">${escapeHtml(msg.message)}</p>
                <p class="text-[10px] text-outline mt-1">${time}</p>
            </div>
        </div>`;
    }

    function escapeHtml(text) {
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    function timeAgo(dateStr) {
        const now = new Date();
        const date = new Date(dateStr);
        const diffMs = now - date;
        const diffSec = Math.floor(diffMs / 1000);
        if (diffSec < 10) return 'baru saja';
        if (diffSec < 60) return diffSec + ' detik lalu';
        const diffMin = Math.floor(diffSec / 60);
        if (diffMin < 60) return diffMin + ' menit lalu';
        const diffHour = Math.floor(diffMin / 60);
        if (diffHour < 24) return diffHour + ' jam lalu';
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
    }

    function scrollToBottom() {
        container.scrollTop = container.scrollHeight;
    }

    // Load initial messages from server-rendered data
    const initialMessages = @json($messages->items());
    initialMessages.forEach(msg => {
        container.insertAdjacentHTML('beforeend', renderMessage(msg));
        if (msg.id > lastMessageId) lastMessageId = msg.id;
    });
    scrollToBottom();

    // Poll for new messages every 2 seconds
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
                        // Avoid duplicates
                        if (!document.querySelector(`.chat-msg[data-id="${msg.id}"]`)) {
                            container.insertAdjacentHTML('beforeend', renderMessage(msg));
                        }
                        if (msg.id > lastMessageId) lastMessageId = msg.id;
                    });
                    if (wasAtBottom) scrollToBottom();
                }
            }
        } catch(e) { /* silently retry */ }
        setTimeout(pollMessages, 2000);
    }
    setTimeout(pollMessages, 2000);

    // Send message via AJAX (no page reload)
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        input.value = '';
        sendBtn.disabled = true;

        // Optimistic UI: show message immediately
        const tempMsg = {
            id: 'temp-' + Date.now(),
            user_id: currentUserId,
            message: message,
            created_at: new Date().toISOString(),
            user: { name: '{{ auth()->user()->name }}' }
        };
        container.insertAdjacentHTML('beforeend', renderMessage(tempMsg));
        scrollToBottom();

        try {
            const resp = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message })
            });
            // Remove temp message — real one will come via polling
            if (resp.ok) {
                const tempEl = document.querySelector(`.chat-msg[data-id="${tempMsg.id}"]`);
                if (tempEl) tempEl.remove();
            }
        } catch(e) {
            console.error('Send failed', e);
        }
        sendBtn.disabled = false;
        input.focus();
    });

    // Pause polling when tab is hidden
    document.addEventListener('visibilitychange', () => {
        isPolling = !document.hidden;
        if (isPolling) pollMessages();
    });
})();
</script>
@endpush
@endsection
