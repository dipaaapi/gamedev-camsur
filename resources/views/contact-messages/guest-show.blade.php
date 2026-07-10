<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="Message Thread - GameDev CamSur" />
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden bg-slate-950 text-slate-100">
    <x-loading-screen />
    


    <!-- Navigation Bar -->
    <x-guest-nav activePage="contact" />

    <main class="flex-grow py-20 px-6">
        <div class="max-w-4xl mx-auto space-y-8">
            
            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-white tracking-tight sm:text-4xl">
                    Inquiry <span class="gradient-text">Message Thread</span>
                </h1>
                <p class="text-slate-400 mt-2 text-sm">
                    Inquiry Reference #{{ $contactMessage->id }} • Purpose: <span class="text-indigo-400 font-semibold">{{ strtok($contactMessage->purpose, ':') }}</span>
                </p>
            </div>

            <!-- Session alerts -->
            @if (session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-semibold text-center">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Thread Not Available / Closed State -->
            @if(isset($error) || $contactMessage->status === 'Resolved' || $contactMessage->status === 'Archived')
                <div class="bg-slate-900/50 border border-slate-800 rounded-3xl p-10 text-center shadow-2xl backdrop-blur-sm max-w-xl mx-auto space-y-6">
                    <div class="w-16 h-16 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-full flex items-center justify-center mx-auto text-2xl">
                        🔒
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-xl font-bold text-white">Thread No Longer Available</h2>
                        <p class="text-slate-400 text-sm">
                            This messaging thread is closed and no longer available because the inquiry has been marked as resolved or done.
                        </p>
                    </div>
                    <div class="pt-4">
                        <a href="{{ url('/#contact') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all shadow-lg hover:shadow-indigo-500/20">
                            Back to Contact Page
                        </a>
                    </div>
                </div>
            @else
                <!-- Active Thread State -->
                <div class="grid grid-cols-1 gap-8">
                    <!-- Chat Box -->
                    <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 shadow-xl backdrop-blur-sm flex flex-col gap-6">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-4 select-none">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live Conversation History
                        </h3>

                        <div class="space-y-6 max-h-[550px] overflow-y-auto px-2 py-4 flex flex-col gap-6">
                            <!-- Visitor's Original Message (Right Aligned for Visitor) -->
                            <div class="flex items-start gap-3 max-w-[85%] self-end flex-row-reverse group">
                                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs shrink-0 select-none">
                                    {{ strtoupper(substr($contactMessage->name, 0, 2)) }}
                                </div>
                                <div class="space-y-1 text-right">
                                    <div class="flex items-center gap-2 justify-end mr-1 select-none">
                                        <span class="text-[11px] text-indigo-400 font-bold">You ({{ $contactMessage->name }})</span>
                                    </div>
                                    <div class="bg-indigo-950/40 border border-indigo-500/30 text-indigo-100 px-4 py-3 rounded-2xl rounded-tr-none text-left relative transition-shadow">
                                        <div class="text-sm leading-relaxed whitespace-pre-line">
                                            {{ $contactMessage->message }}
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end gap-2 mt-1 mr-1 select-none">
                                        <span class="text-[10px] text-slate-500">
                                            {{ $contactMessage->created_at->format('M d, Y h:i A') }}
                                        </span>
                                        <button type="button" onclick="quoteMessage('{{ addslashes($contactMessage->name) }}', '{{ addslashes(Str::limit($contactMessage->message, 80)) }}', true, 0)" class="text-[10px] text-indigo-400 hover:underline font-bold transition-all">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Replies -->
                            @foreach($contactMessage->replies as $reply)
                                @php
                                    $isAdmin = ($reply->user_id !== null);
                                @endphp

                                @if($isAdmin)
                                    <!-- Staff Reply (Left Aligned for Visitor) -->
                                    <div class="flex items-start gap-3 max-w-[85%] self-start group">
                                        <div class="w-9 h-9 rounded-full bg-slate-700 text-white font-bold flex items-center justify-center text-xs shrink-0 select-none">
                                            {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-1.5 ml-1 select-none">
                                                <span class="text-[11px] text-slate-400 font-bold">{{ $reply->user->name }}</span>
                                                <span class="text-[9px] bg-indigo-950/60 text-indigo-400 border border-indigo-800 px-1.5 py-0.5 rounded font-bold uppercase">Staff</span>
                                            </div>

                                            <!-- Render Quoted Message if present -->
                                            @if($reply->reply_to_original)
                                                <div class="bg-slate-900/40 border border-slate-800/40 px-3 py-1.5 rounded-2xl rounded-tl-none text-[11px] text-slate-400 mb-1 max-w-sm truncate text-left select-none">
                                                    <span class="font-bold text-indigo-500">Replying to You:</span> "{{ $contactMessage->message }}"
                                                </div>
                                            @elseif($reply->parent_reply_id && $reply->parentReply)
                                                <div class="bg-slate-900/40 border border-slate-800/40 px-3 py-1.5 rounded-2xl rounded-tl-none text-[11px] text-slate-400 mb-1 max-w-sm truncate text-left select-none">
                                                    <span class="font-bold text-indigo-500">Replying to {{ $reply->parentReply->user ? $reply->parentReply->user->name : 'You' }}:</span> "{{ $reply->parentReply->message }}"
                                                </div>
                                            @endif

                                            <div class="bg-slate-800/60 border border-slate-700/40 text-slate-200 px-4 py-3 rounded-2xl rounded-tl-none relative transition-shadow">
                                                <div class="text-sm leading-relaxed whitespace-pre-line">
                                                    {{ $reply->message }}
                                                </div>

                                                <!-- Attachment Display -->
                                                @if($reply->attachment_path)
                                                    <div class="mt-3 pt-3 border-t border-slate-700/40">
                                                        @php
                                                            $ext = strtolower(pathinfo($reply->attachment_path, PATHINFO_EXTENSION));
                                                            $isImage = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                                            $isVideo = in_array($ext, ['mp4', 'webm', 'ogg', 'mov']);
                                                        @endphp

                                                        @if($isImage)
                                                            <a href="{{ asset('storage/' . $reply->attachment_path) }}" target="_blank" class="block overflow-hidden rounded-lg">
                                                                <img src="{{ asset('storage/' . $reply->attachment_path) }}" class="max-w-xs rounded-lg border border-slate-700/50 shadow-md hover:scale-105 transition-transform duration-200">
                                                            </a>
                                                        @elseif($isVideo)
                                                            <video src="{{ asset('storage/' . $reply->attachment_path) }}" controls class="max-w-xs rounded-lg border border-slate-700/50 shadow-md"></video>
                                                        @else
                                                            <a href="{{ asset('storage/' . $reply->attachment_path) }}" download class="inline-flex items-center gap-2 px-3 py-2 bg-slate-950 border border-slate-850 rounded-xl text-xs text-indigo-400 hover:text-white transition-colors">
                                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                                <span class="truncate max-w-xs font-mono font-bold">{{ $reply->attachment_name }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-1 ml-1 select-none">
                                                <span class="text-[10px] text-slate-500">
                                                    {{ $reply->created_at->format('M d, Y h:i A') }}
                                                </span>
                                                <button type="button" onclick="quoteMessage('{{ addslashes($reply->user->name) }}', '{{ addslashes(Str::limit($reply->message, 80)) }}', false, {{ $reply->id }})" class="text-[10px] text-indigo-400 hover:underline font-bold transition-all">
                                                    Reply
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Guest Reply (Right Aligned for Visitor) -->
                                    <div class="flex items-start gap-3 max-w-[85%] self-end flex-row-reverse group">
                                        <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs shrink-0 select-none">
                                            {{ strtoupper(substr($contactMessage->name, 0, 2)) }}
                                        </div>
                                        <div class="space-y-1 text-right">
                                            <div class="flex items-center gap-2 justify-end mr-1 select-none">
                                                <span class="text-[11px] text-indigo-400 font-bold">You ({{ $contactMessage->name }})</span>
                                            </div>

                                            <!-- Render Quoted Message if present -->
                                            @if($reply->reply_to_original)
                                                <div class="bg-slate-900/40 border border-slate-800/40 px-3 py-1.5 rounded-2xl rounded-tr-none text-[11px] text-slate-400 mb-1 max-w-sm truncate text-left ml-auto select-none">
                                                    <span class="font-bold text-indigo-500">Replying to You:</span> "{{ $contactMessage->message }}"
                                                </div>
                                            @elseif($reply->parent_reply_id && $reply->parentReply)
                                                <div class="bg-slate-900/40 border border-slate-800/40 px-3 py-1.5 rounded-2xl rounded-tr-none text-[11px] text-slate-400 mb-1 max-w-sm truncate text-left ml-auto select-none">
                                                    <span class="font-bold text-indigo-500">Replying to {{ $reply->parentReply->user ? $reply->parentReply->user->name : 'You' }}:</span> "{{ $reply->parentReply->message }}"
                                                </div>
                                            @endif

                                            <div class="bg-indigo-950/40 border border-indigo-500/30 text-indigo-100 px-4 py-3 rounded-2xl rounded-tr-none text-left relative transition-shadow">
                                                <div class="text-sm leading-relaxed whitespace-pre-line">
                                                    {{ $reply->message }}
                                                </div>

                                                <!-- Attachment Display -->
                                                @if($reply->attachment_path)
                                                    <div class="mt-3 pt-3 border-t border-indigo-500/20">
                                                        @php
                                                            $ext = strtolower(pathinfo($reply->attachment_path, PATHINFO_EXTENSION));
                                                            $isImage = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                                            $isVideo = in_array($ext, ['mp4', 'webm', 'ogg', 'mov']);
                                                        @endphp

                                                        @if($isImage)
                                                            <a href="{{ asset('storage/' . $reply->attachment_path) }}" target="_blank" class="block overflow-hidden rounded-lg">
                                                                <img src="{{ asset('storage/' . $reply->attachment_path) }}" class="max-w-xs rounded-lg border border-indigo-500/20 shadow-md hover:scale-105 transition-transform duration-200">
                                                            </a>
                                                        @elseif($isVideo)
                                                            <video src="{{ asset('storage/' . $reply->attachment_path) }}" controls class="max-w-xs rounded-lg border border-indigo-500/20 shadow-md"></video>
                                                        @else
                                                            <a href="{{ asset('storage/' . $reply->attachment_path) }}" download class="inline-flex items-center gap-2 px-3 py-2 bg-slate-905 border border-indigo-900 rounded-xl text-xs text-indigo-300 hover:text-white transition-colors">
                                                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                                <span class="truncate max-w-xs font-mono font-bold">{{ $reply->attachment_name }}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-end gap-2 mt-1 mr-1 select-none">
                                                <span class="text-[10px] text-slate-500">
                                                    {{ $reply->created_at->format('M d, Y h:i A') }}
                                                </span>
                                                <button type="button" onclick="quoteMessage('You', '{{ addslashes(Str::limit($reply->message, 80)) }}', false, {{ $reply->id }})" class="text-[10px] text-indigo-400 hover:underline font-bold transition-all">
                                                    Reply
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Guest Composer Form -->
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-xl rounded-2xl p-6">
                        <h3 class="text-base font-bold text-white mb-4">Send a Reply</h3>
                        <form action="{{ route('contact-messages.guest-reply', $contactMessage) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <!-- Quoted Preview Banner -->
                            <div id="reply-quote-container" class="hidden flex items-center justify-between bg-slate-950 border border-slate-800 border-b-0 px-4 py-2.5 rounded-t-2xl">
                                <div class="flex-grow min-w-0 pr-4 border-l-4 border-indigo-500 pl-3">
                                    <div class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-0.5">Replying to <span id="quote-author"></span></div>
                                    <p id="quote-preview-text" class="text-xs text-slate-400 truncate"></p>
                                </div>
                                <button type="button" onclick="cancelQuote()" class="text-slate-400 hover:text-white transition-colors p-1" title="Cancel Quote">
                                    ✕
                                </button>
                            </div>

                            <!-- Hidden Inputs for Quoting -->
                            <input type="hidden" name="parent_reply_id" id="parent-reply-id" value="">
                            <input type="hidden" name="reply_to_original" id="reply-to-original" value="0">

                            <!-- Rich Editor Controls (Emojis & Attachments) -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-slate-900/50 border border-slate-700/50 border-b-0 rounded-t-2xl px-4 py-3 gap-3">
                                <!-- Attachment Upload Trigger -->
                                <div class="flex items-center gap-3">
                                    <label class="flex items-center gap-1.5 px-3 py-2 bg-slate-950 border border-slate-700/60 rounded-xl text-xs text-slate-300 hover:text-white cursor-pointer hover:border-slate-500 transition-colors shadow-sm select-none">
                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                        </svg>
                                        <span>Attach File</span>
                                        <input type="file" name="attachment" class="hidden" onchange="document.getElementById('attachment-info').innerText = this.files[0] ? this.files[0].name : '';">
                                    </label>
                                    <span id="attachment-info" class="text-xs text-slate-400 truncate max-w-xs font-mono font-semibold"></span>
                                </div>

                                <!-- Emoji Trigger & Picker -->
                                <div class="relative" id="emoji-picker-container">
                                    <button type="button" onclick="toggleEmojiPicker()" class="text-xs hover:scale-105 transition-transform focus:outline-none px-3 py-2 bg-slate-950 border border-slate-700/60 rounded-xl text-slate-300 font-bold shadow-sm select-none" title="Insert Emoji">
                                        😊 Emojis
                                    </button>
                                    <div id="emoji-picker" class="hidden absolute bottom-12 right-0 bg-slate-950 border border-slate-700 p-3 rounded-2xl shadow-xl z-20 w-56 grid grid-cols-5 gap-2">
                                        @php
                                            $emojis = ['😊', '😂', '👍', '❤️', '🎉', '🔥', '🙌', '😮', '😢', '😡', '🙏', '✨', '⭐', '🤔', '💬', '🤩', '💡', '✅', '❌', '🚀'];
                                        @endphp
                                        @foreach($emojis as $emoji)
                                            <button type="button" onclick="insertEmoji('{{ $emoji }}')" class="hover:bg-slate-800 p-1.5 rounded-lg text-lg focus:outline-none transition-colors">
                                                {{ $emoji }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Textarea -->
                            <div>
                                <textarea id="reply-textarea" name="message" required rows="4" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-b-2xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-none text-sm placeholder-slate-500" placeholder="Type your reply here..."></textarea>
                            </div>

                            <div class="flex justify-between items-center">
                                <button type="button" onclick="openResolveModal()" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-emerald-500/50 bg-emerald-600/20 hover:bg-emerald-600/40 text-emerald-400 text-xs font-bold transition-all">
                                    Resolve & Leave Feedback
                                </button>
                                <x-skew-button type="submit" class="px-6 py-2.5 text-sm font-bold bg-indigo-650 hover:bg-indigo-600 border-indigo-500/50 text-indigo-300">
                                    Send Message
                                </x-skew-button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </main>

    <!-- Scripting for Emoji Picker, Quoting -->
    <script>
        function toggleEmojiPicker() {
            const picker = document.getElementById('emoji-picker');
            picker.classList.toggle('hidden');
        }

        // Insert Emoji
        function insertEmoji(emoji) {
            const textarea = document.getElementById('reply-textarea');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            textarea.value = text.substring(0, start) + emoji + text.substring(end);
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + emoji.length;
            document.getElementById('emoji-picker').classList.add('hidden');
        }

        // Close emoji picker when clicking outside
        document.addEventListener('click', (e) => {
            const container = document.getElementById('emoji-picker-container');
            const picker = document.getElementById('emoji-picker');
            if (container && !container.contains(e.target) && picker) {
                picker.classList.add('hidden');
            }
        });

        // Quote Message function
        function quoteMessage(name, text, isOriginal, replyId) {
            document.getElementById('parent-reply-id').value = isOriginal ? '' : replyId;
            document.getElementById('reply-to-original').value = isOriginal ? '1' : '0';
            document.getElementById('quote-author').innerText = name;
            document.getElementById('quote-preview-text').innerText = text;
            
            const quoteContainer = document.getElementById('reply-quote-container');
            quoteContainer.classList.remove('hidden');
            
            // Scroll to composer form smoothly
            document.getElementById('reply-textarea').scrollIntoView({ behavior: 'smooth' });
            document.getElementById('reply-textarea').focus();
        }

        function cancelQuote() {
            document.getElementById('parent-reply-id').value = '';
            document.getElementById('reply-to-original').value = '0';
            document.getElementById('reply-quote-container').classList.add('hidden');
        }
    </script>

    <!-- Resolve Dialog/Modal Overlay -->
    <div id="resolve-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/60 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-750/80 w-full max-w-md p-6 rounded-2xl shadow-2xl relative mx-4 text-left">
            <h3 class="text-xl font-bold text-white mb-2">Resolve Inquiry & Leave Feedback</h3>
            <p class="text-slate-400 text-sm mb-4">Please rate your conversation satisfaction and leave a brief comment.</p>
            
            <form action="{{ route('contact-messages.guest-resolve', $contactMessage) }}" method="POST">
                @csrf
                
                <!-- Star Rating selection -->
                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Satisfaction Rating</label>
                    <div class="flex items-center gap-2" id="star-picker">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="star-btn text-3xl text-slate-650 hover:scale-110 transition-transform" data-value="{{ $i }}">
                                ★
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-value" value="5">
                </div>

                <!-- Resolution Comment -->
                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Feedback Comment</label>
                    <textarea name="resolution_comment" rows="3" required class="w-full px-4 py-2.5 bg-slate-950 border border-slate-750/80 rounded-xl text-white text-sm focus:outline-none focus:border-indigo-500 resize-none" placeholder="Provide notes or feedback regarding your experience..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" onclick="closeResolveModal()" class="px-4 py-2 text-xs font-bold text-slate-400 hover:text-white transition-colors bg-slate-800 rounded-lg">
                        Cancel
                    </button>
                    <x-skew-button type="submit" class="px-5 py-2 text-xs font-bold bg-emerald-600/30 hover:bg-emerald-600/50 border-emerald-500/50 text-emerald-400">
                        Confirm Resolution
                    </x-skew-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripting for Resolve Modal & Stars -->
    <script>
        function openResolveModal() {
            document.getElementById('resolve-modal').classList.remove('hidden');
        }

        // Close resolve modal
        function closeResolveModal() {
            document.getElementById('resolve-modal').classList.add('hidden');
        }

        // Star rating
        function setRating(val) {
            document.getElementById('rating-value').value = val;
            const stars = document.querySelectorAll('.star-btn');
            stars.forEach((star, index) => {
                if (index < val) {
                    star.classList.remove('text-slate-650');
                    star.classList.add('text-amber-400');
                } else {
                    star.classList.remove('text-amber-400');
                    star.classList.add('text-slate-650');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            setRating(5);
        });
    </script>

    <!-- Footer -->
    <x-sitemap />
    <x-footer />
</body>
</html>
