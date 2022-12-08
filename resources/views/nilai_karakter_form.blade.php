@extends("template")

@section("title", "Nilai Karakter")

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
                <div class="box-body table-responsive">
                    <form action="{{ route("karakter.simpan") }}" method="post">
                        @csrf
                        <input type="hidden" name="anggota_rombel_id" value="{{ $anggota_rombel_id }}">
                        <table class="table no-margin">
                            <tbody>
                                @foreach ($sikap as $key => $skp)
                                    <tr>
                                        <td><label>{{ $skp->butir_sikap }}</label></td>
                                        <td width="25">:</td>
                                        <td><textarea name="sikap_id[{{ $skp->sikap_id }}]" rows="3" class="form-control"></textarea></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><label>Capaian Karakter</label></td>
                                    <td>:</td>
                                    <td><textarea name="capaian_ppk" rows="5" class="form-control"></textarea></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary" style="width: 100%;" name="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
