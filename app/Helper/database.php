<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

function erapor5($table) {
    return DB::connection("erapor5")
    ->table($table);
}
function getAnggotaRombelByRombel5($rombongan_belajar_id) {
    return erapor5("anggota_rombel")
    ->join("peserta_didik", "peserta_didik.peserta_didik_id", "=", "anggota_rombel.peserta_didik_id")
    ->where("semester_id", sesi("semester_id"))
    ->where("rombongan_belajar_id", $rombongan_belajar_id)
    ->whereNull("anggota_rombel.deleted_at")
    ->get();
}
function getRombonganBelajarByID5($rombongan_belajar_id) {
    return erapor5("rombongan_belajar")
    ->where("semester_id", sesi("semester_id"))
    ->where("rombongan_belajar_id", $rombongan_belajar_id)
    ->first();
}
function absensi5($anggota_rombel_id) {
    $cek = erapor5("absensi")->where("anggota_rombel_id", $anggota_rombel_id);
    if ($cek->count() == 0) :
        $absen["sakit"] = "";
        $absen["izin"]  = "";
        $absen["alpa"]  = "";
    else:
        $absen["sakit"] = $cek->first()->sakit;
        $absen["izin"]  = $cek->first()->izin;
        $absen["alpa"]  = $cek->first()->alpa;
    endif;
    return (object) $absen;
}
function erapor6($table) {
    return DB::connection("erapor6")
    ->table($table);
}
function getAnggotaRombelByRombel6($rombongan_belajar_id) {
    return erapor6("anggota_rombel")
    ->join("peserta_didik", "peserta_didik.peserta_didik_id", "=", "anggota_rombel.peserta_didik_id")
    ->where("semester_id", sesi("semester_id"))
    ->where("rombongan_belajar_id", $rombongan_belajar_id)
    ->whereNull("anggota_rombel.deleted_at")
    ->get();
}
function getRombonganBelajarByID6($rombongan_belajar_id) {
    return erapor6("rombongan_belajar")
    ->where("semester_id", sesi("semester_id"))
    ->where("rombongan_belajar_id", $rombongan_belajar_id)
    ->first();
}
function absensi6($anggota_rombel_id) {
    $cek = erapor6("absensi")->where("anggota_rombel_id", $anggota_rombel_id);
    if ($cek->count() == 0) :
        $absen["sakit"] = "";
        $absen["izin"]  = "";
        $absen["alpa"]  = "";
    else:
        $absen["sakit"] = $cek->first()->sakit;
        $absen["izin"]  = $cek->first()->izin;
        $absen["alpa"]  = $cek->first()->alpa;
    endif;
    return (object) $absen;
}
function semester() {
    return erapor6("ref.semester")
    ->where("periode_aktif", 1)
    ->first();
}
function getUUID() {
    return Str::uuid();
}
