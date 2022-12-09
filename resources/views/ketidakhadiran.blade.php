@extends("template")

@section("ketidakhadiran", "active")
@section("title", "Ketidakhadiran")

@section("css")
    <style>
        .table {margin: 3px;border-color: black}
        .table thead tr th {vertical-align: middle; text-align: center; padding: 2px}
        .table tfoot tr th {vertical-align: middle; text-align: center; padding: 1px}
        .table tbody tr td {vertical-align: middle; padding: 3px}
        .table tbody tr td input {vertical-align: middle; text-align: center;}
        .head-absensi {width: 150px}
    </style>
@endsection

@section("content")
    <div class="row">
        <div class="col-lg-3 col-xs-12">
            <div class="box no-border">
                <div class="box-body">
                    <form method="get">
                        <div class="form-group" style="margin-bottom: 3px;">
                            <select name="rombongan_belajar_id" class="form-control select2">
                                <option value="">== Pilih Kelas ==</option>
                                @foreach ($rombel6 as $kls6)
                                    <option value="{{ $kls6->rombongan_belajar_id }}" {{ (isset($_GET["rombongan_belajar_id"]) && $_GET["rombongan_belajar_id"] == $kls6->rombongan_belajar_id) ? "selected" : "" }}>{{ $kls6->nama }}</option>
                                @endforeach
                                @foreach ($rombel5 as $kls5)
                                    <option value="{{ $kls5->rombongan_belajar_id }}" {{ (isset($_GET["rombongan_belajar_id"]) && $_GET["rombongan_belajar_id"] == $kls5->rombongan_belajar_id) ? "selected" : "" }}>{{ $kls5->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn bg-blue pull-right"><i class="fa fa-search"></i>&nbsp; Cari</button>
                        @if(isset($_GET["rombongan_belajar_id"]))
                            <a class="btn bg-green pull-right" id="import" style="margin-right: 3px;" data-toggle="modal" data-target="#modal-import">Import</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        @if(isset($_GET["rombongan_belajar_id"]))
            <div class="col-lg-9 col-xs-12">
                <div class="box no-border">
                    <div class="box-body table-responsive">
                        <form action="{{ route("ketidakhadiran.simpan") }}" method="post">
                            @csrf
                            <table class="table table-bordered">
                                <thead class="bg-navy">
                                    <tr>
                                        <th rowspan="2">No</th>
                                        <th rowspan="2">NIPD</th>
                                        <th rowspan="2">NISN</th>
                                        <th rowspan="2">Nama Lengkap</th>
                                        <th rowspan="2">Kelas</th>
                                        <th colspan="3">Absensi</th>
                                    </tr>
                                    <tr>
                                        <th class="head-absensi">Sakit</th>
                                        <th class="head-absensi">Izin</th>
                                        <th class="head-absensi">Alpa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (getAnggotaRombelByRombel5($_GET["rombongan_belajar_id"]) as $pd)
                                        <input type="hidden" name="anggota_rombel_id[]" value="{{ $pd->anggota_rombel_id }}">
                                        <tr>
                                            <td class="text-center">{{ $nomor++ }}</td>
                                            <td class="text-center">{{ $pd->no_induk }}</td>
                                            <td class="text-center">{{ $pd->nisn }}</td>
                                            <td>{{ proper($pd->nama) }}</td>
                                            <td class="text-center">{{ getRombonganBelajarByID5($_GET["rombongan_belajar_id"])->nama }}</td>
                                            <td><input type="number" name="sakit[]" class="form-control input-sm" min="0" value="{{ absensi5($pd->anggota_rombel_id)->sakit }}"></td>
                                            <td><input type="number" name="izin[]" class="form-control input-sm" min="0" value="{{ absensi5($pd->anggota_rombel_id)->izin }}"></td>
                                            <td><input type="number" name="alpa[]" class="form-control input-sm" min="0" value="{{ absensi5($pd->anggota_rombel_id)->alpa }}"></td>
                                        </tr>
                                    @endforeach
                                    @foreach (getAnggotaRombelByRombel6($_GET["rombongan_belajar_id"]) as $pd)
                                        <input type="hidden" name="anggota_rombel_id[]" value="{{ $pd->anggota_rombel_id }}">
                                        <tr>
                                            <td class="text-center">{{ $nomor++ }}</td>
                                            <td class="text-center">{{ $pd->no_induk }}</td>
                                            <td class="text-center">{{ $pd->nisn }}</td>
                                            <td>{{ proper($pd->nama) }}</td>
                                            <td class="text-center">{{ getRombonganBelajarByID6($_GET["rombongan_belajar_id"])->nama }}</td>
                                            <td><input type="number" name="sakit[]" class="form-control input-sm" min="0" value="{{ absensi6($pd->anggota_rombel_id)->sakit }}"></td>
                                            <td><input type="number" name="izin[]" class="form-control input-sm" min="0" value="{{ absensi6($pd->anggota_rombel_id)->izin }}"></td>
                                            <td><input type="number" name="alpa[]" class="form-control input-sm" min="0" value="{{ absensi6($pd->anggota_rombel_id)->alpa }}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-navy">
                                    <tr>
                                        <th colspan="8"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="submit" name="submit" class="btn bg-blue" style="width: 100%">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @if(isset($_GET["rombongan_belajar_id"]))
        <div class="modal fade" id="modal-import">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body no-padding">
                        <div class="box no-border">
                            <div class="box-header bg-black">
                                <h3 class="box-title">
                                    <b>
                                        <i class="fa fa-th"></i>&nbsp; IMPORT DATA KETIDAKHADIRAN
                                    </b>
                                </h3>
                            </div>
                            <div class="box-body">
                                <form action="{{ route("ketidakhadiran.import") }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="rombongan_belajar_id" value="{{ $_GET["rombongan_belajar_id"] }}">
                                    <div class="form-group">
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                    <div>
                                        <a href="{{ route('ketidakhadiran.template', $_GET["rombongan_belajar_id"]) }}" class="btn btn-success pull-left">Download Template</a>
                                        <button class="btn bg-blue pull-right" name="submit">Submit</button>
                                        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal">Tutup</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
