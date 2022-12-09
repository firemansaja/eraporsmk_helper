@extends("template")

@section("karakter", "active")
@section("title", "Daftar Nilai Karakter")

@section("css")
    <style>
        .form-group {margin-bottom: 3px;}
        .table thead tr th {vertical-align: middle; text-align: center; padding: 3px;}
        .table tbody tr td {padding: 2px; vertical-align: middle}
        .table tfoot tr th {padding: 1px}
        .nilai {width: 150px}
        #cari {width: 100%}
    </style>
@endsection

@section("content")
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <form method="get">
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group">
                            <select name="rombongan_belajar_id" class="form-control select2">
                                <option value="">== Pilih Kelas</option>
                                @foreach ($rombel5 as $kls5)
                                    <option value="{{ $kls5->rombongan_belajar_id }}" {{ (isset($_GET['rombongan_belajar_id']) && ($_GET['rombongan_belajar_id'] == $kls5->rombongan_belajar_id)) ? "selected" : "" }} >{{ $kls5->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn bg-blue pull-right" id="cari"><i class="fa fa-search"></i>&nbsp; Cari</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <a class="btn bg-navy pull-right" id="nilai_sikap_button" disabled>Isi Nilai Karakter</a>
            @if(isset($_GET["rombongan_belajar_id"]))
                <a class="btn bg-green pull-right" id="import" style="margin-right: 3px;" data-toggle="modal" data-target="#modal-import">Import</a>
            @endif
        </div>
        @if(isset($_GET["rombongan_belajar_id"]))
            <div class="col-xs-12">
                <div class="box no-border">
                    <div class="box-body table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-navy">
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">NIS</th>
                                    <th rowspan="2">Nama Lengkap</th>
                                    <th rowspan="2">Kelas</th>
                                    <th colspan="5">Nilai Karakter</th>
                                    <th rowspan="2">Catatan</th>
                                </tr>
                                <tr>
                                    @foreach ($sikap as $skp)
                                        <th>{{ $skp->butir_sikap }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (getAnggotaRombelByRombel5($_GET["rombongan_belajar_id"]) as $pd)
                                    <input type="hidden" name="anggota_rombel_id[]" value="{{ $pd->anggota_rombel_id }}">
                                    <tr data-anggota_rombel_id="{{ $pd->anggota_rombel_id }}">
                                        <td class="text-center">{{ $nomor++ }}</td>
                                        <td class="text-center">{{ $pd->no_induk }}</td>
                                        <td>{{ proper($pd->nama) }}</td>
                                        <td class="text-center">{{ getRombonganBelajarByID5($_GET["rombongan_belajar_id"])->nama }}</td>
                                        @foreach ($sikap as $skp)
                                            <td style="width: 150px;" align="center">{{ nilai_karakter($pd->anggota_rombel_id, $skp->sikap_id)->deskripsi }}</td>
                                        @endforeach
                                        <td>{{ nilai_karakter($pd->anggota_rombel_id, 1)->capaian }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-navy">
                                <tr>
                                    <th colspan="10"></th>
                                </tr>
                            </tfoot>
                        </table>
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
                                        <i class="fa fa-th"></i>&nbsp; IMPORT NILAI KARAKTER
                                    </b>
                                </h3>
                            </div>
                            <div class="box-body">
                                <form action="{{ route("karakter.import") }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="rombongan_belajar_id" value="{{ $_GET["rombongan_belajar_id"] }}">
                                    <div class="form-group">
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                    <div>
                                        <a href="{{ route('karakter.template', $_GET["rombongan_belajar_id"]) }}" class="btn btn-success pull-left">Download Template</a>
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

@section("js")
    <script>
        $(function() {
            $(".table tbody tr").click(function() {
                $(".selected").removeClass("selected")
                $(this).addClass("selected")

                var anggota_rombel_id = $(this).data('anggota_rombel_id')
                $("#nilai_sikap_button").removeAttr("disabled")
                $("#nilai_sikap_button").attr("href", "{{ url('karakter/nilai') }}" + "/" + anggota_rombel_id)
            })
        })
    </script>
@endsection
