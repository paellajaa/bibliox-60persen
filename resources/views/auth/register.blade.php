<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - BIBLIOX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .login-card {
            background: white;
            border-radius: 2.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
        }
        .input-field {
            background-color: #f1f5f9;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .input-field:focus {
            background-color: white;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .btn-primary {
            background: #2563eb;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(37, 99, 235, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6">

    <div class="mb-8 text-center">
        <div class="flex justify-center mb-6">
            <a href="/" class="w-20 h-20 bg-white rounded-[2rem] p-3 shadow-xl border-4 border-slate-50 rotate-3 transition-transform hover:rotate-0 duration-300 flex items-center justify-center cursor-pointer">
                <img src="{{ asset('logo.png') }}" alt="Logo Bibliox" class="w-full h-full object-contain rounded-xl" onerror="this.src='https://placehold.co/100x100?text=BX'">
            </a>
        </div>
        
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tighter uppercase italic">Biblio<span class="text-blue-600">X</span> Register</h1>
        <p class="text-slate-400 text-sm mt-1 font-bold">Silakan lengkapi data diri Anda untuk bergabung</p>
    </div>

    <div class="w-full max-w-[480px] login-card p-10 border border-slate-100">
        
        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl animate-pulse">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-600 text-xs font-bold">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM ACTION --}}
        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap"
                    class="w-full input-field rounded-2xl px-5 py-4 text-slate-700 outline-none font-semibold transition-all">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Alamat Email (Username)</label>
                <input type="email" name="username" value="{{ old('username') }}" required placeholder="Contoh: ryan@email.com"
                    class="w-full input-field rounded-2xl px-5 py-4 text-slate-700 outline-none font-semibold transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full input-field rounded-2xl px-5 py-4 text-slate-700 outline-none font-semibold transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                        class="w-full input-field rounded-2xl px-5 py-4 text-slate-700 outline-none font-semibold transition-all">
                </div>
            </div>

            <button type="submit" class="w-full btn-primary text-white py-5 rounded-2xl font-black text-sm italic uppercase tracking-[0.2em] mt-4 shadow-xl shadow-blue-100">
                Otentikasi Daftar
            </button>
        </form>
    </div>

    <div class="mt-8 text-center">
        <p class="text-slate-400 text-sm font-medium">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Masuk di sini</a></p>
        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.3em] mt-10 italic">© 2026 BiblioX Digital Library</p>
    </div>

</body>
</html>