@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.credits.tabs')
        <div class="row">
            <div class="card-panel radius--forms"><h5 class="header">{{__('credits.Edit')}} {{__('credits.Credits')}}</h5>
                @include('admin.company.credits.formcredits')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent()
    <script>
        window.Services = {
            url: "{{route('admin.company.credits.services.save',['company'=>$company,'credit'=>$credit])}}"
        }
    </script>
    <script src="{{asset('js/credits/credits.js')}}"></script>
@endsection
