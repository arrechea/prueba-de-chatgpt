@extends('admin.layout.default')

@section('content')
<div class="signin__container">
    <div class="signin__row">
{{--        <div class="signin__row-col">--}}
{{--            <div class="signin__logo">--}}
{{--                <img src="{{isset($company) && isset($company->pic) ? $company->pic : asset('logo/buq-r.svg')}}" alt="GAFAfit">--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="signin__row-col">
            <div class="signin__wrapper auth-wrap">
                <img src="{{isset($company) && isset($company->pic) ? $company->pic : asset('logo/buq-r.svg')}}">
                <h1 class="signin__title">¿Olvidaste tu contraseña?</h1>
                <div class="signin__form">
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
                            action="{{ isset($company)?route('admin.companyLogin.password.sendResetLink',['company'=>$company]):route('admin.password.sendResetLink') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div>
                                    {{-- <i class="material-icons prefix">person</i> --}}
                                    <input class="white-text lowercase-text" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Ingresa tu correo de registro" autofocus>
                                    {{-- <label for="email">Email</label> --}}
                                    @if ($errors->has('email'))
                                    <span class="help-block error-text">
                                        {{ $errors->first('email') }}
                                    </span>
                                    @endif
                                </div>
                                {{--  SUBMIT BUTTON  --}}
                                <div class="input-field col s12 center">
                                    <button class="btn" type="submit" name="login">Recuperar</button>
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
        </div>
    </div>
</div>
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
