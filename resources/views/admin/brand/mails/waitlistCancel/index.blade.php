@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
                <div class="BuqSaas-c-sectionTitle">
                    <h2>{{__('mails.settingsMailWaitlistCancel')}}</h2>
                </div>
            </div>
            <div class="BuqSaas-l-form__body">
                @include('admin.brand.mails.waitlistCancel.form')
            </div>
        </div>
        @else
            @include('admin.brand.mails.tabs')
            <div class="row">
                <div class="card-panel radius--forms"><h5 class="header">{{__('mails.settingsMailWaitlistCancel')}}</h5>
                    @include('admin.brand.mails.waitlistCancel.form')
                </div>
            </div>
        @endif
    </div>
@endsection
