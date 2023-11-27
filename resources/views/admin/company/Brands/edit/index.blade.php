@extends('admin.layout.master')
@section('content')
    <div class="main-container">

        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
                <div class="BuqSaas-c-sectionTitle">
                    <h2>General y pagos</h2>
                </div>
            </div>
            <div class="BuqSaas-l-form__body">
                @include('admin.company.Brands.form')
            </div>
        </div>
        @else
            @include('admin.company.Brands.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    @include('admin.company.Brands.form')
                </div>
            </div>
        @endif
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

    @include('admin.common.special-text-form',['model'=>($brandToEdit ?? new \App\Models\Brand\Brand()),'brand'=>$brandToEdit])
@endsection
