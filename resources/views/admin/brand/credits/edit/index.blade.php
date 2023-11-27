@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.credits.tabs')
        <div class="row">
            <div class="card-panel radius--forms"><h5
                    class="header">{{__('credits.Edit')}} {{__('credits.Credits')}}</h5>
                @include('admin.brand.credits.formcredits')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent()
    <script>
        window.Services = {
            url: "{{route('admin.company.brand.credits.services.save',['company'=>$company,'brand'=>$brand,'credit'=>$credit])}}"
        }
    </script>
    <script src="{{asset('js/credits/credits.js')}}"></script>
@endsection

@section('css')
    @parent

    <style>

        .location-selector {
            display: none;
        }

        .location-selector.active {
            display: block;
        }
    </style>
@endsection
