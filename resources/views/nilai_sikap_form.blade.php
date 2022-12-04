@extends("template")

@section("title", "Formulir Nilai Sikap")
@section("sikap", "active")

@section("content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box no-border">
                <div class="box-body">
                    <form action="" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-2">
                                    Nama
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" name="" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-2">
                                    Kelas
                                </div>
                                <div class="col-sm-10">
                                    <input type="text" name="" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        @foreach ($sikap as $skp)
                            <div class="box no-border">
                                <div class="box-header bg-black">
                                    <h3 class="box-title">
                                        <b>
                                            {{ $skp->butir_sikap }}
                                        </b>
                                    </h3>
                                </div>
                                <div class="box-body">
                                    aksdjasd
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
