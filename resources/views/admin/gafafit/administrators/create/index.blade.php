@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.gafafit.administrators.components.tabs')
        <div class="row">
            <div class="card-panel radius--forms">

                @include('admin.gafafit.administrators.formadmin')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
@endsection
