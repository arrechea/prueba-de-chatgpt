@extends('admin.layout.default')

@section('content')
    <div class="logo-section valign-wrapper circle primary-bg">
        <img class="valign" src="{{ url('logo/ic_whatshot_48px.svg') }}" alt="Forge">
    </div>
    <div class="signin-wrapper auth-wrap">
        <div class="signin-form">
            <div class="row">
                @if (session('status'))
                    <div class="col s12">
                        <div class="animated alert-flash success }} valign-wrapper pos-relative">
                            {{ session('status') }}
                            <a href="#" class="close-flash"><i class="material-icons right primary-text">close</i></a>
                        </div>
                    </div>
                @endif
                <form class="col s12" role="form" method="POST"
                      action="{{ isset($company)?route('admin.companyLogin.password.tryReset',['company'=>$company]):route('admin.password.tryReset') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="row">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">person</i>
                            <input class="white-text lowercase-text" type="email" id="email" name="email"
                                   value="{{ old('email') }}" autofocus>
                            <label for="email">Email</label>
                            @if ($errors->has('email'))
                                <span class="help-block error-text">
                                {{ $errors->first('email') }}
                            </span>
                            @endif
                        </div>

                        <div class="input-field col s12">
                            <i class="material-icons prefix">vpn_key</i>
                            <input class="white-text" type="password" id="password" name="password">
                            <label for="password">Password</label>
                            @if ($errors->has('password'))
                                <span class="help-block error-text">
                                {{ $errors->first('password') }}
                            </span>
                            @endif
                        </div>

                        <div class="input-field col s12">
                            <i class="material-icons prefix">vpn_key</i>
                            <input class="white-text" type="password" id="password-confirm"
                                   name="password_confirmation">
                            <label for="password-confirm">Confirm Password</label>
                            @if ($errors->has('password_confirmation'))
                                <span class="help-block error-text">
                                {{ $errors->first('password_confirmation') }}
                            </span>
                            @endif
                        </div>

                        {{--  SUBMIT BUTTON  --}}
                        <div class="input-field col s12 center">
                            <button class="btn mm-btn waves-effect waves-light sigin-submit" type="submit" name="login">
                                Reset Password <i class="material-icons right white-text">send</i>
                            </button>
                        </div>

                    </div>
                </form>
                <div class="col s12 center-align">
                    <a href="{{ isset($company)?route('admin.companyLogin.init',['company'=>$company]):route('admin.init') }}"
                       title="" class="primary-text light">Back to login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
