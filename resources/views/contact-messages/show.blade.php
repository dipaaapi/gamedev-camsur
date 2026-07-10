<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('Inquiry Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Back to Inbox & Top Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('contact-messages.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-400 hover:text-white transition-colors gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Inbox
                </a>

                <div class="flex items-center gap-2">
                    @if($contactMessage->status !== 'Resolved')
                        <form action="{{ route('contact-messages.resolve', $contactMessage) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to mark this inquiry as resolved?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-emerald-500/50 bg-emerald-600/20 hover:bg-emerald-600/40 text-emerald-400 text-xs font-bold transition-all">
                                Mark as Resolved
                            </button>
                        </form>
                    @endif

                    @if($contactMessage->status === 'Archived')
                        <form action="{{ route('contact-messages.restore', $contactMessage) }}" method="POST" class="m-0">
                            @csrf
                            <x-skew-button type="submit" class="px-4 py-2 text-xs font-bold bg-indigo-650/40 hover:bg-indigo-600 border-indigo-500/50 text-indigo-300">
                                Restore
                            </x-skew-button>
                        </form>
                    @else
                        <form action="{{ route('contact-messages.archive', $contactMessage) }}" method="POST" class="m-0">
                            @csrf
                            <x-skew-button type="submit" class="px-4 py-2 text-xs font-bold bg-amber-600/20 hover:bg-amber-600/50 border-amber-500/30 text-amber-400">
                                Archive
                            </x-skew-button>
                        </form>
                    @endif

                    <form action="{{ route('contact-messages.destroy', $contactMessage) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to permanently delete this inquiry?');">
                        @csrf
                        @method('DELETE')
                        <x-skew-button type="submit" class="px-4 py-2 text-xs font-bold bg-rose-600/30 hover:bg-rose-600 border-rose-500/50 text-rose-455">
                            Delete
                        </x-skew-button>
                    </form>
                </div>
            </div>

            <!-- Success/Error Alerts -->
            @if (session('success'))
                <div class="p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-450 rounded-xl text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-450 rounded-xl text-sm font-semibold">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Resolution Rating Banner -->
            @if($contactMessage->status === 'Resolved')
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-xl">
                    <div>
                        <h4 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="text-emerald-450">✓</span> Resolved Inquiry
                        </h4>
                        @if($contactMessage->resolution_comment)
                            <p class="text-slate-300 text-sm mt-2 italic">"{{ $contactMessage->resolution_comment }}"</p>
                        @endif
                    </div>
                    <div class="flex flex-col items-end shrink-0">
                        <span class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Resolution Rating</span>
                        <div class="flex items-center gap-1 text-amber-400 text-xl">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $contactMessage->rating)
                                    ★
                                @else
                                    <span class="text-slate-600">★</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            @endif

            <!-- Inquiry Details Card (Meta Information Only) -->
            <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-xl rounded-2xl p-8 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h3 class="text-2xl font-bold text-white">{{ $contactMessage->name }}</h3>
                            @if($contactMessage->pronouns)
                                <span class="text-xs bg-indigo-500/10 text-indigo-405 border border-indigo-500/20 px-2.5 py-0.5 rounded font-semibold">{{ $contactMessage->pronouns }}</span>
                            @endif
                        </div>
                        <p class="text-slate-400 text-sm mt-1">
                            Email: <a href="mailto:{{ $contactMessage->email }}" class="text-indigo-400 hover:underline">{{ $contactMessage->email ?? 'N/A' }}</a> 
                            | Mobile: <span class="text-slate-300 font-mono">{{ $contactMessage->mobile_number }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        @php
                            $purposeColor = match(strtok($contactMessage->purpose, ':')) {
                                'Inquiry' => 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20',
                                'Complaint' => 'bg-rose-500/10 text-rose-400 border border-rose-500/20',
                                'Suggestion' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                'Purchase' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                'Priority' => 'bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20',
                                default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20'
                            };
                            $statusColor = match($contactMessage->status) {
                                'Pending' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                'Replied' => 'bg-blue-500/10 text-blue-400 border border-blue-500/20',
                                'Resolved' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                'Archived' => 'bg-purple-500/10 text-purple-400 border border-purple-500/20',
                                default => 'bg-slate-500/10 text-slate-400 border border-slate-500/20'
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $purposeColor }}">
                            {{ $contactMessage->purpose }}
                        </span>
                        <span class="px-3 py-1 text-xs font-bold rounded-lg {{ $statusColor }}">
                            {{ $contactMessage->status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Messenger-Style Chat Thread -->
            <div class="bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                <h3 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-4">
                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Messenger Chat History
                </h3>

                <div class="space-y-4 max-h-[500px] overflow-y-auto px-2 py-6 flex flex-col gap-6">
                    <!-- Client's Original Message (Left Aligned) -->
                    <div class="flex items-start gap-3 max-w-[85%] self-start group">
                        <!-- Initials Avatar -->
                        <div class="w-9 h-9 rounded-full bg-slate-700 text-white font-bold flex items-center justify-center text-xs shrink-0 select-none">
                            {{ strtoupper(substr($contactMessage->name, 0, 2)) }}
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 ml-1">
                                <span class="text-[11px] text-slate-400 font-bold">{{ $contactMessage->name }}</span>
                            </div>
                            <div class="bg-slate-800/60 border @if($contactMessage->is_flagged) border-rose-500 bg-rose-950/20 @else border-slate-700/40 @endif text-slate-200 px-4 py-3 rounded-2xl rounded-tl-none relative transition-shadow">
                                @if($contactMessage->is_flagged)
                                    <div class="flex items-center gap-1.5 text-xs text-rose-455 font-bold mb-1.5 select-none">
                                        <span>🚩 Red Flagged (Foul language detected)</span>
                                    </div>
                                @endif
                                <div class="text-sm leading-relaxed whitespace-pre-line">
                                    @php
                                        $origMsg = e($contactMessage->message);
                                        $origMsg = preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-indigo-400 hover:underline break-all">$1</a>', $origMsg);
                                    @endphp
                                    {!! $origMsg !!}
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 mt-1 ml-1 select-none">
                                <span class="text-[10px] text-slate-500">
                                    {{ $contactMessage->created_at->format('M d, Y h:i A') }}
                                </span>
                                @if($contactMessage->status !== 'Resolved')
                                    <button type="button" onclick="quoteMessage('{{ addslashes($contactMessage->name) }}', '{{ addslashes(Str::limit($contactMessage->message, 80)) }}', true, 0)" class="text-[10px] text-indigo-400 hover:underline font-bold transition-all">
                                        Reply
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Conversation Replies -->
                    @foreach($contactMessage->replies as $reply)
                        @php
                            $isAdmin = ($reply->user_id !== null);
                            $formattedMsg = e($reply->message);
                            $formattedMsg = preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-indigo-300 hover:underline break-all">$1</a>', $formattedMsg);
                        @endphp
                        
                        @if($isAdmin)
                            <!-- Staff Reply (Right Aligned) -->
                            <div class="flex items-start gap-3 max-w-[85%] self-end flex-row-reverse group">
                                <!-- Initials Avatar -->
                                <div class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs shrink-0 select-none">
                                    {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                </div>
                                <div class="space-y-1 text-right">
                                    <div class="flex items-center gap-1.5 justify-end mr-1">
                                        <span class="text-[11px] text-indigo-400 font-bold">{{ $reply->user->name }}</span>
                                        <span class="text-[9px] bg-indigo-950/60 text-indigo-400 border border-indigo-800 px-1.5 py-0.5 rounded font-bold uppercase select-none">Staff</span>
                                        
                                        <!-- Reply Mode Tag -->
                                        <span class="text-[9px] bg-slate-950 text-slate-400 border border-slate-800 px-1.5 py-0.5 rounded font-bold uppercase select-none" title="Delivered via: {{ ucfirst($reply->reply_mode) }}">
                                            {{ $reply->reply_mode }}
                                        </span>
                                    </div>

                                    <!-- Render Quoted Message if present -->
                                    @if($reply->reply_to_original)
                                        <div class="bg-slate-900/40 border border-slate-800/40 px-3 py-1.5 rounded-2xl rounded-tr-none text-[11px] text-slate-400 mb-1 max-w-sm truncate text-left ml-auto select-none">
                                            <span class="font-bold text-indigo-500">Replying to {{ $contactMessage->name }}:</span> "{{ $contactMessage->message }}"
                                        </div>
                                    @elseif($reply->parent_reply_id && $reply->parentReply)
                                        <div class="bg-slate-900/40 border border-slate-800/40 px-3 py-1.5 rounded-2xl rounded-tr-none text-[11px] text-slate-400 mb-1 max-w-sm truncate text-left ml-auto select-none">
                                            <span class="font-bold text-indigo-500">Replying to {{ $reply->parentReply->user ? $reply->parentReply->user->name : $contactMessage->name }}:</span> "{{ $reply->parentReply->message }}"
                                        </div>
                                    @endif

                                    <div class="bg-indigo-950/40 border @if($reply->is_flagged) border-rose-500 bg-rose-950/20 @else border-indigo-500/30 @endif text-indigo-100 px-4 py-3 rounded-2xl rounded-tr-none text-left relative transition-shadow">
                                        @if($reply->is_flagged)
                                            <div class="flex items-center gap-1.5 text-xs text-rose-455 font-bold mb-1.5 select-none">
                                                <span>🚩 Red Flagged (Foul language detected)</span>
                                            </div>
                                        @endif
                                        <div class="text-sm leading-relaxed whitespace-pre-line">
                                            {!! $formattedMsg !!}
                                        </div>

                                        <!-- Attachment display -->
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
                                                    <!-- Document Download -->
                                                    <a href="{{ asset('storage/' . $reply->attachment_path) }}" download class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-indigo-300 hover:text-white transition-colors">
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
                                        @if($contactMessage->status !== 'Resolved')
                                            <button type="button" onclick="quoteMessage('{{ addslashes($reply->user->name) }}', '{{ addslashes(Str::limit($reply->message, 80)) }}', false, {{ $reply->id }})" class="text-[10px] text-indigo-400 hover:underline font-bold transition-all">
                                                Reply
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- User Reply (Left Aligned) -->
                            <div class="flex items-start gap-3 max-w-[85%] self-start group">
                                <div class="w-9 h-9 rounded-full bg-slate-700 text-white font-bold flex items-center justify-center text-xs shrink-0 select-none">
                                    {{ strtoupper(substr($contactMessage->name, 0, 2)) }}
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 ml-1">
                                        <span class="text-[11px] text-slate-400 font-bold">{{ $contactMessage->name }}</span>
                                    </div>

                                    <!-- Render Quoted Message if present -->
                                    @if($reply->reply_to_original)
                                        <div class="bg-slate-900/40 border border-slate-800/40 px-3 py-1.5 rounded-2xl rounded-tl-none text-[11px] text-slate-400 mb-1 max-w-sm truncate text-left select-none">
                                            <span class="font-bold text-indigo-500">Replying to {{ $contactMessage->name }}:</span> "{{ $contactMessage->message }}"
                                        </div>
                                    @elseif($reply->parent_reply_id && $reply->parentReply)
                                        <div class="bg-slate-900/40 border border-slate-800/40 px-3 py-1.5 rounded-2xl rounded-tl-none text-[11px] text-slate-400 mb-1 max-w-sm truncate text-left select-none">
                                            <span class="font-bold text-indigo-500">Replying to {{ $reply->parentReply->user ? $reply->parentReply->user->name : $contactMessage->name }}:</span> "{{ $reply->parentReply->message }}"
                                        </div>
                                    @endif

                                    <div class="bg-slate-800/60 border @if($reply->is_flagged) border-rose-500 bg-rose-950/20 @else border-slate-700/40 @endif text-slate-200 px-4 py-3 rounded-2xl rounded-tl-none relative transition-shadow">
                                        @if($reply->is_flagged)
                                            <div class="flex items-center gap-1.5 text-xs text-rose-455 font-bold mb-1.5 select-none">
                                                <span>🚩 Red Flagged (Foul language detected)</span>
                                            </div>
                                        @endif
                                        <div class="text-sm leading-relaxed whitespace-pre-line">
                                            {!! $formattedMsg !!}
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 mt-1 ml-1 select-none">
                                        <span class="text-[10px] text-slate-500">
                                            {{ $reply->created_at->format('M d, Y h:i A') }}
                                        </span>
                                        @if($contactMessage->status !== 'Resolved')
                                            <button type="button" onclick="quoteMessage('{{ addslashes($contactMessage->name) }}', '{{ addslashes(Str::limit($reply->message, 80)) }}', false, {{ $reply->id }})" class="text-[10px] text-indigo-400 hover:underline font-bold transition-all">
                                                Reply
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Send Reply Form -->
            @if($contactMessage->status !== 'Resolved')
                <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-xl rounded-2xl p-8">
                    <h3 class="text-lg font-bold text-white mb-4">Compose Reply</h3>
                    
                    <form action="{{ route('contact-messages.reply', $contactMessage) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <!-- Quoted Preview Banner -->
                        <div id="reply-quote-container" class="hidden flex items-center justify-between bg-slate-950 border border-slate-800 border-b-0 px-4 py-2.5 rounded-t-2xl">
                            <div class="flex-grow min-w-0 pr-4 border-l-4 border-indigo-500 pl-3">
                                <div class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider mb-0.5">Replying to <span id="quote-author"></span></div>
                                <p id="quote-preview-text" class="text-xs text-slate-400 truncate"></p>
                            </div>
                            <button type="button" onclick="cancelQuote()" class="text-slate-405 hover:text-white transition-colors p-1" title="Cancel Quote">
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
                                <label class="flex items-center gap-1.5 px-3 py-2 bg-slate-950 border border-slate-700/60 rounded-xl text-xs text-slate-350 hover:text-white cursor-pointer hover:border-slate-550 transition-colors shadow-sm select-none">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    <span>Attach File</span>
                                    <input type="file" name="attachment" class="hidden" onchange="document.getElementById('attachment-info').innerText = this.files[0] ? this.files[0].name : '';">
                                </label>
                                <span id="attachment-info" class="text-xs text-slate-400 truncate max-w-xs font-mono font-semibold"></span>
                            </div>

                            <!-- Emoji & Reply Mode Controls -->
                            <div class="flex items-center gap-4">
                                <!-- Reply Mode Selection -->
                                <div class="flex items-center gap-2">
                                    <div class="inline-flex rounded-xl bg-slate-950 p-1 border border-slate-750/80 shadow-sm select-none">
                                        <label class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all {{ $contactMessage->email ? 'text-slate-300' : 'opacity-30 cursor-not-allowed' }}" id="mode-email-label">
                                            <input type="radio" name="reply_mode" value="email" class="hidden" {{ !$contactMessage->email ? 'disabled' : '' }} onchange="updateReplyModeUI(this)">
                                            <span>Email</span>
                                        </label>
                                        <label class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all {{ $contactMessage->mobile_number ? 'text-slate-300' : 'opacity-30 cursor-not-allowed' }}" id="mode-sms-label">
                                            <input type="radio" name="reply_mode" value="sms" class="hidden" {{ !$contactMessage->mobile_number ? 'disabled' : '' }} onchange="updateReplyModeUI(this)">
                                            <span>SMS</span>
                                        </label>
                                        <label class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold cursor-pointer transition-all {{ ($contactMessage->email && $contactMessage->mobile_number) ? 'text-slate-300' : 'opacity-30 cursor-not-allowed' }}" id="mode-both-label">
                                            <input type="radio" name="reply_mode" value="both" checked class="hidden" {{ (!($contactMessage->email && $contactMessage->mobile_number)) ? 'disabled' : '' }} onchange="updateReplyModeUI(this)">
                                            <span>Both</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Emoji Trigger -->
                                <div class="relative" id="emoji-picker-container select-none">
                                    <button type="button" onclick="toggleEmojiPicker()" class="text-xs hover:scale-105 transition-transform focus:outline-none px-3 py-2 bg-slate-950 border border-slate-700/60 rounded-xl text-slate-300 font-bold shadow-sm" title="Insert Emoji">
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
                        </div>

                        <!-- Textarea -->
                        <div>
                            <textarea id="reply-textarea" name="message" required rows="5" class="w-full px-4 py-3 bg-slate-900/50 border border-slate-700/50 rounded-b-2xl text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors resize-none text-sm placeholder-slate-500" placeholder="Type your reply to the user..."></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <p class="text-xs text-slate-400 max-w-xl" id="reply-footer-text">
                                @if($contactMessage->email && $contactMessage->mobile_number)
                                    Replying will automatically send an email to <span class="text-indigo-400 font-semibold font-mono">{{ $contactMessage->email }}</span>, an SMS to <span class="text-indigo-400 font-semibold font-mono">{{ $contactMessage->mobile_number }}</span>, and update the status to <span class="text-blue-500 font-semibold">Replied</span>.
                                @elseif($contactMessage->email)
                                    Replying will automatically send an email to <span class="text-indigo-400 font-semibold font-mono">{{ $contactMessage->email }}</span> and update the status to <span class="text-blue-500 font-semibold">Replied</span>.
                                @elseif($contactMessage->mobile_number)
                                    Replying will automatically send an SMS to <span class="text-indigo-400 font-semibold font-mono">{{ $contactMessage->mobile_number }}</span> and update the status to <span class="text-blue-500 font-semibold">Replied</span>.
                                @else
                                    Replying will update the inquiry status to <span class="text-blue-400 font-semibold">Replied</span>.
                                @endif
                            </p>
                            <x-skew-button type="submit" class="px-6 py-2.5 text-sm font-bold shrink-0 self-end">
                                Send Reply
                            </x-skew-button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Scripting for Emoji Picker and Reply Modes -->
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

        // Reply Mode Selector Styling & Text Updates
        function updateReplyModeUI(radio) {
            const modes = ['email', 'sms', 'both'];
            modes.forEach(m => {
                const label = document.getElementById('mode-' + m + '-label');
                if (label) {
                    label.classList.remove('bg-indigo-600', 'text-white', 'shadow-md', 'bg-slate-200');
                    label.classList.add('text-slate-300');
                }
            });

            const activeLabel = document.getElementById('mode-' + radio.value + '-label');
            if (activeLabel) {
                activeLabel.classList.remove('text-slate-300');
                activeLabel.classList.add('bg-indigo-600', 'text-white', 'shadow-md');
            }

            // Update bottom description text
            const footerText = document.getElementById('reply-footer-text');
            if (footerText) {
                const email = "{{ $contactMessage->email }}";
                const mobile = "{{ $contactMessage->mobile_number }}";
                if (radio.value === 'both') {
                    footerText.innerHTML = `Replying will automatically send an email to <span class="text-indigo-400 font-semibold font-mono">${email}</span>, an SMS to <span class="text-indigo-400 font-semibold font-mono">${mobile}</span>, and update the status to <span class="text-blue-500 font-semibold">Replied</span>.`;
                } else if (radio.value === 'email') {
                    footerText.innerHTML = `Replying will automatically send an email to <span class="text-indigo-400 font-semibold font-mono">${email}</span> and update the status to <span class="text-blue-500 font-semibold">Replied</span>.`;
                } else if (radio.value === 'sms') {
                    footerText.innerHTML = `Replying will automatically send an SMS to <span class="text-indigo-400 font-semibold font-mono">${mobile}</span> and update the status to <span class="text-blue-500 font-semibold">Replied</span>.`;
                }
            }
        }

        // Initialize elements on load
        document.addEventListener('DOMContentLoaded', () => {
            // Auto click/initialize checked radio
            const checkedRadio = document.querySelector('input[name="reply_mode"]:checked');
            if (checkedRadio) {
                updateReplyModeUI(checkedRadio);
            } else {
                const enabledRadio = document.querySelector('input[name="reply_mode"]:not([disabled])');
                if (enabledRadio) {
                    enabledRadio.checked = true;
                    updateReplyModeUI(enabledRadio);
                }
            }
        });
    </script>
</x-app-layout>
