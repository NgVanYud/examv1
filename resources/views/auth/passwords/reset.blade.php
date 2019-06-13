<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hệ Thống Thi Trắc Nghiệm Online</title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
</head>
<body>
    <div class="row d-flex justify-content-center h-100">
        <div class="col col-sm-6 align-self-center justify-content-center">
            <div class="card">
                <div class="card-header">
                    <strong>
                        Hệ Thống Thi Trắc Nghiệm Online - @lang('labels.frontend.passwords.reset_password_box_title')
                    </strong>
                </div><!--card-header-->

                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" acction="{{ route('password.reset') }}">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email" value="{{ $email }}" disabled>
                        </div>
                        <input type="hidden" value="{{ $token }}">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Mật Khẩu Mới</label>
                            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Mật Khẩu">
                            <small id="emailHelp" class="form-text text-muted">Password tối thiểu 8 ký tự</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Xác Nhận Mật Khẩu Mới</label>
                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Xác nhận mật khẩu">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-6 -->
    </div><!-- row -->
</body>
</html>

