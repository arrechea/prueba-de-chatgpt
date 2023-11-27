@extends('admin.layout.default')

@section('content')
    <div class="signin__container">
        <div class="signin__row">
            {{--            <div class="signin__row-col">--}}
            {{--                <div class="signin__logo">--}}
            {{--                    <img src="{{isset($company) && isset($company->pic) ? $company->pic : asset('logo/buq-r.svg')}}" alt="GAFAfit">--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div class="signin__row-col">
                <div class="signin__wrapper auth-wrap">
                    {{-- <div class="signin__logo" style="background-color: var(--main-color)"> --}}
                    <img src="{{isset($company) && isset($company->pic) ? $company->pic : asset('logo/buq-r.svg')}}"
                         alt="GAFAfit">
                    <h1 class="signin__title">¡Bienvenido!</h1>
                    <div class="signin__form">
                        <div class="row">
                            <form id="signin-form" class="col s12" role="form" method="POST"
                                  action="{{ isset($company) ? route('admin.companyLogin.login',['company'=>$company]):route('admin.login') }}">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div>
                                        <input class="login-input" type="email" id="email" name="email"
                                               placeholder="{{__('login.Email')}}"
                                               value="{{ old('email') }}" autofocus>
                                        @if ($errors->has('email'))
                                            <span class="help-block error-text">
                                            {{ $errors->first('email') }}
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <input class="login-input" type="password" id="password" name="password"
                                               placeholder="{{__('login.Password')}}">
                                        @if ($errors->has('password'))
                                            <span class="help-block error-text">
                                            {{ $errors->first('password') }}
                                        </span>
                                        @endif
                                    </div>
                                    <hr class="signin__divider">
                                    {{--  REMEMBER ME  --}}
                                    <div class="signin__footer">
                                        <div class="signin__footer-col">
                                            <input class="cb-secondary" type="checkbox" id="remember_me"
                                                   name="remember">
                                            <label for="remember_me">{{__('login.Remember')}}</label>
                                        </div>
                                        <div class="signin__footer-col">
                                            <a href="{{ isset($company) ?route('admin.companyLogin.password.reset',['company'=>$company]):route('admin.password.reset') }}">{{__('login.Forgot')}} {{__('login.Password')}}
                                                ?</a>
                                        </div>
                                    </div>
                                    {{--  SUBMIT BUTTON  --}}
                                    <div class="input-field col s12 center">
                                        <button class="btn login_btn" type="submit" name="login"
                                                data-preloader="blue" data-text="Login" data-icon="send"
                                                data-redirection="home.html">{{__('login.Login')}}</button>
                                    </div>
                                    {{--  <div class="col s12 center nav-link"><a class="switchVisibility" href="javascript:void(0)" data-ref="signup-wrapper">Create account</a></div>  --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Language Dropdown -->
    {{--  <div class="pull-right">
        <!-- Dropdown Trigger -->
        <a class='dropdown-button btn' data-activates='dropdownlang'>@lang('common.language')</a>

        <!-- Dropdown Structure -->
        <ul id='dropdownlang' class='dropdown-content'>
            <li><a id="lang-en">@lang('common.english')</a></li>
            <li><a id="lang-es">@lang('common.spanish')</a></li>
        </ul>
    </div>  --}}
@endsection


@section('jsPostApp')
    @include('common.change-language-script')
    @include('common.automatic-login')
@endsection

@section('css')
    <style>
        .signin__wrapper {
            text-align: center;
        }

        .signin__wrapper > img {
            max-width: 90px;
            margin-top: 45px;
        }

        body.signin{
            background-image: url("/images/login_back.png")!important;
        }
    </style>
@endsection
