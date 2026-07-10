<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('My Profile & Social Hub') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ groupChatCreateOpen: false, showContactInfo: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Main Grid layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left/Middle Columns: Profile Settings -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Profile Info Card -->
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-[0_0_30px_rgba(0,0,0,0.2)] rounded-2xl p-6 sm:p-8">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- Password Update Card -->
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-[0_0_30px_rgba(0,0,0,0.2)] rounded-2xl p-6 sm:p-8">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Right Column: Social Hub (Friends & Invites) -->
                <div class="lg:col-span-1 space-y-8">
                    
                    <!-- Search & Add Friend -->
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-[0_0_30px_rgba(0,0,0,0.2)] rounded-2xl p-6">
                        <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Add Friend
                        </h3>
                        <form method="POST" action="{{ route('friendships.store') }}" class="space-y-3">
                            @csrf
                            <div>
                                <input type="text" name="search_query" placeholder="Enter username, email, phone, or name" class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-white text-xs outline-none focus:ring-1 focus:ring-indigo-500 transition-all" required>
                                @error('search_query')
                                    <span class="text-[11px] text-rose-400 mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <x-skew-button type="submit" class="w-full py-2 text-xs uppercase tracking-wider font-bold">
                                Send Request
                            </x-skew-button>
                        </form>
                    </div>

                    <!-- Pending Invites Section -->
                    @if($pendingIncoming->isNotEmpty() || $pendingOutgoing->isNotEmpty())
                        <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-[0_0_30px_rgba(0,0,0,0.2)] rounded-2xl p-6 space-y-4">
                            @if($pendingIncoming->isNotEmpty())
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Incoming Requests</h4>
                                    <div class="space-y-2">
                                        @foreach($pendingIncoming as $invite)
                                            <div class="bg-slate-900/40 border border-slate-850 p-3 rounded-xl flex items-center justify-between">
                                                <div class="min-w-0 flex-1 mr-2">
                                                    <span class="text-white text-xs font-bold block truncate">{{ $invite->user->name }}</span>
                                                    <span class="text-[10px] text-slate-500 block">@<span>{{ $invite->user->username }}</span></span>
                                                </div>
                                                <div class="flex gap-1.5 flex-shrink-0">
                                                    <form method="POST" action="{{ route('friendships.accept', $invite) }}">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600/20 hover:bg-emerald-600/30 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold rounded-lg cursor-pointer transition-all">Accept</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('friendships.destroy', $invite) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-2.5 py-1 bg-rose-950/20 hover:bg-rose-900/30 border border-rose-900/30 text-rose-400 text-[10px] font-bold rounded-lg cursor-pointer transition-all">Decline</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($pendingOutgoing->isNotEmpty())
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Sent Requests</h4>
                                    <div class="space-y-2">
                                        @foreach($pendingOutgoing as $invite)
                                            <div class="bg-slate-900/40 border border-slate-850 p-3 rounded-xl flex items-center justify-between">
                                                <div class="min-w-0 flex-1 mr-2">
                                                    <span class="text-white text-xs font-bold block truncate">{{ $invite->friend->name }}</span>
                                                    <span class="text-[10px] text-slate-500 block">@<span>{{ $invite->friend->username }}</span></span>
                                                </div>
                                                <form method="POST" action="{{ route('friendships.destroy', $invite) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-755 border border-slate-700 text-slate-400 text-[10px] font-bold rounded-lg cursor-pointer transition-all">Cancel</button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Group Chats Section -->
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-[0_0_30px_rgba(0,0,0,0.2)] rounded-2xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Group Chats
                            </h3>
                            <button type="button" @click="groupChatCreateOpen = true" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-xs font-bold cursor-pointer transition-all">
                                + New Group Chat
                            </button>
                        </div>

                        @if($groupChats->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-xs text-slate-500">No active group chats.</p>
                            </div>
                        @else
                            <div class="space-y-2 max-h-[150px] overflow-y-auto pr-1">
                                @foreach($groupChats as $g)
                                    <a href="{{ route('profile.edit', ['group_chat_id' => $g->id, 'tab' => 'messages']) }}" class="flex items-center gap-2.5 p-2.5 rounded-xl border border-transparent hover:border-slate-850 hover:bg-slate-900/30 transition-all duration-200 {{ $activeGroupChat && $activeGroupChat->id === $g->id ? 'bg-indigo-500/10 border-indigo-500/30' : '' }}">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-xs font-bold text-indigo-300">
                                            #
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-bold text-white block truncate">{{ $g->name }}</span>
                                            <span class="text-[9px] text-slate-500 block truncate">{{ $g->members()->count() }} members</span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Friends & Chat Hub (Grouped & Sortable) -->
                    <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-[0_0_30px_rgba(0,0,0,0.2)] rounded-2xl p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Friends
                            </h3>
                        </div>

                        <!-- Sorting controls -->
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Sort By</label>
                                <select id="friendSortSelect" class="w-full bg-slate-950/60 border border-slate-800 rounded-lg p-2 text-white outline-none focus:border-indigo-500" onchange="applySorting()">
                                    <option value="name_asc">Name (A-Z)</option>
                                    <option value="name_desc">Name (Z-A)</option>
                                    <option value="date_desc">Newest Added</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Create Group</label>
                                <div class="flex gap-1.5">
                                    <input type="text" id="newGroupNameInput" placeholder="Group Name" class="flex-1 bg-slate-950/60 border border-slate-800 rounded-lg px-2 text-white placeholder-slate-600 focus:border-indigo-500">
                                    <button type="button" onclick="createNewGroup()" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg font-bold cursor-pointer font-bold text-xs">+</button>
                                </div>
                            </div>
                        </div>

                        <!-- Main Friends list with Groups -->
                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1" id="friendsGroupsContainer">
                            <!-- Dynamically loaded group lists -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Chat Window (Direct Message) -->
            @if($chatWithUser)
                <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-slate-900 border border-slate-800 shadow-2xl rounded-2xl overflow-hidden flex h-[600px] w-full max-w-3xl animate-scale-up relative">
                        
                        <!-- Main Chat Pane -->
                        <div class="flex-1 flex flex-col h-full min-w-0">
                            <!-- Chat Header -->
                            <div class="p-4 bg-slate-950/85 border-b border-slate-850 flex items-center justify-between cursor-pointer" @click="showContactInfo = !showContactInfo">
                                <div class="flex items-center gap-3">
                                    @if($chatWithUser->avatar)
                                        <img src="{{ asset('storage/' . $chatWithUser->avatar) }}" alt="{{ $chatWithUser->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-700">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($chatWithUser->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $chatWithUser->name }}" class="w-10 h-10 rounded-full border border-slate-700">
                                    @endif
                                    <div>
                                        <h4 class="text-sm font-bold text-white hover:underline">{{ $chatWithUser->name }}</h4>
                                        <span class="text-[10px] text-slate-500">Click to view profile information</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('profile.edit') }}" class="p-2 bg-slate-850 hover:bg-slate-800 border border-slate-700 rounded-xl text-slate-400 hover:text-white transition-all cursor-pointer" @click.stop>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Chat Messages list -->
                            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-900/60" id="chatMessageListContainer">
                                @if($chatMessages->isEmpty())
                                    <div class="h-full flex flex-col items-center justify-center text-center">
                                        <span class="text-3xl mb-2">💬</span>
                                        <p class="text-xs text-slate-500">No messages yet. Send a message to start the conversation!</p>
                                    </div>
                                @else
                                    @foreach($chatMessages as $msg)
                                        @php
                                            $isMe = $msg->sender_id === $user->id;
                                        @endphp
                                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                            <div class="max-w-[70%] space-y-1">
                                                <div class="px-4 py-2.5 rounded-2xl text-xs {{ $isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-slate-950/80 border border-slate-850 text-slate-300 rounded-tl-none' }} leading-relaxed break-words shadow-md">
                                                    @if($msg->is_flagged)
                                                        <div class="flex items-center gap-1 text-[9px] text-amber-400 font-bold uppercase tracking-wider mb-1">
                                                            ⚠️ Flagged message
                                                        </div>
                                                    @endif
                                                    <p>{{ $msg->message }}</p>

                                                    @if($msg->attachment_path)
                                                        <div class="mt-2 pt-2 border-t border-white/10 flex items-center gap-2">
                                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="underline text-[10px] truncate max-w-full font-semibold hover:opacity-80">
                                                                {{ $msg->attachment_name ?? 'Download Attachment' }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="text-[9px] text-slate-500 block {{ $isMe ? 'text-right' : 'text-left' }}">{{ $msg->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Chat Input form -->
                            <div class="p-4 bg-slate-950/80 border-t border-slate-850">
                                <form method="POST" action="{{ route('direct-messages.send', $chatWithUser) }}" enctype="multipart/form-data" class="flex items-center gap-3">
                                    @csrf
                                    <!-- Attachment Clip -->
                                    <button type="button" class="p-2.5 bg-slate-850 hover:bg-slate-800 border border-slate-700 rounded-xl text-slate-400 hover:text-white transition-all cursor-pointer flex-shrink-0" onclick="document.getElementById('chat_attachment').click()" id="attachmentClipBtn" title="Add attachment">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    </button>
                                    <input type="file" name="attachment" id="chat_attachment" class="hidden" onchange="updateAttachmentIndicator(this)">

                                    <!-- Emoji Picker -->
                                    <div class="relative" x-data="{ emojiOpen: false }">
                                        <button type="button" @click="emojiOpen = !emojiOpen" class="p-2.5 bg-slate-850 hover:bg-slate-800 border border-slate-700 rounded-xl text-slate-450 hover:text-white transition-all cursor-pointer flex-shrink-0" title="Insert emoji">
                                            😀
                                        </button>
                                        <div x-show="emojiOpen" @click.outside="emojiOpen = false" x-transition class="absolute bottom-14 left-0 bg-slate-900 border border-slate-850 rounded-xl p-2.5 shadow-2xl grid grid-cols-6 gap-2.5 w-48 z-50">
                                            @php
                                                $emojis = ['😀', '😂', '😍', '👍', '🔥', '🎉', '❤️', '👏', '🚀', '💡', '💻', '✨', '😢', '😮', '🙌', '💯', '🤔', '😎'];
                                            @endphp
                                            @foreach($emojis as $emoji)
                                                <button type="button" onclick="insertEmoji('{{ $emoji }}')" @click="emojiOpen = false" class="text-base hover:scale-125 transition-transform cursor-pointer">{{ $emoji }}</button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Message Input -->
                                    <div class="flex-1 relative">
                                        <input type="text" name="message" id="chat_message_input" placeholder="Type a message..." class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl pl-4 pr-16 py-2.5 text-white text-xs outline-none" required autocomplete="off">
                                        <div id="attachmentIndicator" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-[9px] bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded font-bold">
                                            File loaded
                                        </div>
                                    </div>

                                    <x-skew-button type="submit" class="py-2.5 px-5 text-xs font-bold uppercase tracking-wider flex-shrink-0">
                                        Send
                                    </x-skew-button>
                                </form>
                            </div>
                        </div>

                        <!-- Slide-out Side-panel for Contact Details -->
                        <div x-show="showContactInfo" x-transition class="w-72 bg-slate-950 border-l border-slate-850 h-full flex flex-col p-6 space-y-6 z-10 relative" style="display:none;">
                            <div class="flex items-center justify-between border-b border-slate-850 pb-3">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Contact Information</h4>
                                <button type="button" @click="showContactInfo = false" class="text-slate-500 hover:text-white cursor-pointer">&times;</button>
                            </div>

                            <!-- Full View Profile Image -->
                            <div class="flex flex-col items-center gap-4 text-center">
                                <div class="relative w-40 h-40 rounded-2xl overflow-hidden border border-slate-800 shadow-xl group">
                                    @if($chatWithUser->avatar)
                                        <img src="{{ asset('storage/' . $chatWithUser->avatar) }}" alt="{{ $chatWithUser->name }}" class="w-full h-full object-cover">
                                        <a href="{{ asset('storage/' . $chatWithUser->avatar) }}" target="_blank" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-xs text-white font-bold">
                                            Full View Image
                                        </a>
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($chatWithUser->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $chatWithUser->name }}" class="w-full h-full object-cover">
                                        <a href="https://ui-avatars.com/api/?name={{ urlencode($chatWithUser->name) }}&color=7F9CF5&background=EBF4FF" target="_blank" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-xs text-white font-bold">
                                            Full View Image
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Contact details text fields -->
                            <div class="space-y-4 text-xs">
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Full Name</span>
                                    <span class="text-white font-semibold">{{ $chatWithUser->name }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Username</span>
                                    <span class="text-white font-semibold">@<span>{{ $chatWithUser->username }}</span></span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Email Address</span>
                                    <span class="text-white font-semibold break-all">{{ $chatWithUser->email }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Mobile Number</span>
                                    <span class="text-white font-semibold">{{ $chatWithUser->mobile_number ?? 'Not Provided' }}</span>
                                </div>
                                <div>
                                    <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Role</span>
                                    <span class="px-2 py-0.5 bg-indigo-650/20 text-indigo-300 border border-indigo-500/20 rounded font-bold uppercase text-[9px]">
                                        {{ $chatWithUser->role_name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <!-- Modal Chat Window (Group Chat) -->
            @if($activeGroupChat)
                <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-slate-900 border border-slate-800 shadow-2xl rounded-2xl overflow-hidden flex h-[600px] w-full max-w-3xl animate-scale-up relative">
                        
                        <!-- Main Chat Pane -->
                        <div class="flex-1 flex flex-col h-full min-w-0">
                            <!-- Chat Header -->
                            <div class="p-4 bg-slate-950/85 border-b border-slate-850 flex items-center justify-between cursor-pointer" @click="showContactInfo = !showContactInfo">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-600/20 border border-indigo-500/30 flex items-center justify-center text-sm font-bold text-indigo-300">
                                        #
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white hover:underline">{{ $activeGroupChat->name }}</h4>
                                        <span class="text-[10px] text-slate-500">Group Conversation &bull; Click to see members list</span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('profile.edit') }}" class="p-2 bg-slate-850 hover:bg-slate-800 border border-slate-700 rounded-xl text-slate-400 hover:text-white transition-all cursor-pointer" @click.stop>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                </div>
                            </div>

                            <!-- Chat Messages list -->
                            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-900/60" id="chatMessageListContainer">
                                @if($groupChatMessages->isEmpty())
                                    <div class="h-full flex flex-col items-center justify-center text-center">
                                        <span class="text-3xl mb-2">💬</span>
                                        <p class="text-xs text-slate-500">No group messages yet. Send a message to start chatting!</p>
                                    </div>
                                @else
                                    @foreach($groupChatMessages as $msg)
                                        @php
                                            $isMe = $msg->sender_id === $user->id;
                                        @endphp
                                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} gap-2.5">
                                            @if(!$isMe)
                                                <!-- Sender avatar -->
                                                <div class="flex-shrink-0 mt-1">
                                                    @if($msg->sender->avatar)
                                                        <img src="{{ asset('storage/' . $msg->sender->avatar) }}" alt="{{ $msg->sender->name }}" class="w-6 h-6 rounded-full object-cover border border-slate-800">
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($msg->sender->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $msg->sender->name }}" class="w-6 h-6 rounded-full border border-slate-800">
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="max-w-[70%] space-y-1">
                                                @if(!$isMe)
                                                    <span class="text-[9px] font-bold text-slate-400 block ml-1">{{ $msg->sender->name }}</span>
                                                @endif
                                                <div class="px-4 py-2.5 rounded-2xl text-xs {{ $isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-slate-950/80 border border-slate-850 text-slate-300 rounded-tl-none' }} leading-relaxed break-words shadow-md">
                                                    @if($msg->is_flagged)
                                                        <div class="flex items-center gap-1 text-[9px] text-amber-400 font-bold uppercase tracking-wider mb-1">
                                                            ⚠️ Flagged message
                                                        </div>
                                                    @endif
                                                    <p>{{ $msg->message }}</p>

                                                    @if($msg->attachment_path)
                                                        <div class="mt-2 pt-2 border-t border-white/10 flex items-center gap-2">
                                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="underline text-[10px] truncate max-w-full font-semibold hover:opacity-80">
                                                                {{ $msg->attachment_name ?? 'Download Attachment' }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                                <span class="text-[9px] text-slate-500 block {{ $isMe ? 'text-right' : 'text-left' }}">{{ $msg->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Chat Input form -->
                            <div class="p-4 bg-slate-950/80 border-t border-slate-850">
                                <form method="POST" action="{{ route('group-chats.send', $activeGroupChat) }}" enctype="multipart/form-data" class="flex items-center gap-3">
                                    @csrf
                                    <!-- Attachment Clip -->
                                    <button type="button" class="p-2.5 bg-slate-850 hover:bg-slate-800 border border-slate-700 rounded-xl text-slate-400 hover:text-white transition-all cursor-pointer flex-shrink-0" onclick="document.getElementById('chat_attachment').click()" id="attachmentClipBtn" title="Add attachment">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    </button>
                                    <input type="file" name="attachment" id="chat_attachment" class="hidden" onchange="updateAttachmentIndicator(this)">

                                    <!-- Emoji Picker -->
                                    <div class="relative" x-data="{ emojiOpen: false }">
                                        <button type="button" @click="emojiOpen = !emojiOpen" class="p-2.5 bg-slate-850 hover:bg-slate-800 border border-slate-700 rounded-xl text-slate-450 hover:text-white transition-all cursor-pointer flex-shrink-0" title="Insert emoji">
                                            😀
                                        </button>
                                        <div x-show="emojiOpen" @click.outside="emojiOpen = false" x-transition class="absolute bottom-14 left-0 bg-slate-900 border border-slate-850 rounded-xl p-2.5 shadow-2xl grid grid-cols-6 gap-2.5 w-48 z-50">
                                            @php
                                                $emojis = ['😀', '😂', '😍', '👍', '🔥', '🎉', '❤️', '👏', '🚀', '💡', '💻', '✨', '😢', '😮', '🙌', '💯', '🤔', '😎'];
                                            @endphp
                                            @foreach($emojis as $emoji)
                                                <button type="button" onclick="insertEmoji('{{ $emoji }}')" @click="emojiOpen = false" class="text-base hover:scale-125 transition-transform cursor-pointer">{{ $emoji }}</button>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Message Input -->
                                    <div class="flex-1 relative">
                                        <input type="text" name="message" id="chat_message_input" placeholder="Type a message..." class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl pl-4 pr-16 py-2.5 text-white text-xs outline-none" required autocomplete="off">
                                        <div id="attachmentIndicator" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-[9px] bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded font-bold">
                                            File loaded
                                        </div>
                                    </div>

                                    <x-skew-button type="submit" class="py-2.5 px-5 text-xs font-bold uppercase tracking-wider flex-shrink-0">
                                        Send
                                    </x-skew-button>
                                </form>
                            </div>
                        </div>

                        <!-- Slide-out Side-panel for Group Members details -->
                        <div x-show="showContactInfo" x-transition class="w-72 bg-slate-950 border-l border-slate-850 h-full flex flex-col p-6 space-y-6 z-10 relative" style="display:none;">
                            <div class="flex items-center justify-between border-b border-slate-850 pb-3">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Group Members</h4>
                                <button type="button" @click="showContactInfo = false" class="text-slate-500 hover:text-white cursor-pointer">&times;</button>
                            </div>

                            <div class="space-y-3 overflow-y-auto flex-1">
                                @foreach($activeGroupChat->members as $member)
                                    <div class="flex items-center gap-3">
                                        @if($member->avatar)
                                            <img src="{{ asset('storage/' . $member->avatar) }}" alt="{{ $member->name }}" class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $member->name }}" class="w-8 h-8 rounded-full">
                                        @endif
                                        <div class="min-w-0">
                                            <span class="text-xs font-bold text-white block truncate">{{ $member->name }}</span>
                                            <span class="text-[9px] text-slate-550 block">@<span>{{ $member->username }}</span></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <!-- Modal for Creating a New Group Chat -->
            <div x-show="groupChatCreateOpen" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="bg-slate-900 border border-slate-800 shadow-2xl rounded-2xl p-6 w-full max-w-md animate-scale-up" @click.outside="groupChatCreateOpen = false">
                    <div class="flex items-center justify-between border-b border-slate-850 pb-3 mb-4">
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider">Create Group Chat</h3>
                        <button type="button" @click="groupChatCreateOpen = false" class="text-slate-500 hover:text-white text-lg cursor-pointer">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('group-chats.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Group Chat Name</label>
                            <input type="text" name="group_name" placeholder="e.g. Study Group" class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-2.5 text-white text-xs outline-none" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Members</label>
                            @if($friends->isEmpty())
                                <p class="text-xs text-slate-500 text-center py-4">Add friends first to select them as members.</p>
                            @else
                                <div class="space-y-2 max-h-[180px] overflow-y-auto pr-1">
                                    @foreach($friends as $friend)
                                        <label class="flex items-center justify-between p-2 rounded-xl bg-slate-950/40 border border-slate-850 hover:bg-slate-900/30 transition-all cursor-pointer">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                @if($friend->avatar)
                                                    <img src="{{ asset('storage/' . $friend->avatar) }}" alt="{{ $friend->name }}" class="w-6 h-6 rounded-full object-cover">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($friend->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $friend->name }}" class="w-6 h-6 rounded-full">
                                                @endif
                                                <div class="min-w-0">
                                                    <span class="text-xs font-bold text-white block truncate">{{ $friend->name }}</span>
                                                    <span class="text-[9px] text-slate-500 block truncate">@<span>{{ $friend->username }}</span></span>
                                                </div>
                                            </div>
                                            <input type="checkbox" name="members[]" value="{{ $friend->id }}" class="rounded bg-slate-950 border-slate-800 text-indigo-650 focus:ring-indigo-500">
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-850 flex gap-2 justify-end">
                            <button type="button" @click="groupChatCreateOpen = false" class="px-4 py-2 bg-slate-850 hover:bg-slate-800 border border-slate-700 text-slate-400 hover:text-white rounded-xl text-xs font-bold cursor-pointer">Cancel</button>
                            <x-skew-button type="submit" class="px-5 py-2 text-xs font-bold uppercase tracking-wider">Create Group</x-skew-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Hidden Raw Friends JSON for client side Javascript rendering -->
    <script type="application/json" id="rawFriendsData">
        [
            @foreach($friends as $friend)
                @php
                    $fs = \App\Models\Friendship::where(function($q) use($user, $friend) {
                        $q->where('user_id', $user->id)->where('friend_id', $friend->id);
                    })->orWhere(function($q) use($user, $friend) {
                        $q->where('user_id', $friend->id)->where('friend_id', $user->id);
                    })->first();
                @endphp
                {
                    "id": {{ $friend->id }},
                    "name": "{{ addslashes($friend->name) }}",
                    "username": "{{ addslashes($friend->username) }}",
                    "avatar": "{{ $friend->avatar ? asset('storage/' . $friend->avatar) : '' }}",
                    "avatar_fallback": "https://ui-avatars.com/api/?name={{ urlencode($friend->name) }}&color=7F9CF5&background=EBF4FF",
                    "date_added": "{{ $fs ? $fs->created_at->timestamp : 0 }}",
                    "unfriend_url": "{{ $fs ? route('friendships.destroy', $fs) : '' }}",
                    "chat_url": "{{ route('profile.edit', ['chat_with' => $friend->id, 'tab' => 'messages']) }}",
                    "unread_count": {{ \App\Models\DirectMessage::where('sender_id', $friend->id)->where('receiver_id', $user->id)->where('is_read', false)->count() }}
                }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ]
    </script>

    <script>
        // Alpine or Vanilla JS code to handle Client-side grouping, Drag-and-Drop and Sorting.
        let friendGroups = {
            groups: [],
            mapping: {} // { friend_id: group_name }
        };

        // Load data from localStorage
        function loadGroups() {
            const data = localStorage.getItem('friend_groups_data_{{ $user->id }}');
            if (data) {
                try {
                    friendGroups = JSON.parse(data);
                } catch(e) {
                    console.error("Error loading groups", e);
                }
            }
            if (!friendGroups.groups) friendGroups.groups = [];
            if (!friendGroups.mapping) friendGroups.mapping = {};
        }

        function saveGroups() {
            localStorage.setItem('friend_groups_data_{{ $user->id }}', JSON.stringify(friendGroups));
        }

        // Render groups and friends
        function renderFriendsAndGroups() {
            loadGroups();
            const container = document.getElementById('friendsGroupsContainer');
            if (!container) return;
            container.innerHTML = '';

            const rawData = JSON.parse(document.getElementById('rawFriendsData').textContent);
            
            // Sort rawData based on selection
            const sortMode = document.getElementById('friendSortSelect').value;
            rawData.sort((a, b) => {
                if (sortMode === 'name_asc') {
                    return a.name.localeCompare(b.name);
                } else if (sortMode === 'name_desc') {
                    return b.name.localeCompare(a.name);
                } else if (sortMode === 'date_desc') {
                    return b.date_added - a.date_added;
                }
                return 0;
            });

            // Add unassigned group if not present
            const allGroups = ['Unassigned', ...friendGroups.groups];

            allGroups.forEach(groupName => {
                const isUnassigned = groupName === 'Unassigned';
                
                // Filter friends belonging to this group
                const groupFriends = rawData.filter(f => {
                    const assignedGroup = friendGroups.mapping[f.id] || 'Unassigned';
                    return assignedGroup === groupName;
                });

                // Create group section card
                const groupDiv = document.createElement('div');
                groupDiv.className = "bg-slate-900/40 border border-slate-850 rounded-xl p-3 space-y-2 group-section";
                groupDiv.setAttribute('data-group', groupName);

                // Group Header
                const header = document.createElement('div');
                header.className = "flex items-center justify-between border-b border-slate-850 pb-1.5";
                
                const titleSpan = document.createElement('span');
                titleSpan.className = "text-xs font-bold text-slate-450 uppercase tracking-wider";
                titleSpan.textContent = `${groupName} (${groupFriends.length})`;
                header.appendChild(titleSpan);

                // Delete group button (except Unassigned)
                if (!isUnassigned) {
                    const delBtn = document.createElement('button');
                    delBtn.type = "button";
                    delBtn.className = "text-[10px] font-bold text-rose-400 hover:underline cursor-pointer";
                    delBtn.textContent = "Delete Group";
                    delBtn.onclick = () => deleteGroup(groupName);
                    header.appendChild(delBtn);
                }

                groupDiv.appendChild(header);

                // Drop Area List
                const listContainer = document.createElement('div');
                listContainer.className = "space-y-1.5 min-h-[50px] drop-zone";
                listContainer.setAttribute('data-group', groupName);
                
                // Drag Over and Drop Handlers
                listContainer.ondragover = (e) => {
                    e.preventDefault();
                    listContainer.classList.add('bg-indigo-600/5', 'border-indigo-500/30', 'border', 'border-dashed', 'rounded-lg');
                };
                listContainer.ondragleave = () => {
                    listContainer.classList.remove('bg-indigo-600/5', 'border-indigo-500/30', 'border', 'border-dashed', 'rounded-lg');
                };
                listContainer.ondrop = (e) => {
                    e.preventDefault();
                    listContainer.classList.remove('bg-indigo-600/5', 'border-indigo-500/30', 'border', 'border-dashed', 'rounded-lg');
                    const friendId = e.dataTransfer.getData('text/plain');
                    moveFriendToGroup(friendId, groupName);
                };

                if (groupFriends.length === 0) {
                    const emptyTip = document.createElement('p');
                    emptyTip.className = "text-[10px] text-slate-600 text-center py-4";
                    emptyTip.textContent = "Drag friends here";
                    listContainer.appendChild(emptyTip);
                } else {
                    groupFriends.forEach(friend => {
                        const item = document.createElement('div');
                        item.className = "flex items-center justify-between p-2 rounded-lg border border-transparent hover:border-slate-850 hover:bg-slate-900/30 transition-all cursor-grab active:cursor-grabbing";
                        item.setAttribute('draggable', 'true');
                        item.ondragstart = (e) => {
                            e.dataTransfer.setData('text/plain', friend.id);
                            e.dataTransfer.effectAllowed = 'move';
                        };

                        const detailsLink = document.createElement('a');
                        detailsLink.href = friend.chat_url;
                        detailsLink.className = "flex items-center gap-2 flex-1 min-w-0";

                        const avatarWrap = document.createElement('div');
                        avatarWrap.className = "relative flex-shrink-0";
                        const img = document.createElement('img');
                        img.src = friend.avatar ? friend.avatar : friend.avatar_fallback;
                        img.className = "w-7 h-7 rounded-full object-cover border border-slate-700";
                        avatarWrap.appendChild(img);

                        const statusIndicator = document.createElement('span');
                        statusIndicator.className = "absolute bottom-0 right-0 w-2 h-2 bg-emerald-500 border border-slate-900 rounded-full";
                        avatarWrap.appendChild(statusIndicator);
                        detailsLink.appendChild(avatarWrap);

                        const textWrap = document.createElement('div');
                        textWrap.className = "min-w-0 flex-1";
                        const nameSpan = document.createElement('span');
                        nameSpan.className = "text-xs font-bold text-white block truncate flex items-center gap-1.5";
                        nameSpan.textContent = friend.name;

                        if (friend.unread_count > 0) {
                            const badge = document.createElement('span');
                            badge.className = "px-1.5 py-0.5 text-[8px] font-black bg-rose-500 text-white rounded-full animate-pulse flex-shrink-0";
                            badge.textContent = friend.unread_count;
                            nameSpan.appendChild(badge);
                        }

                        const userSpan = document.createElement('span');
                        userSpan.className = "text-[9px] text-slate-505 block truncate";
                        userSpan.textContent = `@${friend.username}`;
                        textWrap.appendChild(nameSpan);
                        textWrap.appendChild(userSpan);
                        detailsLink.appendChild(textWrap);

                        item.appendChild(detailsLink);

                        // Unfriend Button
                        const unfriendForm = document.createElement('form');
                        unfriendForm.method = "POST";
                        unfriendForm.action = friend.unfriend_url;
                        unfriendForm.onsubmit = () => confirm(`Are you sure you want to unfriend ${friend.name}?`);
                        
                        const tokenInput = document.createElement('input');
                        tokenInput.type = "hidden";
                        tokenInput.name = "_token";
                        tokenInput.value = "{{ csrf_token() }}";
                        unfriendForm.appendChild(tokenInput);

                        const methodInput = document.createElement('input');
                        methodInput.type = "hidden";
                        methodInput.name = "_method";
                        methodInput.value = "DELETE";
                        unfriendForm.appendChild(methodInput);

                        const btn = document.createElement('button');
                        btn.type = "submit";
                        btn.className = "p-1 hover:text-rose-400 text-slate-500 transition-colors cursor-pointer";
                        btn.title = "Unfriend";
                        btn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>`;
                        unfriendForm.appendChild(btn);
                        item.appendChild(unfriendForm);

                        listContainer.appendChild(item);
                    });
                }

                groupDiv.appendChild(listContainer);
                container.appendChild(groupDiv);
            });
        }

        function createNewGroup() {
            const input = document.getElementById('newGroupNameInput');
            const groupName = input.value.trim();
            if (!groupName) return;
            if (groupName.toLowerCase() === 'unassigned') {
                alert("Cannot name a group 'Unassigned'.");
                return;
            }
            loadGroups();
            if (friendGroups.groups.includes(groupName)) {
                alert("Group already exists.");
                return;
            }
            friendGroups.groups.push(groupName);
            saveGroups();
            input.value = '';
            renderFriendsAndGroups();
        }

        function deleteGroup(groupName) {
            if (!confirm(`Are you sure you want to delete "${groupName}"? Your friends in this group will be moved to Unassigned.`)) {
                return;
            }
            loadGroups();
            
            // Remove group
            friendGroups.groups = friendGroups.groups.filter(g => g !== groupName);
            
            // Reset friends mapping belonging to this group back to Unassigned
            for (const key in friendGroups.mapping) {
                if (friendGroups.mapping[key] === groupName) {
                    delete friendGroups.mapping[key];
                }
            }

            saveGroups();
            renderFriendsAndGroups();
        }

        // Move friend to group
        function moveFriendToGroup(friendId, groupName) {
            loadGroups();
            if (groupName === 'Unassigned') {
                delete friendGroups.mapping[friendId];
            } else {
                friendGroups.mapping[friendId] = groupName;
            }
            saveGroups();
            renderFriendsAndGroups();
        }

        function applySorting() {
            renderFriendsAndGroups();
        }

        document.addEventListener('DOMContentLoaded', () => {
            renderFriendsAndGroups();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('chatMessageListContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });

        function updateAttachmentIndicator(input) {
            const indicator = document.getElementById('attachmentIndicator');
            const clip = document.getElementById('attachmentClipBtn');
            if (input.files && input.files.length > 0) {
                indicator.classList.remove('hidden');
                indicator.textContent = input.files[0].name.substring(0, 8) + '...';
                clip.classList.add('border-indigo-500', 'text-indigo-400');
            } else {
                indicator.classList.add('hidden');
                clip.classList.remove('border-indigo-500', 'text-indigo-400');
            }
        }

        function insertEmoji(emoji) {
            const input = document.getElementById('chat_message_input');
            if (input) {
                const start = input.selectionStart || 0;
                const end = input.selectionEnd || 0;
                const text = input.value;
                input.value = text.substring(0, start) + emoji + text.substring(end);
                input.focus();
                const newPos = start + emoji.length;
                input.setSelectionRange(newPos, newPos);
            }
        }
    </script>
</x-app-layout>
