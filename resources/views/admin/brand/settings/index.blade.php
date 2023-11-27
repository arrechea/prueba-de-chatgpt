@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        <div class="row">
            <div class="card-panel radius--forms"><h4 class="header">{{__('brand.Settings')}}</h4>
                @include('admin.brand.settings.form')
            </div>
        </div>
    </div>
@endsection

@section('jsPostApp')
    @parent
    {{--**Script para aplicar los selectores de lugar (país, estado, ciudad)**--}}
    <?php
    $modelToEdit = $brandToEdit ?? new \App\Models\GafaFitModel();
    ?>
    @include('admin.common.places-script')
    {{--**--**--}}
@endsection
