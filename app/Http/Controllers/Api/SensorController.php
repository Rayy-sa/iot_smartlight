<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorLog;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi format data yang masuk
        $request->validate([
            'nilai_cahaya' => 'required|integer',
            'status_lampu' => 'required|integer',
        ]);

        // 2. Simpan ke database PostgreSQL
        $log = SensorLog::create([
            'nilai_cahaya' => $request->nilai_cahaya,
            'status_lampu' => $request->status_lampu,
        ]);

        // 3. Kirim respon balik ke ESP32 bahwa data berhasil disimpan
        return response()->json([
            'status' => 'success',
            'message' => 'Data IoT berhasil disimpan!',
            'data' => $log
        ], 201);
    }
}
