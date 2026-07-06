<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - LogisTix</title>
   <link rel="stylesheet" href="{{ asset('build/assets/app-DQ3XtoCw.css') }}">
<script src="{{ asset('build/assets/app-DO2nEFzp.js') }}"></script>
    <style>
        [x-cloak] { display: none !important; }
        .page-fade-in { animation: fadeInScale 0.3s ease-out forwards; }
        @keyframes fadeInScale {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="font-sans antialiased bg-white overflow-hidden">

    <div x-data="{ isLeaving: false, showPassword: false, email: '' }" class="flex h-screen w-full">

        <div class="hidden lg:block lg:w-[40%] h-full relative bg-slate-200">
            <img src="https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=1200&auto=format&fit=crop"
                 alt="Pelabuhan"
                 class="w-full h-full object-cover opacity-80 mix-blend-multiply">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="absolute top-10 left-10 z-10">
    <img src="{{ asset('images/logologistix.png') }}" alt="Logo LogisTix" class="h-16 w-auto object-contain">
</div>
        </div>

        <div class="w-full lg:w-[60%] h-full flex items-center justify-center bg-white px-8">
            <div :class="isLeaving ? 'opacity-0 transform translate-y-2' : 'opacity-100'"
                 class="w-full max-w-[520px] flex flex-col transition-all duration-300 ease-in-out page-fade-in">

                <div class="flex w-full border-b border-gray-200 mb-10">
                    <a href="{{ route('login') }}" class="w-1/2 text-center pb-3 border-b-2 border-red-500 text-red-500 font-bold text-[14px]">Masuk</a>
                    <a href="{{ route('register') }}"
                       @click.prevent="isLeaving = true; setTimeout(() => window.location.href = '{{ route('register') }}', 250)"
                       class="w-1/2 text-center pb-3 text-gray-400 hover:text-gray-600 font-bold text-[14px] transition cursor-pointer">Daftar</a>
                </div>

                <h2 class="text-3xl font-black text-[#1a1c3a] tracking-tight mb-2">Selamat Datang</h2>
                <p class="text-gray-400 text-sm mb-8">Masuk ke akun anda</p>

                <!-- Status Pesan -->
                @if (session('status'))
                    <div class="mb-6 p-3 bg-green-50 border border-green-100 text-green-700 rounded-lg text-sm font-semibold">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-3 bg-red-50 border border-red-100 text-red-600 rounded-lg text-sm font-semibold">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-[14px] font-bold text-gray-600 mb-2">Email</label>
                        <input x-model="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-white text-gray-900 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 px-4 py-3.5 shadow-sm transition text-sm outline-none">
                    </div>

                    <div class="mb-5 relative">
                        <label class="block text-[14px] font-bold text-gray-600 mb-2">Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" required class="w-full bg-white text-gray-900 rounded-lg border border-gray-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 px-4 py-3.5 shadow-sm transition pr-10 text-sm font-mono outline-none">
                            <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>



                    <button type="submit" class="w-full py-3.5 bg-white border border-gray-200 rounded-lg text-[14px] font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm mb-6">
                        Masuk
                    </button>
                </form>

                <a href="https://wa.me/628988869125?text=Halo%20Admin%20LogisTix,%20saya%20butuh%20bantuan%20akses." target="_blank" class="w-full flex justify-center items-center gap-2 py-3.5 bg-white border border-gray-200 rounded-lg text-[14px] font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                    Hubungi CS (WhatsApp)
                </a>
            </div>
        </div>
    </div>
</body>
</html>
