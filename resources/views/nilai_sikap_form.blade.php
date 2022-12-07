@extends("template")

@section("title", "Formulir Nilai Sikap")
@section("sikap", "active")

@section("css")
    <style>
        .form-group label.control-label {text-align: left;}
    </style>
@endsection

@section("content")
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="box no-border">
                <div class="box-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label><br>
                        <input type="text" class="form-control" disabled value="{{ proper($pd->nama) }}">
                    </div>
                    <div class="form-group">
                        <label>Kelas</label><br>
                        <input type="text" class="form-control" disabled value="{{ $rombel->nama }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="box no-border">
                <div class="box-body">
                    <form action="{{ route("sikap.simpan") }}" method="post" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="anggota_rombel_id" value="{{ $anggota_rombel_id }}">
                        @foreach ($sikap as $key => $skp)
                            @php $nilai = nilai_sikap($anggota_rombel_id, $skp->sikap_id, $rombel->guru_id); @endphp
                            <div class="box no-border no-margin">
                                <div class="box-header bg-black">
                                    <h3 class="box-title">
                                        <b>
                                            {{ $skp->butir_sikap }}
                                        </b>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tanggal</label>
                                        <div class="col-sm-4">
                                            <input name="tanggal[{{ $skp->sikap_id }}]" type="date" class="form-control" placeholder="Email" required value="{{ $nilai->tanggal_sikap }}">
                                        </div>
                                        <div class="col-sm-6">
                                            <select name="opsi[{{ $skp->sikap_id }}]" class="form-control" required>
                                                <option value="">== Pilih Opsi ==</option>
                                                @for($i = 0; $i <= 1; $i++)
                                                    <option value="{!! $i !!}" {{ ($nilai->opsi_sikap == $i && $nilai->opsi_sikap != null) ? "selected" : "" }}>{!! $daftar_opsi[$i] !!}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0px;">
                                        <label class="col-sm-2 control-label">Deskripsi</label>
                                        <div class="col-sm-10">
                                            <textarea name="deskripsi[{{ $skp->sikap_id }}]" rows="3" class="form-control" required>{{ $nilai->uraian_sikap }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button type="submit" class="btn bg-blue" style="width: 100%">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
