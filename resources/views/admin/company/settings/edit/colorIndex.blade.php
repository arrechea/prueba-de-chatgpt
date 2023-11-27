@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="header card-title">{{__('colors.CompanyColors')}}</h5>
                @include('admin.company.settings.edit.companyColors')
            </div>
        </div>
    </div>
@endsection
