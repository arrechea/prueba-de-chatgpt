@extends('admin.layout.master')
@section('content')

    <div class="main-container">
        @include('admin.company.Administrator.tabs')
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('gafafit.Roles')}}</h5>
                <div class="card-panel panelcombos">
                    @include('admin.catalog.table')
                </div>
            </div>
        </div>
    </div>
@endsection
@section('jsPostApp')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endsection

