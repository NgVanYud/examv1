<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('generals.app_name')</title>
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
</head>
<body>
    <div class="auth-form-container d-flex justify-content-center align-items-center">
        <div class="auth-form-wrapper">
            <div class="row">
                <div class="col-md-5 text-center d-flex justify-content-center flex-column" style="border-right: 2px solid rgba(255, 255, 255, 0.23);">
                    <div>
                        <img src="{{ asset('/images/logo.png') }}" class="rounded-circle" alt="KMA">
                    </div>
                    <div class="mt-3">
                        <div>
                            <strong> @lang('generals.school_name') </strong>
                        </div>
                        <div>
                            <small>*********</small>
                        </div>
                        <div>
                            <small>
                                <strong> @lang('generals.app_name') </strong>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <h3 class="title">Reset Password</h3>
                    <form method="POST" class="el-form" acction="{{ route('password.reset') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="el-form-item is-required el-form-item--medium">
                            <div class="el-form-item__content">
                                <span class="svg-container">
                                    <svg width="28" height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 115" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <path d="M64.125 56.975L120.188.912A12.476 12.476 0 0 0 115.5 0h-103c-1.588 0-3.113.3-4.513.838l56.138 56.137z"/>
                                        <path d="M64.125 68.287l-62.3-62.3A12.42 12.42 0 0 0 0 12.5v71C0 90.4 5.6 96 12.5 96h103c6.9 0 12.5-5.6 12.5-12.5v-71a12.47 12.47 0 0 0-1.737-6.35L64.125 68.287z"/>
                                    </svg>
                                </span>
                                <div class="el-input el-input--medium">
                                    <input type="email"
                                           value="{{ $email }}"
                                           autocomplete="on" name="email"
                                           placeholder="@lang('validation.attributes.email')"
                                           class="el-input__inner">
                                </div>
                            </div>
                        </div>
                        <div class="el-form-item is-required el-form-item--medium">
                            <div class="el-form-item__content">
                                <span class="svg-container">
                                    <svg width="28" height="15" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 115" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M108.8 44.322H89.6v-5.36c0-9.04-3.308-24.163-25.6-24.163-23.145 0-25.6 16.881-25.6 24.162v5.361H19.2v-5.36C19.2 15.281 36.798 0 64 0c27.202 0 44.8 15.281 44.8 38.961v5.361zm-32 39.356c0-5.44-5.763-9.832-12.8-9.832-7.037 0-12.8 4.392-12.8 9.832 0 3.682 2.567 6.808 6.407 8.477v11.205c0 2.718 2.875 4.962 6.4 4.962 3.524 0 6.4-2.244 6.4-4.962V92.155c3.833-1.669 6.393-4.795 6.393-8.477zM128 64v49.201c0 8.158-8.645 14.799-19.2 14.799H19.2C8.651 128 0 121.359 0 113.201V64c0-8.153 8.645-14.799 19.2-14.799h89.6c10.555 0 19.2 6.646 19.2 14.799z"/>
                                    </svg>
                                </span>
                                <div class="el-input el-input--medium">
                                    <input type="password"
                                           value="{{ old('password') }}"
                                           autocomplete="on"
                                           name="password"
                                           placeholder="@lang('validation.attributes.new_password')"
                                           class="el-input__inner">
                                </div>
                            </div>
                        </div>
                        <div class="el-form-item is-required el-form-item--medium">
                            <div class="el-form-item__content">
                                <span class="svg-container">
                                    <svg version="1.1" id="Capa_1" width="28" height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                         viewBox="0 0 900 500" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path d="M368.64,0H20.48C9.175,0,0,9.175,0,20.48v348.16c0,11.305,9.175,20.48,20.48,20.48h348.16
                                                c11.305,0,20.48-9.175,20.48-20.48V20.48C389.12,9.175,379.945,0,368.64,0z M348.16,348.16H40.96V40.96h307.2V348.16z"/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M471.04,419.84v51.2h-51.2V512h71.68c11.305,0,20.48-9.175,20.48-20.48v-71.68H471.04z"/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <rect x="262.349" y="471.04" width="110.182" height="40.96"/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M163.84,471.04v-51.2h-40.96v71.68c0,11.305,9.175,20.48,20.48,20.48h71.68v-40.96H163.84z"/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <path d="M491.52,122.88h-71.68v40.96h51.2v51.2H512v-71.68C512,132.055,502.825,122.88,491.52,122.88z"/>
                                        </g>
                                    </g>
                                    <g>
                                        <g>
                                            <rect x="471.04" y="262.349" width="40.96" height="110.182"/>
                                        </g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    <g>
                                    </g>
                                    </svg>
                                </span>
                                <div class="el-input el-input--medium">
                                    <input type="password"
                                           value="{{ old('password_confirmation') }}"
                                           autocomplete="on"
                                           name="password_confirmation"
                                           placeholder="@lang('validation.attributes.retype_new_password')"
                                           class="el-input__inner">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="el-button mb-2 el-button--default el-button--medium d-block w-100">
                            @lang('buttons.general.submit')
                        </button>
                        <button type="reset" class="el-button ml-0 el-button--info el-button--medium d-block w-100">
                            @lang('buttons.general.reset')
                        </button>
                        <div class="auth-form-footer text-center">
                            <hr>
                            <span> @lang('generals.school_name') </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- row -->
</body>
</html>

