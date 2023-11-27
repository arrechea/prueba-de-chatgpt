@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.gafafit.creditsgf.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                @include('admin.gafafit.creditsgf.form')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent()
    <script>
        window.Services = {
            url: "{{route('admin.credits.services.save',['gafacredit'=>$gafacredit->id])}}"
        }
    </script>
    <script src="{{asset('js/credits/credits.js')}}"></script>
@endsection
