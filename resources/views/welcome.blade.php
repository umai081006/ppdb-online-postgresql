<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem PPDB Online</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-20">
            <a href="{{ url('/') }}" class="text-2xl font-extrabold text-indigo-600 tracking-tight flex items-center gap-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                PPDB Online
            </a>
            <div class="hidden md:flex space-x-8 items-center font-medium text-slate-600">
                <a href="{{ url('/') }}" class="text-indigo-600 transition">Beranda</a>
                <a href="#pendaftaran" class="hover:text-indigo-600 transition">Pendaftaran</a>
                <a href="#pengumuman" class="hover:text-indigo-600 transition">Pengumuman</a>
            </div>
            <div class="flex space-x-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-full text-indigo-600 font-medium hover:bg-indigo-50 transition border border-indigo-100">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main>
        <section class="relative pt-20 pb-32 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-50/50 via-white to-purple-50/50 -z-10"></div>
            <!-- Decorative blobs -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-indigo-200/50 blur-3xl -z-10"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-purple-200/50 blur-3xl -z-10"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <span class="inline-block py-1.5 px-4 rounded-full bg-indigo-50 text-indigo-700 text-sm font-semibold mb-6 border border-indigo-100 shadow-sm">Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</span>
                <h1 class="text-5xl md:text-6xl font-extrabold text-slate-900 tracking-tight mb-8 leading-tight">
                    Langkah Awal Menuju <br class="hidden md:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Masa Depan Gemilang</span>
                </h1>
                <p class="mt-4 text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Sistem Penerimaan Peserta Didik Baru (PPDB) Online hadir untuk memberikan kemudahan, transparansi, dan kecepatan dalam proses pendaftaran siswa baru.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 rounded-full bg-indigo-600 text-white font-semibold text-lg hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-200 transition-all transform hover:-translate-y-1">Buka Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="px-8 py-4 rounded-full bg-indigo-600 text-white font-semibold text-lg hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-200 transition-all transform hover:-translate-y-1">Mulai Daftar Sekarang</a>
                        <a href="#alur" class="px-8 py-4 rounded-full bg-white text-indigo-600 font-semibold text-lg border border-indigo-100 hover:bg-indigo-50 transition-all">Lihat Alur</a>
                    @endauth
                </div>
            </div>
        </section>

        <!-- Alur Section -->
        <section id="alur" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Alur Pendaftaran</h2>
                    <p class="mt-4 text-lg text-slate-600">Tiga langkah mudah untuk bergabung bersama kami</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Step 1 -->
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl hover:shadow-indigo-100 transition duration-300 group">
                        <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 group-hover:rotate-3 transition duration-300">1</div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Pendaftaran Online</h3>
                        <p class="text-slate-600 leading-relaxed text-lg">Buat akun, lengkapi formulir biodata, pilih jalur pendaftaran, dan unggah berkas persyaratan yang dibutuhkan dari rumah.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl hover:shadow-purple-100 transition duration-300 group">
                        <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 group-hover:-rotate-3 transition duration-300">2</div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Proses Seleksi</h3>
                        <p class="text-slate-600 leading-relaxed text-lg">Panitia akan memverifikasi berkas yang masuk. Proses seleksi dilakukan secara transparan berdasarkan kriteria masing-masing jalur.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 hover:shadow-xl hover:shadow-green-100 transition duration-300 group">
                        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 group-hover:scale-110 group-hover:rotate-3 transition duration-300">3</div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Hasil Pengumuman</h3>
                        <p class="text-slate-600 leading-relaxed text-lg">Pantau terus website ini. Hasil seleksi akan diumumkan secara realtime sesuai dengan jadwal yang telah ditetapkan.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cek Status -->
        <section id="pendaftaran" class="py-24 bg-slate-50 relative overflow-hidden">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-100 overflow-hidden border border-slate-100">
                    <div class="p-8 md:p-14">
                        <h2 class="text-3xl font-bold text-center text-slate-900 mb-3">Cek Status Pendaftaran</h2>
                        <p class="text-center text-slate-500 mb-10 text-lg">Sudah mendaftar? Masukkan nomor pendaftaran Anda untuk melacak status terkini.</p>
                        
                        <form action="{{ route('cek-status') }}" method="POST" class="relative max-w-2xl mx-auto">
                            @csrf
                            <input type="text" name="nomor_pendaftaran" placeholder="Contoh: PPDB-2026-0001" class="w-full pl-6 pr-32 py-4 md:py-5 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition text-lg shadow-sm" required>
                            <button type="submit" class="absolute right-2 top-2 bottom-2 bg-indigo-600 text-white px-6 md:px-8 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-md">Lacak</button>
                        </form>

                        @if(session('error'))
                            <div class="mt-6 p-4 max-w-2xl mx-auto bg-red-50 border border-red-100 text-red-600 rounded-xl flex items-start gap-3">
                                <svg class="w-6 h-6 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        @if(session('status_check'))
                            @php $reg = session('status_check'); @endphp
                            <div class="mt-10 p-8 max-w-2xl mx-auto bg-slate-50 rounded-2xl border border-slate-200">
                                <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-2 text-xl">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Hasil Pencarian
                               </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 text-base">
                                    <div>
                                        <p class="text-slate-500 mb-1">Nomor Pendaftaran</p>
                                        <p class="font-bold text-slate-900">{{ $reg->no_pendaftaran }}</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500 mb-1">Nama Pendaftar</p>
                                        <p class="font-bold text-slate-900">{{ $reg->student->user->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500 mb-1">Jalur Pilihan</p>
                                        <p class="font-bold text-slate-900">{{ $reg->jalurPendaftaran->nama_jalur }}</p>
                                    </div>
                                    <div>
                                        <p class="text-slate-500 mb-2">Status Terkini</p>
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold text-white uppercase tracking-wider shadow-sm
                                            @if($reg->status == 'pending') bg-yellow-500
                                            @elseif($reg->status == 'verified') bg-blue-500
                                            @elseif($reg->status == 'accepted') bg-emerald-500
                                            @elseif($reg->status == 'rejected') bg-red-500
                                            @endif
                                        ">
                                            {{ $reg->status }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Pengumuman -->
        @if(isset($announcements) && $announcements->count() > 0)
        <section id="pengumuman" class="py-24 bg-white">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Pengumuman Terbaru</h2>
                    <p class="mt-4 text-lg text-slate-600">Informasi dan update seputar proses seleksi</p>
                </div>
                
                <div class="space-y-6">
                    @foreach($announcements as $announcement)
                        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-lg hover:border-indigo-50 transition duration-300">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                <h3 class="text-2xl font-bold text-indigo-900">{{ $announcement->judul }}</h3>
                                <span class="text-sm font-semibold bg-indigo-50 text-indigo-600 py-1.5 px-4 rounded-full whitespace-nowrap">{{ $announcement->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="text-slate-600 prose prose-indigo max-w-none prose-lg leading-relaxed">
                                {{ $announcement->isi }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 text-slate-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2 text-white font-bold text-xl">
                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 14l9-5-9-5-9 5 9 5z"></path></svg>
                PPDB Online
            </div>
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} PPDB Online. Project Umar Ramadhan.</p>
        </div>
    </footer>

</body>
</html>
