<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorLog;
use Illuminate\Support\Facades\Http; // Wajib ada untuk mengirim pesan Telegram

class SensorController extends Controller
{
    // FUNGSI 1: Untuk menyajikan tampilan UI Dashboard di Web
    public function index()
    {
        $currentStatus = SensorLog::latest()->first();
        $historyLogs = SensorLog::latest()->take(10)->get();

        return view('dashboard', compact('currentStatus', 'historyLogs'));
    }

    // FUNGSI 2: Untuk menerima data dari ESP32 (Wokwi) dan kirim Notif
    public function store(Request $request)
    {
        // 1. Validasi format data yang masuk
        $request->validate([
            'nilai_cahaya' => 'required|integer',
            'status_lampu' => 'required|integer',
        ]);

        // 2. Simpan ke database PostgreSQL (Supabase)
        $log = SensorLog::create([
            'nilai_cahaya' => $request->nilai_cahaya,
            'status_lampu' => $request->status_lampu,
        ]);

        // 3. LOGIKA NOTIFIKASI TELEGRAM
        // Kirim pesan hanya jika lampu menyala (status = 1)
        if ($request->status_lampu == 1) {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');
            $pesan = "🌙 *Peringatan IoT:* Hari sudah mulai gelap. Lampu jalan telah OTOMATIS DINYALAKAN!\n\n💡 Intensitas Cahaya: " . $request->nilai_cahaya;

            // Menembak API Telegram
            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $pesan,
                'parse_mode' => 'Markdown'
            ]);
        }

        // 4. Kirim respon balik ke ESP32
        return response()->json([
            'status' => 'success',
            'message' => 'Data IoT berhasil disimpan!',
            'data' => $log
        ], 201);
    }
}