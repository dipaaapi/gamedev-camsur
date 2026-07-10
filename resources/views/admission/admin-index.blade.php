<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('Admissions Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        editModalOpen: false, 
        editForm: { id: '', full_name: '', email: '', phone: '', course: '', score: '', req_good_moral: '', req_birth_cert: '', req_card: '', req_picture: '' } 
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Admissions Table -->
            <div class="bg-slate-900/40 border border-slate-800/60 rounded-3xl p-6 backdrop-blur-sm shadow-2xl overflow-hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-white">Course Applications</h3>
                    <span class="text-xs font-semibold text-slate-400 bg-slate-800 px-3 py-1 rounded-full border border-slate-700/60">
                        Total Applications: {{ $admissions->count() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="bg-slate-950/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-4">Applicant</th>
                                <th class="px-6 py-4">Enrollment Number</th>
                                <th class="px-6 py-4">Contact</th>
                                <th class="px-6 py-4">Assessment Score</th>
                                <th class="px-6 py-4">Requirements</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($admissions as $admission)
                                <tr class="hover:bg-slate-800/20 transition-colors">
                                    <!-- Applicant Info -->
                                    <td class="px-6 py-4 font-bold text-white">
                                        {{ $admission->full_name }}
                                    </td>
                                    
                                    <!-- Enrollment number -->
                                    <td class="px-6 py-4 font-mono text-xs text-emerald-400 tracking-wider">
                                        {{ $admission->enrollment_number }}
                                    </td>

                                    <!-- Contact details -->
                                    <td class="px-6 py-4">
                                        <div class="text-xs space-y-0.5">
                                            <div>{{ $admission->email }}</div>
                                            <div class="text-slate-500">{{ $admission->phone }}</div>
                                        </div>
                                    </td>

                                    <!-- Assessment score -->
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-indigo-400">{{ $admission->score }} / 5</span>
                                    </td>

                                    <!-- Requirements status -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5 text-[10px]">
                                            @php
                                                $reqs = $admission->requirements ?? [];
                                            @endphp
                                            
                                            <!-- Good Moral -->
                                            @if(!empty($reqs['good_moral']))
                                                @if(is_string($reqs['good_moral']) && str_contains($reqs['good_moral'], '/'))
                                                    <a href="/storage/{{ $reqs['good_moral'] }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800 text-emerald-400 hover:text-emerald-300 hover:bg-slate-700 transition-all font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Good Moral 📄
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800 text-emerald-400 font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Good Moral
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800/50 text-slate-500">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Good Moral
                                                </span>
                                            @endif

                                            <!-- Birth Cert -->
                                            @if(!empty($reqs['birth_cert']))
                                                @if(is_string($reqs['birth_cert']) && str_contains($reqs['birth_cert'], '/'))
                                                    <a href="/storage/{{ $reqs['birth_cert'] }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800 text-emerald-400 hover:text-emerald-300 hover:bg-slate-700 transition-all font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Birth Cert 📄
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800 text-emerald-400 font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Birth Cert
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800/50 text-slate-500">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Birth Cert
                                                </span>
                                            @endif

                                            <!-- Report Card -->
                                            @if(!empty($reqs['card']))
                                                @if(is_string($reqs['card']) && str_contains($reqs['card'], '/'))
                                                    <a href="/storage/{{ $reqs['card'] }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800 text-emerald-400 hover:text-emerald-300 hover:bg-slate-700 transition-all font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Report Card 📄
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800 text-emerald-400 font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Report Card
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800/50 text-slate-500">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Report Card
                                                </span>
                                            @endif

                                            <!-- 2x2 Photo -->
                                            @if(!empty($reqs['picture']))
                                                @if(is_string($reqs['picture']) && str_contains($reqs['picture'], '/'))
                                                    <div class="relative inline-block group/preview">
                                                        <a href="/storage/{{ $reqs['picture'] }}" target="_blank" class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800 text-emerald-400 hover:text-emerald-300 hover:bg-slate-700 transition-all font-semibold">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                            2x2 Photo 📄
                                                        </a>
                                                        <!-- Hover preview bubble -->
                                                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-32 h-32 bg-slate-950 border-2 border-indigo-500 rounded-xl overflow-hidden shadow-2xl z-50 hidden group-hover/preview:block pointer-events-none">
                                                            <img src="/storage/{{ $reqs['picture'] }}" alt="Photo Preview" class="w-full h-full object-cover">
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800 text-emerald-400 font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        2x2 Photo
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-slate-800/50 text-slate-500">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    2x2 Photo
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Application status -->
                                    <td class="px-6 py-4">
                                        @if($admission->status === 'approved')
                                            <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded">
                                                Approved
                                            </span>
                                        @elseif($admission->status === 'rejected')
                                            <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded">
                                                Rejected
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-widest bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded animate-pulse">
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Action buttons -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <button @click="editForm = { 
                                                id: '{{ $admission->id }}', 
                                                full_name: '{{ addslashes($admission->full_name) }}', 
                                                email: '{{ addslashes($admission->email) }}', 
                                                phone: '{{ addslashes($admission->phone) }}', 
                                                course: '{{ addslashes($admission->course) }}', 
                                                score: '{{ $admission->score }}', 
                                                req_good_moral: '{{ $reqs['good_moral'] ?? '' }}', 
                                                req_birth_cert: '{{ $reqs['birth_cert'] ?? '' }}', 
                                                req_card: '{{ $reqs['card'] ?? '' }}', 
                                                req_picture: '{{ $reqs['picture'] ?? '' }}'
                                            }; editModalOpen = true;" class="px-2.5 py-1 bg-indigo-500/20 hover:bg-indigo-500 text-indigo-400 hover:text-white border border-indigo-500/40 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                Edit
                                            </button>

                                            @if($admission->status !== 'approved')
                                                <form action="{{ route('admission.admin.update', [$admission, 'approved']) }}" method="POST" class="inline m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2.5 py-1 bg-emerald-500/20 hover:bg-emerald-500 text-emerald-400 hover:text-white border border-emerald-500/40 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                        Approve
                                                    </button>
                                                </form>
                                            @endif
                                            @if($admission->status !== 'rejected')
                                                <form action="{{ route('admission.admin.update', [$admission, 'rejected']) }}" method="POST" class="inline m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-2.5 py-1 bg-rose-500/20 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/40 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-500 font-semibold">
                                        No course applications submitted yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit Form Modal Overlay -->
        <div x-show="editModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4" x-transition x-cloak>
            <div @click.outside="editModalOpen = false" class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-xl p-6 md:p-8 shadow-2xl relative overflow-hidden text-slate-100">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                    <h3 class="text-xl font-bold text-white">Edit Admission Details</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form :action="'/dashboard/admissions/' + editForm.id + '/update-all'" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-slate-400 text-xs font-semibold mb-2">Full Name</label>
                            <input type="text" name="full_name" x-model="editForm.full_name" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-400 text-xs font-semibold mb-2">Email Address</label>
                                <input type="email" name="email" x-model="editForm.email" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-slate-400 text-xs font-semibold mb-2">Phone Number</label>
                                <input type="text" name="phone" x-model="editForm.phone" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-400 text-xs font-semibold mb-2">Course</label>
                                <input type="text" name="course" x-model="editForm.course" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-slate-400 text-sm focus:outline-none opacity-70 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-slate-400 text-xs font-semibold mb-2">Assessment Score</label>
                                <input type="number" name="score" min="0" max="5" x-model="editForm.score" class="w-full p-3 bg-slate-950 border border-slate-800 rounded-xl text-white text-sm focus:border-indigo-500 focus:outline-none" required>
                            </div>
                        </div>
                        
                        <!-- Requirements checklist and uploads -->
                        <div>
                            <label class="block text-slate-400 text-xs font-semibold mb-3">Submitted Credentials (Upload files)</label>
                            <div class="space-y-3 bg-slate-950 p-4 rounded-xl border border-slate-800">
                                <!-- Good Moral -->
                                <div class="flex items-center justify-between gap-4 py-1.5 border-b border-slate-900 last:border-0">
                                    <div class="text-xs max-w-[50%]">
                                        <span class="block text-slate-300 font-semibold">Good Moral Certificate</span>
                                        <template x-if="editForm.req_good_moral">
                                            <a :href="'/storage/' + editForm.req_good_moral" target="_blank" class="text-[10px] text-emerald-400 hover:underline block truncate">View File</a>
                                        </template>
                                        <template x-if="!editForm.req_good_moral">
                                            <span class="text-[10px] text-slate-500">No file uploaded</span>
                                        </template>
                                    </div>
                                    <input type="file" name="file_good_moral" class="text-[10px] text-slate-400 max-w-[50%] cursor-pointer">
                                </div>

                                <!-- Birth Cert -->
                                <div class="flex items-center justify-between gap-4 py-1.5 border-b border-slate-900 last:border-0">
                                    <div class="text-xs max-w-[50%]">
                                        <span class="block text-slate-300 font-semibold">PSA Birth Certificate</span>
                                        <template x-if="editForm.req_birth_cert">
                                            <a :href="'/storage/' + editForm.req_birth_cert" target="_blank" class="text-[10px] text-emerald-400 hover:underline block truncate">View File</a>
                                        </template>
                                        <template x-if="!editForm.req_birth_cert">
                                            <span class="text-[10px] text-slate-500">No file uploaded</span>
                                        </template>
                                    </div>
                                    <input type="file" name="file_birth_cert" class="text-[10px] text-slate-400 max-w-[50%] cursor-pointer">
                                </div>

                                <!-- Card -->
                                <div class="flex items-center justify-between gap-4 py-1.5 border-b border-slate-900 last:border-0">
                                    <div class="text-xs max-w-[50%]">
                                        <span class="block text-slate-300 font-semibold">Report Card / Form 137</span>
                                        <template x-if="editForm.req_card">
                                            <a :href="'/storage/' + editForm.req_card" target="_blank" class="text-[10px] text-emerald-400 hover:underline block truncate">View File</a>
                                        </template>
                                        <template x-if="!editForm.req_card">
                                            <span class="text-[10px] text-slate-500">No file uploaded</span>
                                        </template>
                                    </div>
                                    <input type="file" name="file_card" class="text-[10px] text-slate-400 max-w-[50%] cursor-pointer">
                                </div>

                                <!-- Picture -->
                                <div class="flex items-center justify-between gap-4 py-1.5 border-b border-slate-900 last:border-0">
                                    <div class="text-xs max-w-[50%]">
                                        <span class="block text-slate-300 font-semibold">2x2 ID Photo</span>
                                        <template x-if="editForm.req_picture">
                                            <a :href="'/storage/' + editForm.req_picture" target="_blank" class="text-[10px] text-emerald-400 hover:underline block truncate">View File</a>
                                        </template>
                                        <template x-if="!editForm.req_picture">
                                            <span class="text-[10px] text-slate-500">No file uploaded</span>
                                        </template>
                                    </div>
                                    <input type="file" name="file_picture" class="text-[10px] text-slate-400 max-w-[50%] cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3 border-t border-slate-800 pt-4">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-750 text-slate-300 rounded-xl text-xs font-bold transition-all cursor-pointer">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all cursor-pointer">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
