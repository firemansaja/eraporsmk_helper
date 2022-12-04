<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SikapController extends Controller {
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
        $pass["sikap"] = erapor5("ref.sikap")
        ->whereNull("sikap_induk")
        ->orderBy("sikap_id");
        return view("nilai_sikap", $pass);
    }
    function nilai($anggota_rombel_id) {
        $pass["anggota_rombel_id"] = $anggota_rombel_id;
        $pass["sikap"] = erapor5("ref.sikap")
        ->whereNull("sikap_induk")
        ->orderBy("sikap_id")
        ->get();
        return view("nilai_sikap_form", $pass);
    }
}
