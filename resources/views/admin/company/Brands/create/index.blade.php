@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @include('admin.company.Brands.tabs')
        <div class="row">
            <div class="card-panel radius--forms">

                @include('admin.company.Brands.form')
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
    <script src="{{asset('js/brand/brands.js')}}"></script>

    @include('admin.common.special-text-form',['model'=>($brandToEdit ?? new \App\Models\Brand\Brand())])
@endsection
