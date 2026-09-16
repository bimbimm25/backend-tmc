<div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-amber-100 overflow-hidden grid grid-cols-1 md:grid-cols-12 my-auto">
    @php
        $emailInputClasses = $errors->has('email')
            ? 'border-rose-500 bg-rose-50/20'
            : 'border-amber-200 bg-amber-50/20';

        $passwordInputClasses = $errors->has('password')
            ? 'border-rose-500 bg-rose-50/20'
            : 'border-amber-200 bg-amber-50/20';
    @endphp
    
    <!-- KOLOM KIRI: Form Login -->
    <div class="md:col-span-7 p-6 sm:p-8 flex flex-col justify-between">
        <div>
            <!-- Header Brand -->
            <div class="flex items-center gap-2 mb-6">
                <div class="w-7 h-7 rounded-full bg-amber-800 flex items-center justify-center text-white text-xs font-bold shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3" />
                    </svg>
                </div>
                <span class="font-bold text-amber-950 text-sm tracking-wide">To Meet Cafe</span>
            </div>

            <!-- Greeting -->
            <div class="mb-6">
                <h1 class="text-xl sm:text-2xl font-bold text-amber-950 tracking-tight">
                    Hello, Welcome Back
                </h1>
                <p class="text-amber-800/70 text-xs mt-1">
                    Hey, welcome back to your special place
                </p>
            </div>

            <!-- Form Login -->
            <form wire:submit.prevent="authenticate" autocomplete="off" class="space-y-4">
                
                <!-- Input Email -->
                <div>
                    <label class="block text-[11px] font-bold text-amber-950 uppercase tracking-wider mb-1">Email Address</label>
                    <input 
                        type="email" 
                        wire:model="email" 
                        autocomplete="new-email"
                        class="w-full border rounded-lg px-3 py-2 text-xs text-amber-950 focus:bg-white focus:border-amber-800 focus:ring-1 focus:ring-amber-800 focus:outline-none transition {{ $emailInputClasses }}"
                        placeholder="Masukkan email"
                        required
                        autofocus

                    {{-- Pesan Peringatan Merah Khusus Email --}}
                    @error('email') 
                        <span class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $message }}
                        </span> 
                    @enderror
                </div>

                <!-- Input Password -->
                <div>
                    <label class="block text-[11px] font-bold text-amber-950 uppercase tracking-wider mb-1">Password</label>
                    <div class="relative flex items-center">
                        <input 
                            type="{{ $showPassword ? 'text' : 'password' }}"
                            wire:model="password"
                            autocomplete="new-password"
                            class="w-full border rounded-lg pl-3 pr-10 py-2 text-xs text-amber-950 focus:bg-white focus:border-amber-800 focus:ring-1 focus:ring-amber-800 focus:outline-none transition {{ $passwordInputClasses }}"
                            placeholder="Masukkan password"
                            required
                        >
                        
                        <!-- Toggle Button Icon Mata -->
                        <button 
                            type="button" 
                            wire:click="togglePassword" 
                            class="absolute right-3 text-amber-800/60 hover:text-amber-950 focus:outline-none p-1 transition cursor-pointer"
                            title="{{ $showPassword ? 'Sembunyikan Password' : 'Tampilkan Password' }}"
                        >
                            @if($showPassword)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            @endif
                        </button>
                    </div>
                    {{-- Pesan Peringatan Merah Khusus Password --}}
                    @error('password') 
                        <span class="text-[11px] text-rose-600 mt-1 flex items-center gap-1 font-medium">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            {{ $message }}
                        </span> 
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            wire:model="remember" 
                            class="w-3.5 h-3.5 rounded border-amber-300 text-amber-800 focus:ring-amber-800"
                        >
                        <span class="text-[11px] font-medium text-amber-800/80">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 bg-amber-800 hover:bg-amber-900 active:bg-amber-950 text-white font-bold rounded-lg text-xs transition duration-150 shadow-md flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span wire:loading.remove wire:target="authenticate">Sign In</span>
                        <span wire:loading wire:target="authenticate" class="text-[11px]">Authenticating...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-[10px] text-amber-800/40">
            © {{ date('Y') }} To Meet Cafe Admin. All rights reserved.
        </div>
    </div>

    <!-- KOLOM KANAN: Banner Logo -->
    <div class="md:col-span-5 bg-linear-to-br from-amber-900 via-amber-800 to-amber-950 p-6 hidden md:flex flex-col items-center justify-center text-center relative overflow-hidden">
        <div class="relative z-10 space-y-4 flex flex-col items-center">
            <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-2xl p-3 flex items-center justify-center border border-white/20 shadow-lg">
                <div class="text-center flex flex-col items-center">
                    <svg class="w-7 h-7 text-white mb-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3" />
                    </svg>
                    <div class="text-white font-extrabold text-xs tracking-wider uppercase">To Meet</div>
                    <div class="text-amber-200 text-[9px] tracking-widest uppercase">Cafe</div>
                </div>
            </div>

            <div class="max-w-50 space-y-1">
                <h3 class="text-sm font-bold text-white tracking-wide">To Meet Cafe</h3>
                <p class="text-[11px] text-amber-200/70 leading-relaxed">
                    Panel manajemen konten
                </p>
            </div>
        </div>
    </div>

</div>