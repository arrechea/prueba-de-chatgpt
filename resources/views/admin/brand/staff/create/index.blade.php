@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
               <a class="BuqSaas-e-button is-link" href="{{route('admin.company.brand.staff.index', ['company' => $company,'brand'=>$brand])}}">
                  <i class="far fa-angle-left"></i>
                  <span>Atrás</span>
               </a>
                <div class="BuqSaas-c-sectionTitle">
                    <h2>{{__('staff.Create')}}</h2>
                </div>
            </div>
            <div class="BuqSaas-l-form__body">
                @include('admin.brand.staff.form')
            </div>
        </div>
        @else
            @include('admin.brand.staff.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="card-title header">{{__('staff.Create')}}</h5>

                    @include('admin.brand.staff.form')

                </div>
            </div>
        @endif
    </div>
@endsection

@section('jsPostApp')
    @parent
    {{--**Script para aplicar los selectores de lugar (país, estado, ciudad)**--}}
    <?php
    $modelToEdit = $staff ?? new \App\Models\GafaFitModel();
    $country_required = true;
    ?>
    @include('admin.common.places-script')
    {{--**--**--}}

    @include('admin.common.special-text-form',['model'=>($staff ?? new \App\Models\Staff\Staff())])
@endsection
