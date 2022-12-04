<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KetidakhadiranController extends Controller {
    function index() {
        $pass["nomor"] = 1;
        $pass["rombel6"] = erapor6("rombongan_belajar")
        ->where("semester_id", sesi("semester_id"))
        ->where("jenis_rombel", 1)
        ->where("tingkat", 10)
        ->whereNull("deleted_at")
        ->orderBy("tingkat")
        ->orderBy("jurusan_id")
        ->orderBy("nama")
        ->get();
        $pass["rombel5"] = erapor5("rombongan_belajar")
        ->where("semester_id", sesi("semester_id"))
        ->where("jenis_rombel", 1)
        ->where("tingkat", "!=", 10)
        ->whereNull("deleted_at")
        ->orderBy("tingkat")
        ->orderBy("jurusan_id")
        ->orderBy("nama")
        ->get();
        return view("ketidakhadiran", $pass);
    }
    function simpan(Request $req) {
        foreach ($req->anggota_rombel_id as $key => $anggota_rombel_id) {
            $data = [
                "sekolah_id" => sesi("sekolah_id"),
                "anggota_rombel_id" => $anggota_rombel_id,
                "sakit" => $req->sakit[$key],
                "izin" => $req->izin[$key],
                "alpa" => $req->alpa[$key],
                "updated_at" => now(),
                "last_sync" => now()
            ];
            $cek5 = erapor5("anggota_rombel")->where("anggota_rombel_id", $anggota_rombel_id)->count();
            if ($cek5 > 0) {
                $cek_absen5 = erapor5("absensi")->where("anggota_rombel_id", $anggota_rombel_id);
                if ($cek_absen5->count() == 0) :
                    $data["absensi_id"] = getUUID();
                    $data["created_at"] = now();
                    erapor5("absensi")->insert($data);
                else:
                    $cek_absen5->update($data);
                endif;
            }
            $cek6 = erapor6("anggota_rombel")->where("anggota_rombel_id", $anggota_rombel_id)->count();
        }
        return back()->with("success", "Absensi berhasil disimpan");
        // dd($data);
    }
}
