@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))


            <div class="BuqSaas-l-form">
                <div class="BuqSaas-l-form__header">
                    <div class="BuqSaas-c-sectionTitle">
                        <h2>Configuración de: <strong>{{($location->name)}}</strong></h2>
                    </div>
                </div>
                <div class="BuqSaas-l-form__body">
                    @include('admin.location.settings.form')
                </div>
            </div>
        @else
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="card-title header">{{__('settings.Location')}}</h5>
                    @include('admin.location.settings.form')

                </div>
            </div>
        @endif
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
