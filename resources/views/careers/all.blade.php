<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark scroll-smooth">
<head>
    <x-head title="All Game Dev Careers - GameDev CamSur">
        .gradient-text {
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </x-head>
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden bg-[#0f172a] text-[#f8fafc]">
    <x-loading-screen />
    


    <!-- Navigation Bar -->
    <x-guest-nav />

    <!-- Main Content -->
    <main class="flex-grow pt-32 pb-20 px-6 relative" 
          x-data="{ 
              search: '', 
              selectedGroup: 'All',
              getGroup(category) {
                  let cat = category.toLowerCase();
                  if (cat.includes('leadership') || cat.includes('critical')) return 'Core';
                  if (cat.includes('engineering') || cat.includes('logic') || cat.includes('server') || cat.includes('pipeline') || cat.includes('devops') || cat.includes('database') || cat.includes('build')) return 'Programming';
                  if (cat.includes('design') || cat.includes('narrative') || cat.includes('balance') || cat.includes('validation')) return 'Design';
                  if (cat.includes('visual') || cat.includes('aesthetic') || cat.includes('animation') || cat.includes('styling') || cat.includes('interface') || cat.includes('art')) return 'Art';
                  if (cat.includes('sound') || cat.includes('audio') || cat.includes('composition') || cat.includes('performance')) return 'Audio';
                  return 'Operations';
              }
          }">
        
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-800/50 border border-slate-700/50 text-sm text-brand-light mb-6 backdrop-blur-sm">
                    Top 50 Key Roles & Sectors
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4">Game Dev <span class="gradient-text">Career Path</span></h1>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto">Explore the hierarchy and structural roles that make up modern game development studios worldwide.</p>
            </div>

            <!-- Filters Toolbar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 bg-slate-900/40 p-6 rounded-3xl border border-slate-800/60 backdrop-blur-sm">
                <!-- Search -->
                <div class="relative w-full md:w-80">
                    <input type="text" x-model="search" placeholder="Search positions or tools..." 
                           class="w-full bg-slate-950/30 border border-slate-800 hover:border-slate-700 focus:border-brand-light text-white px-4 py-3 pl-10 rounded-2xl outline-none transition-colors text-sm">
                    <svg class="absolute left-3.5 top-3.5 w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <!-- Tabs -->
                <div class="flex flex-wrap gap-2">
                    <template x-for="grp in ['All', 'Core', 'Programming', 'Design', 'Art', 'Audio', 'Operations']">
                        <button @click="selectedGroup = grp" 
                                class="px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-200 cursor-pointer -skew-x-6 border"
                                :class="selectedGroup === grp 
                                    ? 'bg-indigo-600 border-indigo-500 text-white shadow-[2px_2px_0px_rgba(0,0,0,0.3)]' 
                                    : 'bg-slate-800/40 border-slate-700/30 text-slate-400 hover:bg-slate-800 hover:text-white'"
                                x-text="grp"></button>
                    </template>
                </div>
            </div>

            <!-- Positions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($careers as $career)
                    <div class="bg-slate-900/30 border border-slate-800/50 rounded-3xl p-6 md:p-8 flex flex-col justify-between hover:bg-slate-800/30 transition-all duration-300 hover:border-slate-700/80 shadow-lg relative group"
                         x-show="(selectedGroup === 'All' || getGroup('{{ $career->category }}') === selectedGroup) && 
                                 ('{{ strtolower($career->title) }}'.includes(search.toLowerCase()) || 
                                  '{{ strtolower($career->category) }}'.includes(search.toLowerCase()) || 
                                  '{{ strtolower($career->description) }}'.includes(search.toLowerCase()) ||
                                  '{{ strtolower($career->meta_value_1) }}'.includes(search.toLowerCase()) ||
                                  '{{ strtolower($career->meta_value_2) }}'.includes(search.toLowerCase()))">
                         
                         <div>
                             <!-- Tag & Number -->
                             <div class="flex items-center mb-4">
                                 <span class="text-xs font-bold text-slate-500 group-hover:text-brand-light transition-colors">SECTOR: </span>
                                 <span class="{{ $career->color_class }} font-bold uppercase tracking-wider text-xs ml-2">{{ $career->category }}</span>
                             </div>
                             
                             <!-- Title -->
                             <h3 class="text-2xl font-bold text-white mb-4">{{ $career->title }}</h3>
                             
                             <!-- Description -->
                             <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                 {{ $career->description }}
                             </p>
                         </div>

                         <!-- Skewed Meta Boxes -->
                         <div class="space-y-4 pt-4 border-t border-slate-800/40">
                             <div class="bg-gradient-to-br from-indigo-950/40 to-slate-900/80 -skew-x-12 px-4 py-2.5 shadow-[3px_3px_0px_rgba(0,0,0,0.3)] border-l-4 {{ $career->border_color }}">
                                 <div class="skew-x-12">
                                     <span class="text-[10px] text-indigo-300 block font-bold uppercase tracking-wider mb-0.5">{{ $career->meta_key_1 }}</span>
                                     <span class="text-xs font-semibold text-slate-200">{{ $career->meta_value_1 }}</span>
                                 </div>
                             </div>
                             <div class="bg-gradient-to-br from-indigo-950/40 to-slate-900/80 -skew-x-12 px-4 py-2.5 shadow-[3px_3px_0px_rgba(0,0,0,0.3)] border-l-4 {{ $career->border_color }}">
                                 <div class="skew-x-12">
                                     <span class="text-[10px] text-indigo-300 block font-bold uppercase tracking-wider mb-0.5">{{ $career->meta_key_2 }}</span>
                                     <span class="text-xs font-semibold text-slate-200">{{ $career->meta_value_2 }}</span>
                                 </div>
                             </div>
                         </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Footer -->
    <x-sitemap />
    <x-footer />
    <x-scroll-to-top />
</body>
</html>
