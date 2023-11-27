@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.mails.tabs')
        <div class="row">
            <div class="card-panel radius--forms"><h5 class="header">{{__('mails.settings')}} de {{__('mails.Mail')}}
                    de {{__('mails.forgetPass')}}</h5>
                @include('admin.brand.mails.forgetPassword.forgetPassword')
            </div>
        </div>
    </div>
@endsection
