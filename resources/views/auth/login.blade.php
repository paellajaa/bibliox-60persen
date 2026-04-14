<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - BIBLIOX Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        @keyframes slideDown {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slideDown { animation: slideDown 0.4s ease-out; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#F8FAFC] flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-md">
        <div class="text-center mb-10">
            <div class="flex justify-center mb-6">
                <a href="/" class="w-20 h-20 bg-white rounded-[2rem] p-3 shadow-xl border-4 border-slate-50 -rotate-6 transition-transform hover:rotate-0 duration-300 flex items-center justify-center cursor-pointer">
                    <img src="{{ asset('logo.png') }}" alt="Logo Bibliox" class="w-full h-full object-contain rounded-xl" onerror="this.src='https://placehold.co/100x100?text=BX'">
                </a>
            </div>
            
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tighter uppercase">BIBLIOX LOGIN</h1>
            <p class="text-slate-400 mt-2 font-medium">Silakan masuk menggunakan ID Pengenal</p>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl animate-slideDown text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Notifikasi Error --}}
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-600 rounded-r-xl animate-slideDown text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-10 rounded-[40px] shadow-xl shadow-slate-200/50 border border-slate-100">
            {{-- Error Validasi Laravel --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-600 rounded-r-xl animate-slideDown text-sm font-bold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 ml-1">ID PENGENAL (Email)</label>
                    <input type="text" name="pengenal" value="{{ old('pengenal') }}" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 outline-none transition-all font-semibold" 
                        placeholder="Contoh: 2026001" required autofocus>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-3 ml-1">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Kata Sandi</label>
                    </div>
                    <input type="password" name="password" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-slate-900 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 outline-none transition-all font-semibold" 
                        placeholder="••••••••" required>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-2 block text-sm text-slate-500 font-medium">Ingat saya</label>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-blue-600/30 hover:scale-[1.02] active:scale-95 text-lg italic tracking-wider uppercase">
                    Otentikasi Masuk
                </button>
            </form>
        </div>

        <div class="text-center mt-8">
            <p class="text-slate-400 font-medium">Belum punya akun? 
                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Daftar Sekarang</a>
            </p>
        </div>
        
        <p class="text-center mt-10 text-slate-300 font-bold text-[10px] tracking-[0.2em] uppercase">
            © 2026 BIBLIOX DIGITAL LIBRARY
        </p>
    </div>
</body>
</html>