<x-app-layout>
    <style>
        /* Card flip structure */
        .perspective-container {
            perspective: 1000px;
        }
        .card-inner {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
        }
        .card-flipped {
            transform: rotateY(180deg);
        }
        .card-face {
            backface-visibility: hidden;
        }
        .card-back {
            transform: rotateY(180deg);
        }

        /* Barcode emulation */
        .barcode-bar {
            height: 100%;
            background-color: #000;
            display: inline-block;
        }

        /* Holographic watermark effect */
        .holo-overlay {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0) 30%, rgba(255, 255, 255, 0.08) 50%, rgba(255, 255, 255, 0) 70%);
            background-size: 200% 200%;
            animation: holoFlow 8s linear infinite;
        }
        @keyframes holoFlow {
            0% { background-position: 0% 0%; }
            50% { background-position: 100% 100%; }
            100% { background-position: 0% 0%; }
        }

        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #idCardPrintArea, #idCardPrintArea * {
                visibility: visible;
            }
            #idCardPrintArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 20px;
                background: none !important;
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            .card-inner {
                transform: none !important;
                transform-style: flat !important;
            }
            .card-face {
                position: relative !important;
                backface-visibility: visible !important;
                margin-bottom: 20px;
                border: 1px solid #ddd !important;
                box-shadow: none !important;
                background: #0f172a !important;
                color: #fff !important;
                page-break-inside: avoid;
            }
            .card-back {
                transform: none !important;
            }
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center no-print">
            <div class="flex items-center gap-4">
                <h2 class="font-bold text-2xl text-white leading-tight">
                    {{ __('ID Card Viewer') }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <x-cancel-button href="{{ route('id-cards.index') }}" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('Return') }}
                    </div>
                </x-cancel-button>
                <x-skew-button onclick="window.print()" class="px-5 py-2 text-sm font-semibold">
                    Print ID
                </x-skew-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12 no-print">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="text-center mb-6">
                <p class="text-xs text-slate-400">Click the button above or the card itself to flip between front and back views.</p>
            </div>

            <!-- Dynamic ID Card Container -->
            <div class="perspective-container w-full max-w-sm mx-auto aspect-[1.586/1] cursor-pointer" id="cardContainer">
                <div class="card-inner w-full h-full relative" id="cardInner">
                    
                    <!-- FRONT SIDE OF CARD -->
                    <div class="card-face card-front absolute inset-0 w-full h-full rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-950 border border-indigo-500/30 shadow-[0_15px_35px_rgba(0,0,0,0.5)] flex flex-col justify-between p-6 overflow-hidden">
                        <!-- Hologram Watermark overlay -->
                        <div class="absolute inset-0 holo-overlay pointer-events-none"></div>

                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-indigo-500/20 pb-3 relative z-10">
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('landscape_logo_2.png') }}" alt="Logo" class="h-8 object-contain">
                            </div>
                            <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                {{ $idCard->status }}
                            </span>
                        </div>

                        @php
                            $nameLength = strlen($idCard->full_name);
                            $frontFontSize = 'text-base';
                            if ($nameLength > 30) {
                                $frontFontSize = 'text-[10px]';
                            } elseif ($nameLength > 24) {
                                $frontFontSize = 'text-xs';
                            } elseif ($nameLength > 18) {
                                $frontFontSize = 'text-sm';
                            }

                            $printFontSize = 'text-sm';
                            if ($nameLength > 30) {
                                $printFontSize = 'text-[8px]';
                            } elseif ($nameLength > 24) {
                                $printFontSize = 'text-[10px]';
                            } elseif ($nameLength > 18) {
                                $printFontSize = 'text-xs';
                            }
                        @endphp

                        <!-- Card Body (Photo & Info) -->
                        <div class="flex gap-4 items-center my-auto relative z-10">
                            <!-- Photo -->
                            <div class="w-24 h-24 rounded-xl overflow-hidden bg-slate-800 border-2 border-indigo-500/40 shadow-inner flex-shrink-0">
                                @if ($idCard->id_picture)
                                    <img src="{{ asset($idCard->id_picture) }}" alt="{{ $idCard->full_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-500">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- User Info -->
                            <div class="flex-1 min-w-0">
                                <span class="block text-[8px] text-indigo-400 uppercase tracking-wider font-bold">Full Name</span>
                                <h2 class="{{ $frontFontSize }} font-black text-white leading-tight mb-1 whitespace-normal break-words">{{ $idCard->full_name }}</h2>
                                
                                <span class="block text-[8px] text-indigo-400 uppercase tracking-wider font-bold">ID Number</span>
                                <p class="text-sm font-mono text-white font-bold leading-tight mb-1">{{ $idCard->id_number }}</p>
                                
                                <span class="block text-[8px] text-indigo-400 uppercase tracking-wider font-bold">Validity</span>
                                <p class="text-xs font-semibold text-slate-200 leading-tight">{{ $idCard->validity_date->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="flex justify-between items-end border-t border-indigo-500/20 pt-2 relative z-10 text-[8px] text-indigo-300">
                            <div>
                                <span class="uppercase tracking-widest font-semibold block text-[6px]">Class</span>
                                <span class="font-bold text-white block truncate max-w-[120px]">{{ $idCard->class }}</span>
                            </div>
                            <div>
                                <span class="uppercase tracking-widest font-semibold block text-[6px]">Signature</span>
                                <div class="h-3.5 border-b border-dashed border-slate-500 w-16"></div>
                            </div>
                            <div class="text-right">
                                <span class="uppercase tracking-widest font-semibold block text-[6px]">Batch</span>
                                <span class="font-bold text-white">{{ $idCard->batch_number }}</span>
                            </div>
                        </div>

                    </div>

                    <!-- BACK SIDE OF CARD -->
                    <div class="card-face card-back absolute inset-0 w-full h-full rounded-2xl bg-gradient-to-br from-slate-900 to-indigo-950 border border-indigo-500/30 shadow-[0_15px_35px_rgba(0,0,0,0.5)] flex flex-col justify-between p-6 overflow-hidden">
                        
                        <!-- Back Info -->
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-[9px] text-slate-300">
                            <div>
                                <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Date of Birth</span>
                                <span class="font-semibold text-white">{{ $idCard->dob->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Gender & Age</span>
                                <span class="font-semibold text-white">{{ $idCard->gender }} ({{ $idCard->age }} yrs)</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Student Phone</span>
                                <span class="font-semibold text-white font-mono">{{ $idCard->student_phone }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Permanent Address</span>
                                <span class="font-semibold text-white block truncate" title="{{ $idCard->permanent_address }}">{{ $idCard->permanent_address }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Current Address</span>
                                <span class="font-semibold text-white block truncate" title="{{ $idCard->current_address ?? $idCard->permanent_address }}">{{ $idCard->current_address ?? 'Same as Permanent Address' }}</span>
                            </div>
                        </div>

                        <!-- Terms, Emergency Contact & QR code -->
                        <div class="mt-2 pt-2 border-t border-indigo-500/20 flex items-center justify-between gap-3">
                            <div class="flex-1 text-left">
                                <p class="text-[6px] text-indigo-400 uppercase tracking-wider font-bold">In Case of Emergency:</p>
                                <p class="text-[8px] font-black text-white leading-tight">{{ $idCard->emergency_contact_name }}</p>
                                <p class="text-[7px] text-slate-300 font-mono leading-tight">{{ $idCard->emergency_contact_phone }}</p>
                                
                                <p class="text-[5px] text-slate-400 mt-1 uppercase tracking-widest leading-normal">Property of GameDev CamSur. If found, please return to academy office.</p>
                            </div>
                            
                            <!-- QR Code -->
                            <div class="flex flex-col items-center gap-1 flex-shrink-0">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('id-cards.show', $idCard)) }}" alt="QR Code" class="w-12 h-12 bg-white p-0.5 rounded-lg border border-slate-700">
                                <span class="text-[6px] font-mono text-indigo-400 font-bold">{{ $idCard->id_number }}</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- PRINT VIEW AREA (Visible only during window.print()) -->
    <div id="idCardPrintArea" class="hidden">
        <!-- Front Face -->
        <div class="w-[3.375in] h-[2.125in] rounded-2xl bg-slate-950 border border-slate-800 flex flex-col justify-between p-6 overflow-hidden relative">
            <div class="flex items-center justify-between border-b border-indigo-500/20 pb-3">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('landscape_logo_2.png') }}" alt="Logo" class="h-8 object-contain">
                </div>
                <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">{{ $idCard->status }}</span>
            </div>
            <div class="flex gap-4 items-center my-auto">
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-800 border-2 border-indigo-500/40 flex-shrink-0">
                    @if ($idCard->id_picture)
                        <img src="{{ asset($idCard->id_picture) }}" alt="{{ $idCard->full_name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-500">
                            <span class="text-[10px]">Photo</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <span class="block text-[8px] text-indigo-400 uppercase tracking-wider font-bold">Full Name</span>
                    <h2 class="{{ $printFontSize }} font-black text-white leading-tight mb-1 whitespace-normal break-words">{{ $idCard->full_name }}</h2>
                    <span class="block text-[8px] text-indigo-400 uppercase tracking-wider font-bold">ID Number</span>
                    <p class="text-xs font-mono text-white font-bold leading-tight mb-1">{{ $idCard->id_number }}</p>
                    <span class="block text-[8px] text-indigo-400 uppercase tracking-wider font-bold">Validity</span>
                    <p class="text-[10px] font-semibold text-slate-200 leading-tight">{{ $idCard->validity_date->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex justify-between items-end border-t border-indigo-500/20 pt-2 text-[8px] text-indigo-300">
                <div>
                    <span class="uppercase tracking-widest font-semibold block text-[6px]">Class</span>
                    <span class="font-bold text-white block truncate max-w-[120px]">{{ $idCard->class }}</span>
                </div>
                <div>
                    <span class="uppercase tracking-widest font-semibold block text-[6px]">Signature</span>
                    <div class="h-3.5 border-b border-dashed border-slate-500 w-16"></div>
                </div>
                <div class="text-right">
                    <span class="uppercase tracking-widest font-semibold block text-[6px]">Batch</span>
                    <span class="font-bold text-white">{{ $idCard->batch_number }}</span>
                </div>
            </div>
        </div>

        <!-- Back Face -->
        <div class="w-[3.375in] h-[2.125in] rounded-2xl bg-slate-950 border border-slate-800 flex flex-col justify-between p-6 overflow-hidden relative">
            <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-[9px] text-slate-300">
                <div>
                    <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Date of Birth</span>
                    <span class="font-semibold text-white">{{ $idCard->dob->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Gender</span>
                    <span class="font-semibold text-white">{{ $idCard->gender }}</span>
                </div>
                <div class="col-span-2">
                    <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Student Phone</span>
                    <span class="font-semibold text-white font-mono">{{ $idCard->student_phone }}</span>
                </div>
                <div class="col-span-2">
                    <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Permanent Address</span>
                    <span class="font-semibold text-white block truncate">{{ $idCard->permanent_address }}</span>
                </div>
                <div class="col-span-2">
                    <span class="block text-[6px] text-indigo-400 uppercase tracking-wider font-bold">Current Address</span>
                    <span class="font-semibold text-white block truncate">{{ $idCard->current_address ?? 'Same as Permanent Address' }}</span>
                </div>
            </div>
            <div class="pt-2 border-t border-indigo-500/20 flex items-center justify-between gap-3">
                <div class="flex-1 text-left">
                    <p class="text-[6px] text-indigo-400 uppercase tracking-wider font-bold">In Case of Emergency:</p>
                    <p class="text-[8px] font-black text-white leading-tight">{{ $idCard->emergency_contact_name }}</p>
                    <p class="text-[7px] text-slate-300 font-mono leading-tight">{{ $idCard->emergency_contact_phone }}</p>
                    
                    <p class="text-[5px] text-slate-400 mt-1 uppercase tracking-widest leading-normal">Property of GameDev CamSur. If found, please return to academy office.</p>
                </div>
                <div class="flex flex-col items-center gap-1 flex-shrink-0">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode(route('id-cards.show', $idCard)) }}" alt="QR Code" class="w-12 h-12 bg-white p-0.5 rounded-lg border border-slate-700">
                    <span class="text-[6px] font-mono text-indigo-400 font-bold">{{ $idCard->id_number }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to control flippers -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cardInner = document.getElementById('cardInner');
            const flipButton = document.getElementById('flipButton');
            const cardContainer = document.getElementById('cardContainer');

            function toggleFlip() {
                cardInner.classList.toggle('card-flipped');
            }

            cardContainer.addEventListener('click', toggleFlip);
            flipButton.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleFlip();
            });
        });
    </script>
</x-app-layout>
