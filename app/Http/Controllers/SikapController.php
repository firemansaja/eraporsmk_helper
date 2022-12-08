<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $pass["daftar_opsi"] = ["Negatif", "Positif"];
        return view("nilai_sikap_form", $pass);
    }
    function simpan(Request $req) {
        // if ($req->has("simpan")) :
            $rombel = erapor5("anggota_rombel")
            ->whereNull("anggota_rombel.deleted_at")
            ->where("anggota_rombel_id", $req->anggota_rombel_id)
            ->where("anggota_rombel.semester_id", sesi("semester_id"))
            ->join("rombongan_belajar", "rombongan_belajar.rombongan_belajar_id", "=", "anggota_rombel.rombongan_belajar_id")
            ->where("jenis_rombel", 1)
            ->first();
            foreach ($req->tanggal as $sikap_id => $tanggal) {
                $cek = erapor5("nilai_sikap")
                ->where("anggota_rombel_id", $req->anggota_rombel_id)
                ->where("sikap_id", $sikap_id)
                ->where("guru_id", $rombel->guru_id)
                ->whereNull("deleted_at");
                $data = [
                    "tanggal_sikap" => $tanggal,
                    "sikap_id" => $sikap_id,
                    "opsi_sikap" => $req->opsi[$sikap_id],
                    "uraian_sikap" => $req->deskripsi[$sikap_id],
                    "updated_at" => now(),
                    "last_sync" => now(),
                ];
                if ($cek->count() == 0) :
                    $data["nilai_sikap_id"] = getUUID();
                    $data["guru_id"] = $rombel->guru_id;
                    $data["sekolah_id"] = sesi("sekolah_id");
                    $data["anggota_rombel_id"] = $req->anggota_rombel_id;
                    $data["created_at"] = now();
                    erapor5("nilai_sikap")->insert($data);
                else:
                    $cek->update($data);
                endif;
            }
            return redirect(url("sikap") . "?rombongan_belajar_id=$rombel->rombongan_belajar_id")->with("success", "Nilai Sikap Berhasil Disimpan!");
        // endif;
    }
    function template($rombongan_belajar_id) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(public_path("template-excel/k13_rev-template_nilai_sikap.xlsx"));
        $sheet = $spreadsheet->getActiveSheet();

        //START
        $baris = 7;
        $nomor = 1;

        $anggota_rombel = erapor5("anggota_rombel")->where("rombongan_belajar_id", $rombongan_belajar_id)
        ->join("peserta_didik", "peserta_didik.peserta_didik_id", "=", "anggota_rombel.peserta_didik_id")
        ->where("semester_id", sesi("semester_id"))
        ->whereNull("anggota_rombel.deleted_at")
        ->orderBy("nama")
        ->get();

        $rombongan_belajar = getRombonganBelajarByID5($rombongan_belajar_id);
        $tahun_pelajaran = semester()->tahun_ajaran_id . "/" . (semester()->tahun_ajaran_id + 1);
        $sheet->getCell("B2")->setValue("TAHUN PELAJARAN $tahun_pelajaran");

        foreach ($anggota_rombel as $pd) {
            $rombel = erapor5("rombongan_belajar")->where("rombongan_belajar_id", $pd->rombongan_belajar_id)
                ->where("semester_id", sesi("semester_id"))
                ->whereNull("deleted_at")
                ->first();
            $sheet->getCell("A" . $baris)->setValue($pd->anggota_rombel_id);
            $sheet->getCell("B" . $baris)->setValue($nomor);
            $sheet->getCell("C" . $baris)->setValue($pd->no_induk);
            $sheet->getCell("D" . $baris)->setValue($pd->nisn);
            $sheet->getCell("E" . $baris)->setValue(proper($pd->nama));
            $sheet->getCell("F" . $baris)->setValue($rombel->nama);
            $nomor++;
            $baris++;
        }
        //END

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="template-sikap_kelas_' . $rombongan_belajar->nama . '.xlsx"');
        $writer->save('php://output');
    }
    function import(Request $req) {
        if ($req->has("submit")) :
            error_reporting(0);
            //Move to tmp
            $file = $req->file("file");
            $path = $file->move(public_path("storage/tmp/", $file->getClientOriginalName()));
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path); // Load file yang tadi diupload ke folder tmp
            $sheet = $spreadsheet->getActiveSheet()->toArray();
            $rombel = getRombonganBelajarByID5($req->rombongan_belajar_id);
            foreach ($sheet as $key => $value) {
                if ($key < 6 || $key >= 42) continue; // Mulai dari baris 7
                if ($value[0] == "") break;
                $array = [1 => 7,7 => 10,12 => 13,19 => 16,27 => 19];
                foreach ($array as $key2 => $val) {
                    $cek = erapor5("nilai_sikap")->where([
                        "guru_id" => $rombel->guru_id,
                        "anggota_rombel_id" => $value[0],
                        "sikap_id" => $key2,
                        "deleted_at" => null
                    ]);
                    $data = [
                        "sekolah_id" => sesi("sekolah_id"),
                        "anggota_rombel_id" => $value[0],
                        "guru_id" => $rombel->guru_id,
                        "sikap_id" => $key2,
                        "tanggal_sikap" => $value[$val - 1],
                        "opsi_sikap" => ($value[$val] == "Positif") ? 1 : 0,
                        "uraian_sikap" => $value[$val + 1],
                        "updated_at" => now(),
                        "last_sync" => now(),
                    ];
                    if ($cek->count() == 0) :
                        $data["nilai_sikap_id"] = getUUID();
                        $data["created_at"] = now();
                        erapor5("nilai_sikap")->insert($data);
                    else:
                        $cek->update($data);
                    endif;
                }
            }
            return back()->with(["success", "Nilai Sikap berhasil disimpan!"]);
        endif;
    }
}
