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
            if ($cek6 > 0) {
                $cek_absen6 = erapor6("absensi")->where("anggota_rombel_id", $anggota_rombel_id);
                if ($cek_absen6->count() == 0) :
                    $data["absensi_id"] = getUUID();
                    $data["created_at"] = now();
                    erapor6("absensi")->insert($data);
                else:
                    $cek_absen6->update($data);
                endif;
            }
        }
        return back()->with("success", "Absensi berhasil disimpan");
        // dd($data);
    }
    function template($rombongan_belajar_id) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load(public_path("template-excel/k13_rev-template_ketidakhadiran.xlsx"));
        $sheet = $spreadsheet->getActiveSheet();

        //START
        $baris = 7;
        $nomor = 1;

        $anggota_rombel5 = erapor5("anggota_rombel")->where("rombongan_belajar_id", $rombongan_belajar_id)
            ->join("peserta_didik", "peserta_didik.peserta_didik_id", "=", "anggota_rombel.peserta_didik_id")
            ->where("semester_id", sesi("semester_id"))
            ->whereNull("anggota_rombel.deleted_at")
            ->orderBy("nama")
            ->get();

        $anggota_rombel6 = erapor6("anggota_rombel")->where("rombongan_belajar_id", $rombongan_belajar_id)
            ->join("peserta_didik", "peserta_didik.peserta_didik_id", "=", "anggota_rombel.peserta_didik_id")
            ->where("semester_id", sesi("semester_id"))
            ->whereNull("anggota_rombel.deleted_at")
            ->orderBy("nama")
            ->get();
        $tahun_pelajaran = semester()->tahun_ajaran_id . "/" . (semester()->tahun_ajaran_id + 1);
        $sheet->getCell("B2")->setValue("TAHUN PELAJARAN $tahun_pelajaran");

        foreach ($anggota_rombel5 as $pd) {
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
            header('Content-Disposition: attachment; filename="template-ketidakhadiran_kelas_' . $rombel->nama . '.xlsx"');
        }
        foreach ($anggota_rombel6 as $pd2) {
            $rombel = erapor6("rombongan_belajar")->where("rombongan_belajar_id", $pd2->rombongan_belajar_id)
                ->where("semester_id", sesi("semester_id"))
                ->whereNull("deleted_at")
                ->first();
            $sheet->getCell("A" . $baris)->setValue($pd2->anggota_rombel_id);
            $sheet->getCell("B" . $baris)->setValue($nomor);
            $sheet->getCell("C" . $baris)->setValue($pd2->no_induk);
            $sheet->getCell("D" . $baris)->setValue($pd2->nisn);
            $sheet->getCell("E" . $baris)->setValue(proper($pd2->nama));
            $sheet->getCell("F" . $baris)->setValue($rombel->nama);
            $nomor++;
            $baris++;
            header('Content-Disposition: attachment; filename="template-ketidakhadiran_kelas_' . $rombel->nama . '.xlsx"');
        }
        //END

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $writer->save('php://output');
    }
    function import(Request $req) {
        if ($req->has("submit")) :
            // error_reporting(0);
            //Move to tmp
            $file = $req->file("file");
            $path = $file->move(public_path("storage/tmp/", $file->getClientOriginalName()));
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($path); // Load file yang tadi diupload ke folder tmp
            $sheet = $spreadsheet->getActiveSheet()->toArray();
            $rombel5 = erapor5("rombongan_belajar")
                ->whereNull("deleted_at")
                ->where("rombongan_belajar_id", $req->rombongan_belajar_id) 
                ->where("semester_id", sesi("semester_id"))
                ->where("jenis_rombel", 1);
            $rombel6 = erapor6("rombongan_belajar")
                ->whereNull("deleted_at")
                ->where("rombongan_belajar_id", $req->rombongan_belajar_id) 
                ->where("semester_id", sesi("semester_id"))
                ->where("jenis_rombel", 1);
            foreach ($sheet as $key => $value) {
                if ($key < 6 || $key >= 42) continue; // Mulai dari baris 7
                if ($value[0] == "") break;
                //iasuhduihasuhduashuid
                $cek5 = erapor5("absensi")->where("anggota_rombel_id", $value[0])->whereNull("deleted_at");
                $cek6 = erapor6("absensi")->where("anggota_rombel_id", $value[0])->whereNull("deleted_at");
                $data = [
                    "sekolah_id" => sesi("sekolah_id"),
                    "anggota_rombel_id" => $value[0],
                    "updated_at" => now(),
                    "last_sync" => now(),
                ];
                $absensi = [6 => "sakit", 7 => "izin", 8 => "alpa"];
                foreach ($absensi as $colom_excel => $kolom) {
                    $data[$kolom] = $value[$colom_excel];
                }
                if ($rombel5->count() > 0) :
                    if ($cek5->count() == 0) :
                        $data["absensi_id"] = getUUID();
                        $data["created_at"] = now();
                        erapor5("absensi")->insert($data);
                    else:
                        $cek5->update($data);
                    endif;
                endif;
                if ($rombel6->count() > 0) :
                    if ($cek6->count() == 0) :
                        $data["absensi_id"] = getUUID();
                        $data["created_at"] = now();
                        erapor6("absensi")->insert($data);
                    else:
                        $cek6->update($data);
                    endif;
                endif;
            }
            return back()->with("success", "Data Ketidakhadiran berhasil disimpan!");
        endif;
    }
}
