<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('Public Info Management') }}
        </h2>
    </x-slot>

    @php
        $user = Auth::user();
        $canManageStaff = $user && in_array($user->users_level, [0, 1, 2]); // Developer, Admin, Teacher
        $canPostStudent = $user && in_array($user->users_level, [0, 1, 2, 3]); // Developer, Admin, Teacher, Student
    @endphp

    <div class="py-12" x-data="managementApp()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3 backdrop-blur-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex flex-col gap-1 backdrop-blur-sm">
                    <div class="flex items-center gap-3 font-semibold mb-1">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Please correct the errors below:</span>
                    </div>
                    <ul class="list-disc list-inside pl-8">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="flex items-center gap-2 bg-slate-950/60 border border-slate-800/80 p-1 rounded-2xl font-mono text-xs font-bold uppercase tracking-wider mb-8 w-max">
                @if($canManageStaff)
                <button @click="changeTab('calendar')" 
                        class="px-5 py-2.5 rounded-xl transition-all cursor-pointer"
                        :class="activeTab === 'calendar' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/20' : 'text-slate-400 hover:text-slate-200'">
                    Calendar Events
                </button>
                <button @click="changeTab('announcements')" 
                        class="px-5 py-2.5 rounded-xl transition-all cursor-pointer"
                        :class="activeTab === 'announcements' ? 'bg-amber-600 text-white shadow-lg shadow-amber-500/20' : 'text-slate-400 hover:text-slate-200'">
                    Announcements
                </button>
                @endif
                @if($canPostStudent)
                <button @click="changeTab('student-board')" 
                        class="px-5 py-2.5 rounded-xl transition-all cursor-pointer"
                        :class="activeTab === 'student-board' ? 'bg-pink-600 text-white shadow-lg shadow-pink-500/20' : 'text-slate-400 hover:text-slate-200'">
                    Student Board
                </button>
                @endif
                @if(in_array(Auth::user()->users_level, [0, 1]))
                <button @click="changeTab('capabilities')" 
                        class="px-5 py-2.5 rounded-xl transition-all cursor-pointer"
                        :class="activeTab === 'capabilities' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-500/20' : 'text-slate-400 hover:text-slate-200'">
                    User Capabilities
                </button>
                @endif
            </div>

            <!-- Management Sections Grid -->
            <div class="grid grid-cols-1 gap-8">
                
                @if($canManageStaff)
                <!-- Calendar Events Management Panel -->
                <div x-show="activeTab === 'calendar'" x-transition class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 backdrop-blur-md relative overflow-hidden shadow-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800/80">
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-xl font-bold text-white tracking-wide">Manage Calendar Events</h2>
                                <p class="text-xs text-slate-500">Add, edit, or remove events displayed on the public calendar</p>
                            </div>
                        </div>
                        <x-skew-button @click="openAddEventModal()" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider">
                            + Add Event
                        </x-skew-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3 px-4">Time</th>
                                    <th class="py-3 px-4">Type</th>
                                    <th class="py-3 px-4">Title</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50 text-sm text-slate-300">
                                <template x-for="event in schedules" :key="event.id">
                                    <tr class="hover:bg-slate-800/20 transition-colors">
                                        <td class="py-3.5 px-4 font-mono text-xs text-slate-400" x-text="event.date"></td>
                                        <td class="py-3.5 px-4" x-text="event.time"></td>
                                        <td class="py-3.5 px-4">
                                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase border"
                                                  :class="event.type === 'announcement' ? 'bg-amber-500/10 text-amber-400 border-amber-500/20' : 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20'"
                                                  x-text="event.type"></span>
                                        </td>
                                        <td class="py-3.5 px-4 font-semibold text-white">
                                            <span x-text="event.title"></span>
                                            <template x-if="event.is_archived">
                                                <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase ml-2">Archived</span>
                                            </template>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <template x-if="!event.is_archived">
                                                    <form :action="`/public-info/events/${event.id}/archive`" method="POST" class="inline m-0">
                                                        @csrf
                                                        <button type="submit" title="Archive Event" class="text-amber-500 hover:text-amber-400 cursor-pointer transition-colors">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </template>
                                                <template x-if="event.is_archived">
                                                    <form :action="`/public-info/events/${event.id}/restore`" method="POST" class="inline m-0">
                                                        @csrf
                                                        <button type="submit" title="Restore Event" class="text-emerald-500 hover:text-emerald-400 cursor-pointer transition-colors">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.5" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </template>
                                                <button @click="openEditEventModal(event)" class="text-slate-400 hover:text-white cursor-pointer transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <form :action="`/public-info/events/${event.id}`" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this event permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-300 cursor-pointer transition-colors">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="schedules.length === 0">
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-500">No events found.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Announcements Management Panel -->
                <div x-show="activeTab === 'announcements'" x-transition class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 backdrop-blur-md relative overflow-hidden shadow-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800/80">
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-xl font-bold text-white tracking-wide">Manage Announcements</h2>
                                <p class="text-xs text-slate-500">Add, edit, or remove public announcements and bulletins</p>
                            </div>
                        </div>
                        <x-skew-button @click="openAddAnnouncementModal()" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider">
                            + Add Announcement
                        </x-skew-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3 px-4">Author</th>
                                    <th class="py-3 px-4">Title</th>
                                    <th class="py-3 px-4">Excerpt</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50 text-sm text-slate-300">
                                <template x-for="ann in announcements" :key="ann.id">
                                    <tr class="hover:bg-slate-800/20 transition-colors">
                                        <td class="py-3.5 px-4 font-mono text-xs text-slate-400" x-text="ann.date"></td>
                                        <td class="py-3.5 px-4 text-xs font-semibold" x-text="ann.author"></td>
                                        <td class="py-3.5 px-4 font-semibold text-white">
                                            <span x-text="ann.title"></span>
                                            <template x-if="ann.is_archived">
                                                <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase ml-2">Archived</span>
                                            </template>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-400 line-clamp-1 max-w-[200px]" x-text="ann.excerpt"></td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-3">
                                                <template x-if="!ann.is_archived">
                                                    <form :action="`/public-info/announcements/${ann.id}/archive`" method="POST" class="inline m-0">
                                                        @csrf
                                                        <button type="submit" title="Archive Announcement" class="text-amber-500 hover:text-amber-400 cursor-pointer transition-colors">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </template>
                                                <template x-if="ann.is_archived">
                                                    <form :action="`/public-info/announcements/${ann.id}/restore`" method="POST" class="inline m-0">
                                                        @csrf
                                                        <button type="submit" title="Restore Announcement" class="text-emerald-500 hover:text-emerald-400 cursor-pointer transition-colors">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.5" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </template>
                                                <button @click="openEditAnnouncementModal(ann)" class="text-slate-400 hover:text-white cursor-pointer transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <form :action="`/public-info/announcements/${ann.id}`" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this announcement permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-300 cursor-pointer transition-colors">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="announcements.length === 0">
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-500">No announcements found.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if($canPostStudent)
                <!-- Student Board Posts Management Panel -->
                <div x-show="activeTab === 'student-board'" x-transition class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 backdrop-blur-md relative overflow-hidden shadow-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800/80">
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-xl font-bold text-white tracking-wide">Student Board Posts</h2>
                                <p class="text-xs text-slate-500">
                                    @if($canManageStaff)
                                        Manage all posts displayed on the student spotlight board
                                    @else
                                        Create posts to showcase achievements or school happenings
                                    @endif
                                </p>
                            </div>
                        </div>
                        <x-skew-button @click="openAddPostModal()" class="px-5 py-2.5 text-xs font-bold uppercase tracking-wider">
                            + Create Post
                        </x-skew-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">Date</th>
                                    <th class="py-3 px-4">Author</th>
                                    <th class="py-3 px-4">Category</th>
                                    <th class="py-3 px-4">Title</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50 text-sm text-slate-300">
                                <template x-for="item in studentBoard" :key="item.id">
                                    <tr class="hover:bg-slate-800/20 transition-colors">
                                        <td class="py-3.5 px-4 font-mono text-xs text-slate-400" x-text="item.date"></td>
                                        <td class="py-3.5 px-4 text-xs font-semibold" x-text="item.author"></td>
                                        <td class="py-3.5 px-4">
                                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase border"
                                                  :class="item.category === 'achievement' ? 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' : 'bg-pink-500/10 text-pink-400 border-pink-500/20'"
                                                  x-text="item.category"></span>
                                        </td>
                                        <td class="py-3.5 px-4 font-semibold text-white">
                                            <span x-text="item.title"></span>
                                            <template x-if="item.is_archived">
                                                <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase ml-2">Archived</span>
                                            </template>
                                        </td>
                                        <td class="py-3.5 px-4 text-right">
                                            <div class="flex items-center justify-end gap-3" x-show="canManageStaff || item.user_id == userId">
                                                <template x-if="!item.is_archived">
                                                    <form :action="`/public-info/posts/${item.id}/archive`" method="POST" class="inline m-0">
                                                        @csrf
                                                        <button type="submit" title="Archive Post" class="text-amber-500 hover:text-amber-400 cursor-pointer transition-colors">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </template>
                                                <template x-if="item.is_archived">
                                                    <form :action="`/public-info/posts/${item.id}/restore`" method="POST" class="inline m-0">
                                                        @csrf
                                                        <button type="submit" title="Restore Post" class="text-emerald-500 hover:text-emerald-400 cursor-pointer transition-colors">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.5" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </template>
                                                <button @click="openEditPostModal(item)" class="text-slate-400 hover:text-white cursor-pointer transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <form :action="`/public-info/posts/${item.id}`" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to delete this post permanently?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-300 cursor-pointer transition-colors">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <template x-if="studentBoard.length === 0">
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-500">No posts found.</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if(in_array(Auth::user()->users_level, [0, 1]))
                <!-- User Capabilities Management Panel -->
                <div x-show="activeTab === 'capabilities'" x-transition class="bg-slate-900/40 border border-slate-800/80 rounded-3xl p-6 backdrop-blur-md relative overflow-hidden shadow-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800/80">
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-xl font-bold text-white tracking-wide">User Posting Capabilities</h2>
                                <p class="text-xs text-slate-500">Assign or revoke permissions for users to post in Calendar, Announcements, or Student Board</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <input type="text" x-model="userSearchQuery" placeholder="Search users by name or email..." class="w-full max-w-md bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-emerald-500">
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 text-xs font-bold uppercase tracking-wider">
                                    <th class="py-3 px-4">User</th>
                                    <th class="py-3 px-4">Role</th>
                                    <th class="py-3 px-4">Calendar Events</th>
                                    <th class="py-3 px-4">Announcements</th>
                                    <th class="py-3 px-4">Student Board</th>
                                    <th class="py-3 px-4">Admissions</th>
                                    <th class="py-3 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50 text-sm text-slate-300">
                                @foreach($users as $usr)
                                    @if($usr->id !== Auth::id())
                                    <tr class="hover:bg-slate-800/20 transition-colors" x-show="userSearchQuery === '' || '{{ strtolower($usr->name) }}'.includes(userSearchQuery.toLowerCase()) || '{{ strtolower($usr->email) }}'.includes(userSearchQuery.toLowerCase())">
                                        <td class="py-3.5 px-4">
                                            <div class="font-semibold text-white">{{ $usr->name }}</div>
                                            <div class="text-xs text-slate-500 font-mono">{{ $usr->email }}</div>
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-xs">
                                            <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-400 border border-slate-700">
                                                {{ $usr->role_name }}
                                            </span>
                                        </td>
                                        <form action="{{ route('public-info.users.capabilities', $usr->id) }}" method="POST">
                                            @csrf
                                            <td class="py-3.5 px-4">
                                                <input type="checkbox" name="can_post_calendar" value="1" {{ $usr->can_post_calendar || in_array($usr->users_level, [0,1]) ? 'checked' : '' }} {{ in_array($usr->users_level, [0,1]) ? 'disabled' : '' }} class="rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900">
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <input type="checkbox" name="can_post_announcement" value="1" {{ $usr->can_post_announcement || in_array($usr->users_level, [0,1]) ? 'checked' : '' }} {{ in_array($usr->users_level, [0,1]) ? 'disabled' : '' }} class="rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900">
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <input type="checkbox" name="can_post_student_board" value="1" {{ $usr->can_post_student_board || in_array($usr->users_level, [0,1]) ? 'checked' : '' }} {{ in_array($usr->users_level, [0,1]) ? 'disabled' : '' }} class="rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900">
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <input type="checkbox" name="can_manage_admissions" value="1" {{ $usr->can_manage_admissions || in_array($usr->users_level, [0,1]) ? 'checked' : '' }} {{ in_array($usr->users_level, [0,1]) ? 'disabled' : '' }} class="rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-slate-900">
                                            </td>
                                            <td class="py-3.5 px-4 text-right">
                                                @if(!in_array($usr->users_level, [0, 1]))
                                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-[10px] font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                                                    Update
                                                </button>
                                                @else
                                                <span class="text-xs text-slate-500 font-semibold italic">Full Access</span>
                                                @endif
                                            </td>
                                        </form>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>

        <!-- Modals for Creation/Editing -->
        @if($canManageStaff)
        <!-- Calendar Event Form Modal -->
        <div x-show="eventModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            
            <div class="bg-slate-900 border border-slate-800 max-w-lg w-full rounded-2xl p-6 shadow-2xl relative" @click.away="eventModalOpen = false">
                <button @click="eventModalOpen = false" class="absolute top-4 right-4 text-slate-500 hover:text-white cursor-pointer transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <h3 class="text-xl font-bold text-white mb-4" x-text="eventFields.id ? 'Edit Calendar Event' : 'Add Calendar Event'"></h3>
                
                <form :action="eventFormAction" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                    @csrf
                    <input type="hidden" name="_method" :value="eventFormMethod">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Date</label>
                        <input type="date" name="date" x-model="eventFields.date" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Type</label>
                        <select name="type" x-model="eventFields.type" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="event">Academic/Event</option>
                            <option value="announcement">Bulletin</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Time</label>
                        <input type="text" name="time" x-model="eventFields.time" placeholder="e.g. 09:00 AM" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Title</label>
                        <input type="text" name="title" x-model="eventFields.title" placeholder="Event Title" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Description</label>
                        <textarea name="description" x-model="eventFields.description" placeholder="Description of the event..." required rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                    </div>

                    <!-- Media Attachments Section -->
                    <div class="border-t border-slate-800/80 pt-4 mt-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Media Attachments</label>
                        <div class="space-y-3">
                            <template x-for="(media, index) in mediaItems" :key="index">
                                <div class="p-3 bg-slate-950/60 border border-slate-800/80 rounded-xl space-y-2 relative">
                                    <button type="button" @click="removeMediaItem(index)" class="absolute top-2 right-2 text-rose-400 hover:text-rose-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Type</label>
                                            <select :name="`media_type[${index}]`" x-model="media.type" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                                                <option value="image">Image</option>
                                                <option value="video">Video</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div x-show="media.type === 'image'" class="space-y-2">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Upload Image</label>
                                            <input type="file" :name="`media_file[${index}]`" class="w-full text-xs text-slate-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-white hover:file:bg-slate-700">
                                        </div>
                                        <div class="text-center text-[10px] font-bold text-slate-600 uppercase">OR</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1" x-text="media.type === 'image' ? 'Image External URL' : 'Video URL'"></label>
                                        <input type="text" :name="`media_url[${index}]`" x-model="media.url" placeholder="https://..." class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                                    </div>
                                </div>
                            </template>
                            
                            <button type="button" @click="addMediaItem()" class="w-full py-2 border border-dashed border-slate-800 hover:border-slate-700 rounded-xl text-xs text-slate-400 hover:text-slate-200 transition-colors flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Media Item
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="eventModalOpen = false" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Announcement Form Modal -->
        <div x-show="announcementFormModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            
            <div class="bg-slate-900 border border-slate-800 max-w-lg w-full rounded-2xl p-6 shadow-2xl relative" @click.away="announcementFormModalOpen = false">
                <button @click="announcementFormModalOpen = false" class="absolute top-4 right-4 text-slate-500 hover:text-white cursor-pointer transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <h3 class="text-xl font-bold text-white mb-4" x-text="announcementFields.id ? 'Edit Announcement' : 'Add Announcement'"></h3>
                
                <form :action="announcementFormAction" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                    @csrf
                    <input type="hidden" name="_method" :value="announcementFormMethod">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Title</label>
                        <input type="text" name="title" x-model="announcementFields.title" placeholder="Announcement Title" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Excerpt</label>
                        <input type="text" name="excerpt" x-model="announcementFields.excerpt" placeholder="Short description/preview" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Content</label>
                        <textarea name="content" x-model="announcementFields.content" placeholder="Full announcement text..." required rows="5" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500"></textarea>
                    </div>
                    
                    
                    <!-- Poll/Survey Option Toggle -->
                    <div class="border-t border-slate-800/80 pt-4 mt-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" x-model="includeAnnouncementPoll" name="include_poll" value="1" class="rounded bg-slate-950 border-slate-800 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Include Poll / Survey</span>
                        </label>
                    </div>

                    <!-- Poll Fields -->
                    <div x-show="includeAnnouncementPoll" x-transition class="space-y-4 pt-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Poll Question</label>
                            <input type="text" name="poll_question" x-model="announcementFields.poll_question" :required="includeAnnouncementPoll" placeholder="e.g. Which project did you like most?" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-500">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 flex justify-between items-center">
                                <span>Options</span>
                                <button type="button" @click="announcementFields.poll_options.push('')" class="text-[10px] text-amber-400 hover:text-amber-300 font-bold uppercase tracking-wider cursor-pointer">+ Add Option</button>
                            </label>
                            <div class="space-y-2">
                                <template x-for="(opt, index) in announcementFields.poll_options" :key="index">
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="poll_options[]" x-model="announcementFields.poll_options[index]" :placeholder="'Option ' + (index + 1)" :required="includeAnnouncementPoll" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-amber-500">
                                        <button type="button" @click="if(announcementFields.poll_options.length > 2) announcementFields.poll_options.splice(index, 1)" class="p-2 text-rose-500 hover:bg-rose-500/10 rounded-lg cursor-pointer" :disabled="announcementFields.poll_options.length <= 2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Media Attachments Section -->
                    <div class="border-t border-slate-800/80 pt-4 mt-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Media Attachments</label>
                        <div class="space-y-3">
                            <template x-for="(media, index) in mediaItems" :key="index">
                                <div class="p-3 bg-slate-950/60 border border-slate-800/80 rounded-xl space-y-2 relative">
                                    <button type="button" @click="removeMediaItem(index)" class="absolute top-2 right-2 text-rose-400 hover:text-rose-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Type</label>
                                            <select :name="`media_type[${index}]`" x-model="media.type" class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                                                <option value="image">Image</option>
                                                <option value="video">Video</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div x-show="media.type === 'image'" class="space-y-2">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Upload Image</label>
                                            <input type="file" :name="`media_file[${index}]`" class="w-full text-xs text-slate-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-white hover:file:bg-slate-700">
                                        </div>
                                        <div class="text-center text-[10px] font-bold text-slate-600 uppercase">OR</div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1" x-text="media.type === 'image' ? 'Image External URL' : 'Video URL'"></label>
                                        <input type="text" :name="`media_url[${index}]`" x-model="media.url" placeholder="https://..." class="w-full bg-slate-900 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-white focus:outline-none focus:border-indigo-500">
                                    </div>
                                </div>
                            </template>
                            
                            <button type="button" @click="addMediaItem()" class="w-full py-2 border border-dashed border-slate-800 hover:border-slate-700 rounded-xl text-xs text-slate-400 hover:text-slate-200 transition-colors flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Media Item
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="announcementFormModalOpen = false" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-xs font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if($canPostStudent)
        <!-- Student Board Post Form Modal -->
        <div x-show="postFormModalOpen" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
            
            <div class="bg-slate-900 border border-slate-800 max-w-lg w-full rounded-2xl p-6 shadow-2xl relative" @click.away="postFormModalOpen = false">
                <button @click="postFormModalOpen = false" class="absolute top-4 right-4 text-slate-500 hover:text-white cursor-pointer transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <h3 class="text-xl font-bold text-white mb-4" x-text="postFields.id ? 'Edit Post' : 'Create Post'"></h3>
                
                <form :action="postFormAction" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                    @csrf
                    <input type="hidden" name="_method" :value="postFormMethod">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Category</label>
                        <select name="category" x-model="postFields.category" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="achievement">Achievement</option>
                            <option value="happening">Happening</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Title</label>
                        <input type="text" name="title" x-model="postFields.title" placeholder="Post Title" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Content</label>
                        <textarea name="content" x-model="postFields.content" placeholder="Write your post content here..." required rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500"></textarea>
                    </div>
                    
                    <!-- Spotlight Option Toggle -->
                    <div class="border-t border-slate-800/80 pt-4 mt-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" x-model="postFields.is_spotlight" name="is_spotlight" value="1" class="rounded bg-slate-950 border-slate-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Display as Student board spotlight (Talk of the campus)</span>
                        </label>
                    </div>
                    
                    <!-- Poll/Survey Option Toggle -->
                    <div class="border-t border-slate-800/80 pt-4 mt-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" x-model="includePostPoll" name="include_poll" value="1" class="rounded bg-slate-950 border-slate-800 text-indigo-500 focus:ring-indigo-500 focus:ring-offset-slate-900">
                            <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">Include Poll / Survey</span>
                        </label>
                    </div>

                    <!-- Poll Fields -->
                    <div x-show="includePostPoll" x-transition class="space-y-4 pt-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Poll Question</label>
                            <input type="text" name="poll_question" x-model="postFields.poll_question" :required="includePostPoll" placeholder="e.g. Rate this activity" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-indigo-500">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2 flex justify-between items-center">
                                <span>Options</span>
                                <button type="button" @click="postFields.poll_options.push('')" class="text-[10px] text-indigo-400 hover:text-indigo-300 font-bold uppercase tracking-wider cursor-pointer">+ Add Option</button>
                            </label>
                            <div class="space-y-2">
                                <template x-for="(opt, index) in postFields.poll_options" :key="index">
                                    <div class="flex items-center gap-2">
                                        <input type="text" name="poll_options[]" x-model="postFields.poll_options[index]" :placeholder="'Option ' + (index + 1)" :required="includePostPoll" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                                        <button type="button" @click="if(postFields.poll_options.length > 2) postFields.poll_options.splice(index, 1)" class="p-2 text-rose-500 hover:bg-rose-500/10 rounded-lg cursor-pointer" :disabled="postFields.poll_options.length <= 2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Media Attachments Section -->
                    <div class="border-t border-slate-800/80 pt-4 mt-4 space-y-4">
                        <!-- Existing Attachments list (useful during edit) -->
                        <template x-if="mediaItems.length > 0">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Existing Attachments</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <template x-for="(media, index) in mediaItems" :key="index">
                                        <div class="p-2.5 bg-slate-950/60 border border-slate-800 rounded-xl relative flex items-center justify-between">
                                            <div class="flex items-center gap-2 truncate">
                                                <input type="hidden" :name="`media_type[${index}]`" :value="media.type">
                                                <input type="hidden" :name="`media_url[${index}]`" :value="media.url">
                                                <input type="hidden" :name="`media_name[${index}]`" :value="media.name">
                                                <input type="hidden" :name="`media_extension[${index}]`" :value="media.extension">
                                                <input type="hidden" :name="`media_size[${index}]`" :value="media.size">
                                                
                                                <span class="text-indigo-400 text-sm">
                                                    <template x-if="media.type === 'image'">🖼️</template>
                                                    <template x-if="media.type === 'video'">🎥</template>
                                                    <template x-if="media.type === 'attachment'">📄</template>
                                                </span>
                                                <span class="text-xs text-slate-300 truncate" x-text="media.name || media.url"></span>
                                            </div>
                                            <button type="button" @click="removeMediaItem(index)" class="text-rose-500 hover:text-rose-400 cursor-pointer">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Upload Images Section with Drag & Drop -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Upload Images</label>
                            
                            <div @dragover.prevent="dragOver = true" 
                                 @dragleave.prevent="dragOver = false" 
                                 @drop.prevent="dragOver = false; handleImageDrop($event)"
                                 :class="dragOver ? 'border-indigo-500 bg-indigo-500/10' : 'border-slate-800 hover:border-slate-700'"
                                 class="border-2 border-dashed rounded-2xl p-6 text-center transition-all cursor-pointer relative"
                                 @click="document.getElementById('post_images_input').click()">
                                 
                                <input type="file" id="post_images_input" name="post_images[]" multiple accept="image/*" class="hidden" @change="handleImageSelection($event)">
                                
                                <svg class="w-8 h-8 text-indigo-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-semibold text-slate-300">Drag & drop multiple images here, or <span class="text-indigo-400 hover:underline">browse</span></span>
                                <span class="block text-[10px] text-slate-500 mt-1">PNG, JPG, JPEG up to 10MB</span>
                            </div>
                            
                            <!-- New Selected Images Preview -->
                            <template x-if="postImagePreviews.length > 0">
                                <div class="grid grid-cols-4 gap-3 mt-4">
                                    <template x-for="(img, idx) in postImagePreviews" :key="idx">
                                        <div class="relative aspect-square rounded-xl overflow-hidden border border-slate-800 bg-slate-950">
                                            <img :src="img.src" class="w-full h-full object-cover">
                                            <button type="button" @click="removeSelectedImage(idx)" class="absolute top-1 right-1 p-1 rounded-full bg-rose-600/90 text-white hover:bg-rose-500 cursor-pointer">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <!-- Attachments / Learning Materials Section -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex justify-between items-center">
                                <span>Learning Materials / Attachments</span>
                                <button type="button" @click="document.getElementById('post_attachments_input').click()" class="text-[10px] text-indigo-400 hover:text-indigo-300 font-bold uppercase tracking-wider cursor-pointer">
                                    + Select Files
                                </button>
                            </label>
                            
                            <input type="file" id="post_attachments_input" name="post_attachments[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" class="hidden" @change="handleAttachmentSelection($event)">
                            
                            <!-- Selected Attachments list -->
                            <template x-if="postAttachmentFiles.length > 0">
                                <div class="space-y-1.5 mt-2">
                                    <template x-for="(file, idx) in postAttachmentFiles" :key="idx">
                                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-950/60 border border-slate-800 text-xs">
                                            <div class="flex items-center gap-2 truncate">
                                                <span class="text-indigo-400 text-sm">📄</span>
                                                <span class="truncate font-semibold text-slate-300 text-[11px]" x-text="file.name"></span>
                                            </div>
                                            <div class="flex items-center gap-2 ml-2">
                                                <span class="text-[9px] text-slate-500" x-text="formatBytes(file.size)"></span>
                                                <button type="button" @click="removeSelectedAttachment(idx)" class="text-rose-500 hover:text-rose-400 cursor-pointer">✕</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="postFormModalOpen = false" class="px-5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-xs font-bold uppercase tracking-wider text-white transition-colors cursor-pointer">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <script>
        function managementApp() {
            return {
                canManageStaff: @json($canManageStaff),
                canPostStudent: @json($canPostStudent),
                userSearchQuery: '',
                userId: @json(Auth::id()),
                
                // Tabs state
                activeTab: new URLSearchParams(window.location.search).get('tab') || (@json($canManageStaff) ? 'calendar' : 'student-board'),

                changeTab(tabName) {
                    this.activeTab = tabName;
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tabName);
                    window.history.replaceState(null, '', url.toString());
                },
                
                // Modal States
                eventModalOpen: false,
                announcementFormModalOpen: false,
                postFormModalOpen: false,
                
                // Poll states
                includeAnnouncementPoll: false,
                includePostPoll: false,
                
                // Drag & Drop / Upload States for posts
                dragOver: false,
                postImageFiles: [],
                postImagePreviews: [],
                postAttachmentFiles: [],

                handleImageDrop(e) {
                    const files = e.dataTransfer.files;
                    const input = document.getElementById('post_images_input');
                    const dt = new DataTransfer();
                    
                    // Add existing selected files first
                    for (let i = 0; i < this.postImageFiles.length; i++) {
                        dt.items.add(this.postImageFiles[i]);
                    }
                    
                    for (let i = 0; i < files.length; i++) {
                        if (files[i].type.startsWith('image/')) {
                            dt.items.add(files[i]);
                            this.postImageFiles.push(files[i]);
                            
                            const file = files[i];
                            const reader = new FileReader();
                            reader.onload = (event) => {
                                this.postImagePreviews.push({ name: file.name, src: event.target.result });
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                    if (input) {
                        input.files = dt.files;
                    }
                },

                handleImageSelection(e) {
                    const input = e.target;
                    const dt = new DataTransfer();
                    
                    for (let i = 0; i < this.postImageFiles.length; i++) {
                        dt.items.add(this.postImageFiles[i]);
                    }
                    
                    const files = input.files;
                    for (let i = 0; i < files.length; i++) {
                        dt.items.add(files[i]);
                        this.postImageFiles.push(files[i]);
                        
                        const file = files[i];
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            this.postImagePreviews.push({ name: file.name, src: event.target.result });
                        };
                        reader.readAsDataURL(file);
                    }
                    input.files = dt.files;
                },

                removeSelectedImage(idx) {
                    this.postImageFiles.splice(idx, 1);
                    this.postImagePreviews.splice(idx, 1);
                    
                    const input = document.getElementById('post_images_input');
                    const dt = new DataTransfer();
                    for (let i = 0; i < this.postImageFiles.length; i++) {
                        dt.items.add(this.postImageFiles[i]);
                    }
                    if (input) {
                        input.files = dt.files;
                    }
                },

                handleAttachmentSelection(e) {
                    const input = e.target;
                    const dt = new DataTransfer();
                    
                    for (let i = 0; i < this.postAttachmentFiles.length; i++) {
                        dt.items.add(this.postAttachmentFiles[i]);
                    }
                    
                    const files = input.files;
                    for (let i = 0; i < files.length; i++) {
                        dt.items.add(files[i]);
                        this.postAttachmentFiles.push(files[i]);
                    }
                    input.files = dt.files;
                },

                removeSelectedAttachment(idx) {
                    this.postAttachmentFiles.splice(idx, 1);
                    
                    const input = document.getElementById('post_attachments_input');
                    const dt = new DataTransfer();
                    for (let i = 0; i < this.postAttachmentFiles.length; i++) {
                        dt.items.add(this.postAttachmentFiles[i]);
                    }
                    if (input) {
                        input.files = dt.files;
                    }
                },

                formatBytes(bytes, decimals = 2) {
                    if (!bytes) return '0 Bytes';
                    const k = 1024;
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                },
                
                // Media States
                mediaItems: [],
                addMediaItem() {
                    this.mediaItems.push({ type: 'image', url: '', name: '', file: null });
                },
                removeMediaItem(index) {
                    this.mediaItems.splice(index, 1);
                },
                
                // Form actions and methods
                eventFormAction: '{{ route('public-info.events.store') }}',
                eventFormMethod: 'POST',
                announcementFormAction: '{{ route('public-info.announcements.store') }}',
                announcementFormMethod: 'POST',
                postFormAction: '{{ route('public-info.posts.store') }}',
                postFormMethod: 'POST',
                
                // Form fields
                eventFields: { id: '', date: '', type: 'event', time: '', title: '', description: '' },
                announcementFields: { id: '', title: '', excerpt: '', content: '', poll_question: '', poll_options: ['', ''] },
                postFields: { id: '', category: 'achievement', title: '', content: '', poll_question: '', poll_options: ['', ''], is_spotlight: false },

                // Data bound from PHP
                schedules: @json($schedules),
                announcements: @json($announcements),
                studentBoard: @json($studentBoard),

                openAddEventModal() {
                    const today = new Date();
                    const dateStr = today.toISOString().split('T')[0];
                    this.eventFields = { id: '', date: dateStr, type: 'event', time: '', title: '', description: '' };
                    this.mediaItems = [];
                    this.eventFormAction = '{{ route('public-info.events.store') }}';
                    this.eventFormMethod = 'POST';
                    this.eventModalOpen = true;
                },
                openEditEventModal(event) {
                    this.eventFields = { ...event };
                    this.mediaItems = event.media ? JSON.parse(JSON.stringify(event.media)) : [];
                    this.eventFormAction = `/public-info/events/${event.id}`;
                    this.eventFormMethod = 'PUT';
                    this.eventModalOpen = true;
                },
                openAddAnnouncementModal() {
                    this.announcementFields = { id: '', title: '', excerpt: '', content: '', poll_question: '', poll_options: ['', ''] };
                    this.mediaItems = [];
                    this.includeAnnouncementPoll = false;
                    this.announcementFormAction = '{{ route('public-info.announcements.store') }}';
                    this.announcementFormMethod = 'POST';
                    this.announcementFormModalOpen = true;
                },
                openEditAnnouncementModal(ann) {
                    this.announcementFields = { 
                        id: ann.id, 
                        title: ann.title, 
                        excerpt: ann.excerpt, 
                        content: ann.content,
                        poll_question: ann.poll_question || '',
                        poll_options: ann.poll_options ? [...ann.poll_options] : ['', '']
                    };
                    this.mediaItems = ann.media ? JSON.parse(JSON.stringify(ann.media)) : [];
                    this.includeAnnouncementPoll = !!ann.poll_question;
                    this.announcementFormAction = `/public-info/announcements/${ann.id}`;
                    this.announcementFormMethod = 'PUT';
                    this.announcementFormModalOpen = true;
                },
                openAddPostModal() {
                    this.postFields = { id: '', category: 'achievement', title: '', content: '', poll_question: '', poll_options: ['', ''], is_spotlight: false };
                    this.mediaItems = [];
                    this.postImageFiles = [];
                    this.postImagePreviews = [];
                    this.postAttachmentFiles = [];
                    this.includePostPoll = false;
                    this.postFormAction = '{{ route('public-info.posts.store') }}';
                    this.postFormMethod = 'POST';
                    this.postFormModalOpen = true;
                    this.$nextTick(() => {
                        const imgInput = document.getElementById('post_images_input');
                        if (imgInput) imgInput.value = '';
                        const attInput = document.getElementById('post_attachments_input');
                        if (attInput) attInput.value = '';
                    });
                },
                openEditPostModal(post) {
                    this.postFields = { 
                        id: post.id, 
                        category: post.category, 
                        title: post.title, 
                        content: post.content,
                        poll_question: post.poll_question || '',
                        poll_options: post.poll_options ? [...post.poll_options] : ['', ''],
                        is_spotlight: !!post.is_spotlight
                    };
                    this.mediaItems = post.media ? JSON.parse(JSON.stringify(post.media)) : [];
                    this.postImageFiles = [];
                    this.postImagePreviews = [];
                    this.postAttachmentFiles = [];
                    this.includePostPoll = !!post.poll_question;
                    this.postFormAction = `/public-info/posts/${post.id}`;
                    this.postFormMethod = 'PUT';
                    this.postFormModalOpen = true;
                    this.$nextTick(() => {
                        const imgInput = document.getElementById('post_images_input');
                        if (imgInput) imgInput.value = '';
                        const attInput = document.getElementById('post_attachments_input');
                        if (attInput) attInput.value = '';
                    });
                }
            }
        }
    </script>
</x-app-layout>
