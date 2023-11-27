@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        {{--        @include('admin.company.settings.tabs')--}}
        <div class="row">
            <div class="card-panel radius--forms">

                @include('admin.company.user-import.form')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    <script>
        jQuery(document).ready(function ($){
            $('#test').on('click',function(){
                console.log($('form').serializeArray());
            });
        });
    </script>
@endsection
