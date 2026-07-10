<section class="text-slate-300">
    <header class="mb-6">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-slate-400">
            {{ __("Update your account's profile information and email address, and customize your avatar.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Column: Avatar Section -->
            <div class="md:col-span-1 flex flex-col items-center gap-4 p-6 bg-slate-900/40 rounded-2xl border border-slate-800/80">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">{{ __('Profile Photo') }}</label>
                
                <div class="relative w-36 h-36 rounded-full overflow-hidden bg-slate-950 border-2 border-slate-700 hover:border-indigo-500 transition-colors flex items-center justify-center group" id="avatarPreviewContainer">
                    @if($user->avatar)
                        <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $user->name }}" class="absolute inset-0 w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-slate-950/70 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1.5 z-20">
                        <button type="button" class="text-[10px] text-white font-bold bg-indigo-600 hover:bg-indigo-500 px-2.5 py-1 rounded-lg cursor-pointer" onclick="document.getElementById('avatar_file').click()">Change</button>
                        <a id="avatarFullViewLink" href="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}" target="_blank" class="text-[10px] text-slate-300 hover:text-white font-bold underline">Full View</a>
                    </div>
                </div>

                <!-- Input Elements -->
                <input type="file" name="avatar_file" id="avatar_file" class="hidden" accept="image/png, image/jpeg, image/gif" onchange="previewImageFile(this)">
                <input type="hidden" name="avatar_base64" id="avatar_base64">

                <!-- Interactive buttons -->
                <div class="flex flex-col gap-2 w-full">
                    <button type="button" class="w-full px-4 py-2 bg-slate-850 hover:bg-slate-800 border border-slate-700 hover:border-slate-600 rounded-xl text-xs font-bold text-white transition-all cursor-pointer flex items-center justify-center gap-2" onclick="document.getElementById('avatar_file').click()">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        Upload Image
                    </button>
                    <button type="button" id="startCameraBtn" class="w-full px-4 py-2 bg-indigo-600/10 hover:bg-indigo-600/20 border border-indigo-500/30 hover:border-indigo-500/50 rounded-xl text-xs font-bold text-indigo-300 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Capture via Camera
                    </button>
                </div>

                <!-- Live Camera Section (hidden by default) -->
                <div id="cameraContainer" class="hidden w-full space-y-2 mt-2">
                    <div class="relative w-full aspect-square bg-black rounded-xl overflow-hidden border border-slate-700">
                        <video id="cameraVideo" class="w-full h-full object-cover" autoplay playsinline></video>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" id="captureBtn" class="flex-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold cursor-pointer">Capture</button>
                        <button type="button" id="stopCameraBtn" class="flex-1 px-3 py-1.5 bg-rose-950/40 hover:bg-rose-900/50 text-rose-300 border border-rose-900/30 rounded-lg text-xs font-bold cursor-pointer">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Text inputs -->
            <div class="md:col-span-2 space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="name">{{ __('Name') }}</label>
                    <input id="name" name="name" type="text" class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-3 text-white text-sm focus:ring-1 focus:ring-indigo-500 outline-none transition-all" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2 text-xs text-rose-400" :messages="$errors->get('name')" />
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="email">{{ __('Email') }}</label>
                    <input id="email" name="email" type="email" class="w-full bg-slate-950/60 border border-slate-800 focus:border-indigo-500 rounded-xl px-4 py-3 text-white text-sm focus:ring-1 focus:ring-indigo-500 outline-none transition-all" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                    <x-input-error class="mt-2 text-xs text-rose-400" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-3 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                            <p class="text-xs text-amber-300">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="underline hover:text-amber-200 ml-1">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-xs text-emerald-400 font-bold">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-slate-850">
                    <x-skew-button type="submit" class="px-6 py-2.5 text-xs font-bold tracking-wider uppercase transition-all select-none">
                        {{ __('Save Profile') }}
                    </x-skew-button>

                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-xs font-semibold text-emerald-400"
                        >{{ __('Saved successfully.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <script>
        function previewImageFile(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                    document.getElementById('avatarFullViewLink').href = e.target.result;
                    document.getElementById('avatar_base64').value = ''; // clear camera if upload is chosen
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const startCameraBtn = document.getElementById('startCameraBtn');
            const stopCameraBtn = document.getElementById('stopCameraBtn');
            const captureBtn = document.getElementById('captureBtn');
            const cameraContainer = document.getElementById('cameraContainer');
            const cameraVideo = document.getElementById('cameraVideo');
            const avatarPreview = document.getElementById('avatarPreview');
            const avatarBase64Input = document.getElementById('avatar_base64');
            const fileInput = document.getElementById('avatar_file');
            const avatarFullViewLink = document.getElementById('avatarFullViewLink');

            let stream = null;

            startCameraBtn.addEventListener('click', async () => {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ video: { aspectRatio: 1 }, audio: false });
                    cameraVideo.srcObject = stream;
                    cameraContainer.classList.remove('hidden');
                } catch (err) {
                    window.showToast ? window.showToast('Camera access denied: ' + err.message, 'error') : alert('Camera access denied: ' + err.message);
                }
            });

            function stopCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                cameraVideo.srcObject = null;
                cameraContainer.classList.add('hidden');
            }

            stopCameraBtn.addEventListener('click', stopCamera);

            captureBtn.addEventListener('click', () => {
                const canvas = document.createElement('canvas');
                const size = Math.min(cameraVideo.videoWidth, cameraVideo.videoHeight);
                canvas.width = size;
                canvas.height = size;
                const ctx = canvas.getContext('2d');
                const startX = (cameraVideo.videoWidth - size) / 2;
                const startY = (cameraVideo.videoHeight - size) / 2;
                ctx.drawImage(cameraVideo, startX, startY, size, size, 0, 0, size, size);

                const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
                avatarPreview.src = dataUrl;
                avatarFullViewLink.href = dataUrl;
                avatarBase64Input.value = dataUrl;
                
                // Clear file input so base64 takes precedence
                fileInput.value = '';

                stopCamera();
                if (window.showToast) {
                    window.showToast('Photo captured successfully!', 'success');
                }
            });
        });
    </script>
</section>
