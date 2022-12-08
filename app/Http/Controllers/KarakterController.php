<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KarakterController extends Controller {
    function index() {
        $pass["nomor"] = 1;
        $pass["rombel5"] = erapor5("rombongan_belajar")
            ->where("semester_id", sesi("semester_id"))
            ->where("jenis_rombel", 1)
            ->where("tingkat", "!=", 10)
            ->whereNull("deleted_at")
            ->orderBy("tingkat")
            ->orderBy("jurusan_id")
            ->orderBy("nama")
            ->get();
        $pass["sikap"] = erapor5("ref.sikap")
            ->whereNull("sikap_induk")
            ->orderBy("sikap_id")
            ->get();
        return view("nilai_karakter", $pass);
    }
    function nilai($anggota_rombel_id) {
        $pass["anggota_rombel_id"] = $anggota_rombel_id;
        $pass["pd"] = erapor5("anggota_rombel")
            ->where("anggota_rombel_id", $anggota_rombel_id)
            ->where("semester_id", sesi("semester_id"))
            ->join("peserta_didik", "anggota_rombel.peserta_didik_id", "=", "peserta_didik.peserta_didik_id")
            ->first();
        $pass["rombel"] = erapor5("anggota_rombel")
            ->where("anggota_rombel_id", $anggota_rombel_id)
            ->where("anggota_rombel.semester_id", sesi("semester_id"))
            ->join("rombongan_belajar", "rombongan_belajar.rombongan_belajar_id", "=", "anggota_rombel.rombongan_belajar_id")
            ->where("jenis_rombel", 1)
            ->first();
        $pass["sikap"] = erapor5("ref.sikap")
            ->whereNull("sikap_induk")
            ->orderBy("sikap_id")
            ->get();
        return view("nilai_karakter_form", $pass);
    }
    function simpan(Request $req) {
        if ($req->has("submit")) :
            $rombel = erapor5("anggota_rombel")
            ->whereNull("anggota_rombel.deleted_at")
            ->where("anggota_rombel_id", $req->anggota_rombel_id)
            ->where("anggota_rombel.semester_id", sesi("semester_id"))
            ->join("rombongan_belajar", "rombongan_belajar.rombongan_belajar_id", "=", "anggota_rombel.rombongan_belajar_id")
            ->where("jenis_rombel", 1)
            ->first();
            $cek = erapor5("catatan_ppk")
                ->where("catatan_ppk.anggota_rombel_id", $req->anggota_rombel_id)
                ->join("nilai_karakter", "nilai_karakter.catatan_ppk_id", "=", "catatan_ppk.catatan_ppk_id")
                ->whereNull("catatan_ppk.deleted_at");
            if ($cek->count() == 0) :
                $catatan_ppk_id = getUUID();
                $data_catatan = [
                    "catatan_ppk_id" => $catatan_ppk_id,
                    "sekolah_id" => sesi("sekolah_id"),
                    "anggota_rombel_id" => $req->anggota_rombel_id,
                    "capaian" => $req->capaian_ppk,
                    "created_at" => now(),
                    "updated_at" => now(),
                    "last_sync" => now()
                ];
                erapor5("catatan_ppk")->insert($data_catatan);
                foreach ($req->sikap_id as $sikap_id => $val) {
                    $cek2 = erapor5("nilai_karakter")->where("catatan_ppk_id", $catatan_ppk_id)->where("sikap_id", $sikap_id)->whereNull("deleted_at");
                    $data = [
                        "sekolah_id" => sesi("sekolah_id"),
                        "catatan_ppk_id" => $catatan_ppk_id,
                        "deskripsi" => $val,
                        "updated_at" => now(),
                        "last_sync" => now()
                    ];
                    if ($cek2->count() == 0) :
                        $data = [
                            "nilai_karakter_id" => getUUID(),
                            "sikap_id" => $sikap_id,
                            "created_at" => now(),
                        ];
                        erapor5("nilai_karakter")->insert($data);
                    else:
                        $cek2->update($data);
                    endif;
                }
            else:
                $catatan_ppk = $cek->first();
                $data_catatan = [
                    "sekolah_id" => sesi("sekolah_id"),
                    "anggota_rombel_id" => $req->anggota_rombel_id,
                    "capaian" => $req->capaian_ppk,
                    "updated_at" => now(),
                    "last_sync" => now()
                ];
                $cek->update($data_catatan);
                foreach ($req->sikap_id as $sikap_id => $val2) {
                    $cek2 = erapor5("nilai_karakter")->where("catatan_ppk_id", $catatan_ppk->catatan_ppk_id)->where("sikap_id", $sikap_id)->whereNull("deleted_at");
                    $data = [
                        "sekolah_id" => sesi("sekolah_id"),
                        "catatan_ppk_id" => $catatan_ppk->catatan_ppk_id,
                        "deskripsi" => $val2,
                        "updated_at" => now(),
                        "last_sync" => now()
                    ];
                    if ($cek2->count() == 0) :
                        $data = [
                            "nilai_karakter_id" => getUUID(),
                            "sikap_id" => $sikap_id,
                            "created_at" => now(),
                        ];
                        erapor5("nilai_karakter")->insert($data);
                    else:
                        $cek2->update($data);
                    endif;
                }
            endif;
            return redirect(url("karakter") . "?rombongan_belajar_id=$rombel->rombongan_belajar_id")->with("success", "Nilai Karakter Berhasil Disimpan!");
        endif;
    }
}
