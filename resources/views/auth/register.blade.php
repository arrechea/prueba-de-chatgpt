@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form id="register-form" class="form-horizontal" method="POST" action="{{ route('api.register') }}">
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('companies_id') ? 'has-error' : '' }}">
                                <label for="companies_id" class="col-md-4 control-label">Compañía</label>
                                <div class="col-md-6">
                                    <select class="select2" id="companies_id" name="companies_id" style="width: 100%;">
                                        <option value="" selected="" disabled></option>
                                        @foreach(App\Models\Company\Company::all() as $company)
                                            <option
                                                value="{{ $company->id }}" {!! $company->id == old('companies_id') ? 'selected' : '' !!}>
                                                {{ __($company->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span id="companies_id_error" class="help-block">
                                        <strong>{{ $errors->first('companies_id') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                                <label for="username" class="col-md-4 control-label">Usuario</label>
                                <div class="col-md-6">
                                    <input class="form-control" id="username" name="username" type="email"
                                           value="{{ old('username') }}">
                                    <span id="username_error" class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Contraseña</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>
                                    <span id="password_error" class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                <label for="password-confirm" class="col-md-4 control-label">Repetir Contraseña</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                                <div class="col-md-6 offset-md-4">
                                    <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_SITE_KEY') }}"></div>
                                    <span id="g-recaptcha-response_error" class="help-block invalid-feedback"
                                          style="display: block;">
                                    </span>
                                </div>
                            </div>
                            <input class="persistent-value" type="hidden" id="captcha_secret_key" name="captcha_secret_key" value="{{ config('app.captcha_secret_key') }}">
                            <input class="persistent-value" type="hidden" id="remote_addr" name="remote_addr" value="{{ $_SERVER['REMOTE_ADDR'] }}">

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <a id="submit" href="#" class="btn btn-primary">Register</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('head_js')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('app.captcha_site_key') }}"></script>
@endsection

@section('foot_js')
    <script type="application/javascript">
        $("a#submit").click(function (e) {
            e.preventDefault();

            grecaptcha.ready(function () {
                grecaptcha.execute("{{ config('app.captcha_site_key') }}", { action: 'register' })
                    .then(function (token) {
                        $('#register-form').prepend('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');

                        $.ajax({
                            url: "{{ route('api.register') }}",
                            headers: {
                                'gafafit-company': $('#companies_id option:selected').val(),
                            },
                            method: 'POST',
                            // dataType: 'json',
                            'data': {
                                'username': $("#username").val(),
                                'password': $("#password").val(),
                                'password_confirmation': $("#password-confirm").val(),
                                'g-recaptcha-response': token,
                                'captcha_secret_key': $('#captcha_secret_key').val(),
                                'remote_addr': $('#remote_addr').val(),
                            },
                            beforeSend: function () {
                                $("*").css("cursor", "wait");

                                $("[id$='_error']")
                                    .each(function () {
                                        $(this)
                                            .html('')
                                            .closest('.form-group')
                                            .removeClass('has-error');
                                    });
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                                if (422 === jqXHR.status) {
                                    var errors = $.parseJSON(jqXHR.responseText);

                                    $.each(errors['errors'], function (key, val) {
                                        $("#" + key + "_error")
                                            .html('<strong>' + val[0] + '</strong>')
                                            .closest('.form-group')
                                            .addClass("has-error");
                                    });

                                    $("input[type='password']").val("");
                                }

                                grecaptcha.reset();
                            },
                            success: function(data) {
                                window.location.href = data.url;
                            },
                            complete: function() {
                                $("*").css("cursor", "auto");
                            }
                        });

                    });
            });
        });
    </script>
@endsection
