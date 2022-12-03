
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset("bootstrap/dist/css/bootstrap.min.css") }}">
        <link rel="stylesheet" href="{{ asset("font-awesome/css/font-awesome.min.css") }}">
        <link rel="stylesheet" href="{{ repo("dist/css/AdminLTE.min.css") }}">
        <style>
            .form-group {
                margin: 3px;
            }
        </style>
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="index2.html"><b>Admin</b>LTE</a>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="{{ route("login") }}" method="post">
                    @csrf
                    <div class="form-group has-feedback {{ $errors->has('username') ? 'has-error' : '' }}">
                        <input type="email" name="username" class="form-control" placeholder="Email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if($errors->has('username'))
                            <small class="help-block">{{ $errors->first('username') }}</small>
                        @endif
                    </div>
                    <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        @if($errors->has('password'))
                            <small class="help-block">{{ $errors->first('password') }}</small>
                        @endif
                    </div>
                    <div class="form-group has-feedback {{ $errors->has('semester') ? 'has-error' : '' }}">
                        <select name="semester" class="form-control">
                            <option value="">== Pilih Semester ==</option>
                            @foreach ($daftar_semester as $semester)
                                <option value="{{ $semester->semester_id }}" {{ ($semester->semester_id == semester()->semester_id) ? 'selected' : '' }}>Tahun {{ $semester->nama }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('semester'))
                            <small class="help-block">{{ $errors->first('semester') }}</small>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script src="{{ repo("bower_components/jquery/dist/jquery.min.js") }}"></script>
        <script src="{{ asset("bootstrap/dist/js/bootstrap.min.js") }}"></script>
        @include('sweetalert::alert')
    </body>
</html>
