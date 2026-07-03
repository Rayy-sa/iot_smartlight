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
            'is_hujan' => 'required|integer',
            'jam' => 'required|integer',
        ]);

        $previousLog = SensorLog::latest()->first();

        $log = SensorLog::create([
            'nilai_cahaya' => $request->nilai_cahaya,
            'status_lampu' => $request->status_lampu,
            'is_hujan' => $request->is_hujan,
            'jam' => $request->jam,
        ]);

        if (!$previousLog || $previousLog->status_lampu != $request->status_lampu) {
            $token = env('TELEGRAM_BOT_TOKEN');
            $chatId = env('TELEGRAM_CHAT_ID');
            $pesan = "";

            if ($request->status_lampu == 1) { 
                if ($request->jam >= 6 && $request->jam <= 17 && $request->is_hujan == 1) {
                    $pesan = "⚠️ *Peringatan Cuaca Buruk:* Jarak pandang menurun drastis akibat hujan di siang hari. Lampu jalan AKTIF untuk keselamatan lalu lintas.\n\n💡 Cahaya: " . $request->nilai_cahaya;
                } else {
                    $pesan = "🌙 *Transisi Malam:* Waktu malam telah tiba. Lampu jalan telah OTOMATIS DINYALAKAN.\n\n💡 Cahaya: " . $request->nilai_cahaya;
                }
            } else { 
                if ($request->is_hujan == 1) {
                    $pesan = "🌦️ *Informasi Cuaca:* Terdeteksi hujan panas (hujan saat langit cerah). Jarak pandang aman, lampu jalan TETAP DIMATIKAN untuk efisiensi energi.\n\n💡 Cahaya: " . $request->nilai_cahaya;
                } else {
                    $pesan = "☀️ *Transisi Siang:* Hari sudah terang dan cuaca cerah. Lampu jalan telah OTOMATIS DIMATIKAN.\n\n💡 Cahaya: " . $request->nilai_cahaya;
                }
            }

            Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $pesan,
                'parse_mode' => 'Markdown'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $log
        ], 201);
    }
}