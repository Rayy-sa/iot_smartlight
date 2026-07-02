<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Lampu Jalan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-200 min-h-screen font-sans antialiased p-6">

    <div class="max-w-4xl mx-auto">
        <header class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-blue-400">Monitoring IoT Smart Light</h1>
            <p class="text-slate-400 mt-2">Pemantauan Intensitas Cahaya LDR secara Real-time</p>
        </header>

        @if($currentStatus)
            <div class="flex justify-center mb-10">
                <div class="w-full max-w-sm rounded-2xl shadow-lg p-8 text-center transition-all duration-300 {{ $currentStatus->status_lampu == 1 ? 'bg-indigo-950 border border-indigo-700' : 'bg-blue-900 border border-blue-500' }}">
                    
                    <h2 class="text-xl font-semibold text-slate-300 mb-2">Kondisi Saat Ini</h2>
                    
                    @if($currentStatus->status_lampu == 1)
                        <div class="text-5xl font-bold text-indigo-300 mb-2">MALAM 🌙</div>
                        <p class="text-lg text-indigo-200">Lampu Menyala</p>
                    @else
                        <div class="text-5xl font-bold text-yellow-300 mb-2">SIANG ☀️</div>
                        <p class="text-lg text-blue-200">Lampu Padam</p>
                    @endif

                    <div class="mt-6 inline-block bg-slate-950 px-4 py-2 rounded-lg text-sm text-slate-400">
                        Intensitas Cahaya: <span class="text-white font-mono">{{ $currentStatus->nilai_cahaya }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-800 rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-700">
                    <h3 class="text-lg font-semibold text-white">Riwayat Pemantauan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-700 text-slate-300">
                            <tr>
                                <th class="px-6 py-3">Waktu Masuk</th>
                                <th class="px-6 py-3">Nilai LDR</th>
                                <th class="px-6 py-3">Status Lampu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @foreach($historyLogs as $log)
                            <tr class="hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 text-slate-400">{{ $log->created_at->format('d M Y, H:i:s') }}</td>
                                <td class="px-6 py-4 font-mono">{{ $log->nilai_cahaya }}</td>
                                <td class="px-6 py-4">
                                    @if($log->status_lampu == 1)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-500/20 text-indigo-300">
                                            HIDUP
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">
                                            MATI
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center text-slate-500 mt-20">Belum ada data sensor yang masuk. Coba jalankan simulasi Wokwi.</div>
        @endif

    </div>

<script>
        // Halaman akan dimuat ulang secara otomatis setiap 5 detik (5000 milidetik)
        setTimeout(function() {
            window.location.reload();
        }, 5000);
    </script>
</body>
</html>

