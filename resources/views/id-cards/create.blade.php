<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <h2 class="font-bold text-2xl text-white leading-tight">
                {{ __('Generate ID Card') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-sm font-semibold">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-slate-800/30 border border-slate-700/50 backdrop-blur-sm shadow-xl sm:rounded-2xl overflow-hidden">
                <div class="p-8">
                    <form action="{{ route('id-cards.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Photo Upload Section -->
                        <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-700/30">
                            <div class="relative w-32 h-32 rounded-2xl overflow-hidden bg-slate-900 border-2 border-dashed border-slate-700 hover:border-indigo-500 transition-colors flex items-center justify-center group cursor-pointer" id="imagePreviewContainer">
                                <img id="imagePreview" src="" alt="Preview" class="absolute inset-0 w-full h-full object-cover hidden">
                                <div id="uploadPlaceholder" class="text-center p-4">
                                    <svg class="w-8 h-8 mx-auto text-slate-500 group-hover:text-indigo-400 transition-colors mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">ID Photo</span>
                                </div>
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <label class="block text-sm font-bold text-white mb-1">ID Picture</label>
                                <p class="text-xs text-slate-400 mb-3">Upload a square aspect ratio portrait. Format: JPG, PNG, GIF. Max size 2MB.</p>
                                <input type="file" name="id_picture" id="id_picture" class="hidden" accept="image/*">
                                <x-cancel-button type="button" onclick="document.getElementById('id_picture').click()" style="padding-top: 0.5rem; padding-bottom: 0.5rem; font-size: 0.75rem;">
                                    Choose Photo
                                </x-cancel-button>
                                <x-cancel-button type="button" id="takePhotoBtn" class="ml-2 hidden" style="padding-top: 0.5rem; padding-bottom: 0.5rem; font-size: 0.75rem;">
                                    Take Photo
                                </x-cancel-button>
                                <x-cancel-button id="clearPhotoBtn" variant="clear" class="ml-2 hidden" style="padding-top: 0.5rem; padding-bottom: 0.5rem; font-size: 0.75rem;">
                                    Clear Photo
                                </x-cancel-button>
                            </div>
                        </div>

                        <!-- Main Form Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">First Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <!-- Middle Name -->
                            <div>
                                <label for="middle_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Middle Name <span class="text-slate-500">(Optional)</span></label>
                                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Last Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- DOB -->
                            <div>
                                <label for="dob" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Date of Birth <span class="text-rose-500">*</span></label>
                                <input type="date" name="dob" id="dob" value="{{ old('dob') }}" required
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <!-- Age -->
                            <div>
                                <label for="age" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Age <span class="text-rose-500">*</span></label>
                                <input type="number" name="age" id="age" value="{{ old('age') }}" required min="0" max="150"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Gender <span class="text-rose-500">*</span></label>
                                <x-custom-select 
                                    name="gender" 
                                    id="gender"
                                    :value="old('gender')" 
                                    placeholder="Select Gender"
                                    :required="true"
                                    :options="[
                                        'Male' => 'Male',
                                        'Female' => 'Female',
                                        'Other' => 'Other'
                                    ]"
                                />
                            </div>
                        </div>

                        <!-- Class, Batch Number, and Student Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="class" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Class <span class="text-rose-500">*</span></label>
                                <input type="text" name="class" id="class" value="{{ old('class') }}" required placeholder="e.g. Game Development"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="batch_number" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Batch Number <span class="text-rose-500">*</span></label>
                                <input type="text" name="batch_number" id="batch_number" value="{{ old('batch_number') }}" required placeholder="e.g. Batch 1"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="student_phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Student Phone <span class="text-rose-500">*</span></label>
                                <input type="text" name="student_phone" id="student_phone" value="{{ old('student_phone') }}" required placeholder="e.g. 09123456789"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Addresses -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Permanent Address -->
                            <div>
                                <label for="permanent_address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Permanent Address <span class="text-rose-500">*</span></label>
                                <textarea name="permanent_address" id="permanent_address" required rows="3"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">{{ old('permanent_address') }}</textarea>
                            </div>

                            <!-- Current Address -->
                            <div>
                                <label for="current_address" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Current Address <span class="text-slate-500">(Optional)</span></label>
                                <textarea name="current_address" id="current_address" rows="3"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                                    placeholder="Leave empty if same as permanent address">{{ old('current_address') }}</textarea>
                            </div>
                        </div>

                        <!-- Emergency Contacts -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="emergency_contact_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Emergency Contact Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name') }}" required placeholder="e.g. John Doe"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="emergency_contact_phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Emergency Contact Phone <span class="text-rose-500">*</span></label>
                                <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" required placeholder="e.g. 09123456789"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Validity Date -->
                            <div>
                                <label for="validity_date" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Validity Date <span class="text-rose-500">*</span></label>
                                <input type="date" name="validity_date" id="validity_date" 
                                    value="{{ old('validity_date', date('Y-m-d', strtotime('+1 year'))) }}" required
                                    max="{{ date('Y-m-d', strtotime('+1 year')) }}"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Status <span class="text-rose-500">*</span></label>
                                <x-custom-select 
                                    name="status" 
                                    id="status"
                                    :value="old('status', 'new')" 
                                    placeholder="Select Status"
                                    :required="true"
                                    :options="[
                                        'new' => 'New',
                                        'renew' => 'Renew',
                                        'archived' => 'Archived',
                                        'graduated' => 'Graduated',
                                        'dismissed' => 'Dismissed',
                                        'deceased' => 'Deceased'
                                    ]"
                                />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end gap-4 pt-6 border-t border-slate-700/30">
                            <x-cancel-button href="{{ route('id-cards.index') }}">
                                Cancel
                            </x-cancel-button>
                            <x-skew-button type="submit" class="px-6 py-2 text-sm font-semibold uppercase tracking-wider">
                                Generate Card
                            </x-skew-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Camera Snap Modal -->
    <div id="cameraModal" class="fixed inset-0 z-[150] flex items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 max-w-md w-full shadow-2xl relative">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Snap ID Portrait
            </h3>
            
            <div class="relative aspect-square w-full rounded-2xl overflow-hidden bg-black border border-slate-700/50 mb-6">
                <video id="cameraVideo" class="absolute inset-0 w-full h-full object-cover" autoplay playsinline></video>
                <div class="absolute inset-4 border-2 border-dashed border-indigo-400/50 rounded-2xl pointer-events-none flex items-center justify-center">
                    <span class="text-xs text-indigo-300/60 font-semibold bg-slate-950/60 px-3 py-1 rounded-full uppercase tracking-wider">Position face here</span>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <x-cancel-button type="button" id="closeCameraBtn">
                    Cancel
                </x-cancel-button>
                <x-skew-button type="button" id="snapCaptureBtn" class="px-5 py-2 text-sm font-semibold uppercase tracking-wider">
                    Capture
                </x-skew-button>
            </div>
        </div>
    </div>

    <!-- Script to handle Image Preview and Auto-Age Calculation -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Picture upload preview
            const fileInput = document.getElementById('id_picture');
            const previewImg = document.getElementById('imagePreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            const container = document.getElementById('imagePreviewContainer');

            container.addEventListener('click', () => fileInput.click());

            const clearBtn = document.getElementById('clearPhotoBtn');

            function clearPhoto() {
                fileInput.value = '';
                previewImg.src = '';
                previewImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
                clearBtn.classList.add('hidden');
            }

            clearBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                clearPhoto();
            });

            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const img = new Image();
                        img.onload = function () {
                            if (img.width !== img.height) {
                                window.showToast('Error: Image must have a square aspect ratio (1:1). Current dimensions: ' + img.width + 'x' + img.height, 'error');
                                clearPhoto();
                            } else {
                                previewImg.src = event.target.result;
                                previewImg.classList.remove('hidden');
                                placeholder.classList.add('hidden');
                                clearBtn.classList.remove('hidden');
                            }
                        };
                        img.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    clearPhoto();
                }
            });

            // Camera Capture logic
            const cameraModal = document.getElementById('cameraModal');
            const cameraVideo = document.getElementById('cameraVideo');
            const closeCameraBtn = document.getElementById('closeCameraBtn');
            const snapCaptureBtn = document.getElementById('snapCaptureBtn');
            const takePhotoBtn = document.getElementById('takePhotoBtn');
            let stream = null;

            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                takePhotoBtn.classList.remove('hidden');
            }

            takePhotoBtn.addEventListener('click', async function (e) {
                e.stopPropagation();
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 640 } },
                        audio: false
                    });
                    cameraVideo.srcObject = stream;
                    cameraModal.classList.remove('hidden');
                } catch (err) {
                    window.showToast('Could not access camera: ' + err.message, 'error');
                }
            });

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                cameraVideo.srcObject = null;
                cameraModal.classList.add('hidden');
            }

            closeCameraBtn.addEventListener('click', stopCamera);

            snapCaptureBtn.addEventListener('click', function () {
                const canvas = document.createElement('canvas');
                const size = Math.min(cameraVideo.videoWidth, cameraVideo.videoHeight);
                canvas.width = size;
                canvas.height = size;
                
                const ctx = canvas.getContext('2d');
                const startX = (cameraVideo.videoWidth - size) / 2;
                const startY = (cameraVideo.videoHeight - size) / 2;
                ctx.drawImage(cameraVideo, startX, startY, size, size, 0, 0, size, size);

                canvas.toBlob(function (blob) {
                    const file = new File([blob], "camera_snap.jpg", { type: "image/jpeg" });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                    
                    fileInput.dispatchEvent(new Event('change'));
                    stopCamera();
                    window.showToast('Photo captured and square-cropped successfully!', 'success');
                }, 'image/jpeg', 0.9);
            });

            // Auto-calculate age from Date of Birth
            const dobInput = document.getElementById('dob');
            const ageInput = document.getElementById('age');

            dobInput.addEventListener('change', function () {
                if (!this.value) return;
                const dob = new Date(this.value);
                const today = new Date();
                
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                ageInput.value = Math.max(0, age);
            });
        });
    </script>
</x-app-layout>
