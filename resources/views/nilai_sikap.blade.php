@extends("template")

@section("sikap", "active")
@section("title", "Sikap")

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
                                    <option value="{{ $kls5->rombongan_belajar_id }}">{{ $kls5->nama }}</option>
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
            <a class="btn bg-navy pull-right" id="nilai_sikap_button" disabled>Isi Nilai Sikap</a>
            <a class="btn bg-green pull-right" id="import" style="margin-right: 3px;" data-toggle="modal" data-target="#modal-import">Import</a>
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
                                    <th rowspan="2"></th>
                                    <th colspan="{{ $sikap->count() }}">Nilai Sikap</th>
                                </tr>
                                <tr>
                                    @foreach ($sikap->get() as $skp)
                                        <th class="nilai">{{ $skp->butir_sikap }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (getAnggotaRombelByRombel5($_GET["rombongan_belajar_id"]) as $pd)
                                    <input type="hidden" name="anggota_rombel_id[]" value="{{ $pd->anggota_rombel_id }}">
                                    <tr data-anggota_rombel_id="{{ $pd->anggota_rombel_id }}">
                                        <td rowspan="2" class="text-center">{{ $nomor++ }}</td>
                                        <td rowspan="2" class="text-center">{{ $pd->no_induk }}</td>
                                        <td rowspan="2">
                                            <div>{{ proper($pd->nama) }}</div>
                                            <div>{{ getRombonganBelajarByID5($_GET["rombongan_belajar_id"])->nama }}</div>
                                        </td>
                                        <td>Opsi Sikap</td>
                                        @foreach ($sikap->get() as $skp)
                                            @php $nilai = nilai_sikap($pd->anggota_rombel_id, $skp->sikap_id, getRombonganBelajarByID5($_GET["rombongan_belajar_id"])->guru_id); @endphp
                                            <td>
                                                @switch($nilai->opsi_sikap)
                                                    @case(null) @break
                                                    @case(1)
                                                        Positif
                                                        @break
                                                    @case(0)
                                                        Negatif
                                                        @break
                                                    $@default
                                                @endswitch
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr data-anggota_rombel_id="{{ $pd->anggota_rombel_id }}">
                                        <td>Uraian Sikap</td>
                                        @foreach ($sikap->get() as $skp)
                                            @php $nilai = nilai_sikap($pd->anggota_rombel_id, $skp->sikap_id, getRombonganBelajarByID5($_GET["rombongan_belajar_id"])->guru_id); @endphp
                                            <td>{{$nilai->uraian_sikap}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-navy">
                                <tr>
                                    <th colspan="9"></th>
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
                                        <i class="fa fa-th"></i>&nbsp; IMPORT NILAI SIKAP
                                    </b>
                                </h3>
                            </div>
                            <div class="box-body">
                                <form action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                    <div>
                                        <a href="{{ route('sikap.template', $_GET["rombongan_belajar_id"]) }}" class="btn btn-success pull-left">Download Template</a>
                                        <button class="btn bg-blue pull-right">Submit</button>
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
                $("#nilai_sikap_button").attr("href", "{{ url('sikap/nilai') }}" + "/" + anggota_rombel_id)
            })
        })
    </script>
@endsection
