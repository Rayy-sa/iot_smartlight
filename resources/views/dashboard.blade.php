<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Smart Street Light</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white font-sans min-h-screen p-8">
    <div class="max-w-5xl mx-auto">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold text-blue-400 tracking-wide">IoT Smart Street Light</h1>
            <p class="text-slate-400 mt-2 text-lg">Real-time Environmental Monitoring Dashboard</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-700 text-center">
                <h2 class="text-slate-400 text-sm font-semibold mb-3 tracking-wider">STATUS LAMPU</h2>
                @if($currentStatus && $currentStatus->status_lampu == 1)
                    <span class="text-5xl mb-2">💡</span>
                    <p class="text-green-400 font-bold text-2xl drop-shadow-md">MENYALA</p>
                @else
                    <span class="text-5xl mb-2 filter grayscale opacity-50">💡</span>
                    <p class="text-slate-500 font-bold text-2xl">MATI</p>
                @endif
            </div>

            <div class="bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-700 text-center">
                <h2 class="text-slate-400 text-sm font-semibold mb-3 tracking-wider">INTENSITAS CAHAYA</h2>
                <span class="text-4xl mb-2">🌤️</span>
                <p class="text-3xl font-bold text-blue-300 my-1">{{ $currentStatus->nilai_cahaya ?? 0 }}</p>
                <p class="text-xs text-slate-500 font-mono">LUX / ADC Value</p>
            </div>

            <div class="bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-700 text-center">
                <h2 class="text-slate-400 text-sm font-semibold mb-3 tracking-wider">KONDISI CUACA</h2>
                @if($currentStatus && $currentStatus->is_hujan == 1)
                    <span class="text-5xl mb-2">🌧️</span>
                    <p class="text-blue-400 font-bold text-2xl">HUJAN</p>
                @else
                    <span class="text-5xl mb-2">☀️</span>
                    <p class="text-yellow-400 font-bold text-2xl">CERAH</p>
                @endif
            </div>

            <div class="bg-slate-800 p-6 rounded-2xl shadow-lg border border-slate-700 text-center">
                <h2 class="text-slate-400 text-sm font-semibold mb-3 tracking-wider">WAKTU SISTEM</h2>
                <span class="text-5xl mb-2">🕒</span>
                                @php 
                    $waktuAktual = str_pad($currentStatus->jam ?? 0, 4, '0', STR_PAD_LEFT); 
                @endphp
                <p class="text-white font-bold text-3xl my-1">{{ substr($waktuAktual, 0, 2) }}:{{ substr($waktuAktual, 2, 2) }}</p>
                <p class="text-xs text-slate-500 font-mono">WIB (NTP Sync)</p>
            </div>
        </div>

        <div class="bg-slate-800 rounded-2xl shadow-xl border border-slate-700 overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-700 flex justify-between items-center bg-slate-800/50">
                <h3 class="font-bold text-xl text-slate-200">Riwayat Sinkronisasi Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-300">
                    <thead class="bg-slate-900/80 text-slate-400 font-semibold tracking-wide">
                        <tr>
                            <th class="px-8 py-4">Waktu Terima Data</th>
                            <th class="px-8 py-4">Jam (NTP)</th>
                            <th class="px-8 py-4">Nilai LDR</th>
                            <th class="px-8 py-4">Status Cuaca</th>
                            <th class="px-8 py-4">Aktuator Lampu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/50">
                        @forelse($historyLogs as $log)
                        <tr class="hover:bg-slate-700/40 transition-colors duration-200">
                            <td class="px-8 py-4 font-mono text-slate-400">{{ $log->created_at->format('H:i:s') }}</td>
                                                        @php 
                                $waktuLog = str_pad($log->jam, 4, '0', STR_PAD_LEFT); 
                            @endphp
                            <td class="px-8 py-4 font-mono">{{ substr($waktuLog, 0, 2) }}:{{ substr($waktuLog, 2, 2) }}</td>
                            <td class="px-8 py-4 font-mono text-blue-300">{{ $log->nilai_cahaya }}</td>
                            <td class="px-8 py-4">
                                @if($log->is_hujan == 1)
                                    <span class="flex items-center text-blue-400">🌧️ Hujan</span>
                                @else
                                    <span class="flex items-center text-yellow-400">☀️ Cerah</span>
                                @endif
                            </td>
                            <td class="px-8 py-4">
                                @if($log->status_lampu == 1)
                                    <span class="text-green-400">Menyala</span>
                                @else
                                    <span class="text-slate-400">Mati</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-slate-500 italic">Menunggu transmisi data pertama...</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() { window.location.reload(); }, 5000);
    </script>
</body>
</html>