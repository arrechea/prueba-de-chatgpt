@extends('admin.layout.master')
@section('content')
    <div class="main-container ">

        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
                <div class="BuqSaas-c-sectionTitle">
                    <h2>{{__('mails.resetMailsTitle')}}</h2>
                </div>
            </div>
            <div class="BuqSaas-l-form__body">
                @include('admin.company.mails.reset.form')
            </div>
        </div>
        @else
            @include('admin.company.mails.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="header card-title">{{__('mails.resetMailsTitle')}}</h5>
                    @include('admin.company.mails.reset.form')
                </div>
            </div>
        @endif
    </div>
@endsection
