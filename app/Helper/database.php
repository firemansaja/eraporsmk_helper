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
function getPdByAnggotaRombelID($anggota_rombel_id) {
    return erapor5("anggota_rombel")
    ->join("peserta_didik", "peserta_didik.peserta_didik_id", "=", "anggota_rombel.peserta_didik_id")
    ->where("semester_id", sesi("semester_id"))
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
function nilai_sikap($anggota_rombel_id, $sikap_id, $guru_id) {
    $cek = erapor5("nilai_sikap")
    ->where("anggota_rombel_id", $anggota_rombel_id)
    ->where("sikap_id", $sikap_id)
    ->where("guru_id", $guru_id)
    ->whereNull("deleted_at");
    if ($cek->count() == 0) :
        return (object) [
            "tanggal_sikap" => null,
            "opsi_sikap" => null,
            "uraian_sikap" => null
        ];
    else:
        return $cek->first();
    endif;
}
function nilai_karakter($anggota_rombel_id, $sikap_id) {
    $cek = erapor5("catatan_ppk")
    ->where("anggota_rombel_id", $anggota_rombel_id)
    ->join("nilai_karakter", "nilai_karakter.catatan_ppk_id", "=", "catatan_ppk.catatan_ppk_id")
    ->where("sikap_id", $sikap_id);
    if ($cek->count() == 0) :
        return (object) [
            "deskripsi" => "-",
            "capaian" => "-",
            "sikap_id" => "-"
        ];
    else:
        return $cek->first();
    endif;
}
