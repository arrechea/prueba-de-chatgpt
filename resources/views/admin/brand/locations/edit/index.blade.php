@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.brand.locations.tabs')
        <div class="row">
            <div class="card-panel radius--forms"><h4 class="header">{{__('brand.Edit')}} {{__('brand.Studio')}}</h4>
                @include('admin.brand.locations.form')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    {{--**Script para aplicar los selectores de lugar (país, estado, ciudad)**--}}
    <?php
    $modelToEdit = $LocationToEdit ?? new \App\Models\GafaFitModel();
    ?>
    @include('admin.common.places-script')
    {{--**--**--}}
@endsection
