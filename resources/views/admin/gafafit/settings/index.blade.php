@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        <div class="row">
            <div class="card-panel radius--forms">
                <h5 class="card-title header">{{__('gafafit.Settings')}}</h5>

               @include('admin.gafafit.settings.form')
            </div>
        </div>
    </div>
@endsection
