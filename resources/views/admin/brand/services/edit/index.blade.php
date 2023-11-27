@extends('admin.layout.master')
@section('content')
    <div class="main-container">

        @if(Auth::user()->isA('gafa-saas'))
            <div class="BuqSaas-l-form">
                <div class="BuqSaas-l-form__header">
                    <a class="BuqSaas-e-button is-link"
                       href="{{route('admin.company.brand.services.index', ['company' => $company,'brand'=>$brand])}}">
                        <i class="far fa-angle-left"></i>
                        <span>Atrás</span>
                    </a>
                    <div class="BuqSaas-c-sectionTitle">
                        <h2>{{__('services.Edit')}}</h2>
                    </div>
                </div>
                <div class="BuqSaas-l-form__body">
                    @include('admin.brand.services.form')
                </div>
            </div>
        @else
            @include('admin.brand.services.tabs')
            <div class="row">
                <div class="card-panel radius--forms" id="service-edit-panel">
                    @include('admin.brand.services.form')
                </div>
            </div>
        @endif
    </div>
@endsection

@section('jsPostApp')
    @parent
    <script src="{{asset('js/services.js')}}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <div id="StaffSpecialTexts" style="display: none">{{$special_texts or []}}</div>
    <div id="SpecialTextsLang"
         style="display: none">{{new \Illuminate\Support\Collection(\Illuminate\Support\Facades\Lang::get('special-texts-form'))}}</div>
    @include('admin.common.special-text-form',['model'=>($service ?? new \App\Models\Service())])
    <script>
        window.SpecialTexts = {
            texts: JSON.parse($('#StaffSpecialTexts').text()),
            urls: {
                create: "{{route('admin.company.brand.services.special_text.create',['company'=>$company,'brand'=>$brand,'service'=>$service])}}"
            },
        };
    </script>
    <script src="{{mixGafaFit('js/admin/react/special_texts/list/build.js')}}"></script>

    <script>
        jQuery(document).ready(function ($) {

        });
    </script>

    @include('admin.brand.services.gympass_scripts')
@endsection
