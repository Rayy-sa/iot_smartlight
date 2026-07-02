<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorLog;
use Illuminate\Support\Facades\Http;

class SensorController extends Controller
{
    public function index()
    {
        $currentStatus = SensorLog::latest()->first();
        $historyLogs = SensorLog::latest()->take(10)->get();

        return view('dashboard', compact('currentStatus', 'historyLogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nilai_cahaya' => 'required|integer',
            'status_lampu' => 'required|integer',
        ]);

        // 1. AMBIL DATA TERAKHIR di database SEBELUM menyimpan data baru
        $previousLog = SensorLog::latest()->first();

        // 2. Simpan data baru dari Wokwi
        $log = SensorLog::create([
            'nilai_cahaya' => $request->nilai_cahaya,
            'status_lampu' => $request->status_lampu,
        ]);

        // 3. LOGIKA NOTIFIKASI PINTAR
        // Pesan HANYA dikirim jika belum ada data sama sekali, 
        // ATAU jika status lampu saat ini BERBEDA dengan status lampu sebelumnya.
        if (!$previousLog || $previousLog->status_lampu != $request->status_lampu) {
            
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');

            // Jika berubah menjadi MALAM (Lampu Menyala)
            if ($request->status_lampu == 1) {
                $pesan = "🌙 *Peringatan :* Hari sudah mulai gelap. Lampu jalan telah OTOMATIS DINYALAKAN!\n\n💡 Intensitas Cahaya: " . $request->nilai_cahaya;
            } 
            // Jika berubah menjadi SIANG (Lampu Mati)
            else {
                $pesan = "☀️ *Peringatan :* Hari sudah mulai terang. Lampu jalan telah OTOMATIS DIMATIKAN!\n\n💡 Intensitas Cahaya: " . $request->nilai_cahaya;
            }

            // Kirim ke Telegram
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $pesan,
                'parse_mode' => 'Markdown'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data IoT berhasil disimpan!',
            'data' => $log
        ], 201);
    }
}