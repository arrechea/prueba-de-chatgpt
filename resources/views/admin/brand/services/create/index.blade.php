@extends('admin.layout.master')
@section('content')
    <div class="main-container">

        @if(Auth::user()->isA('gafa-saas'))
        <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
               <a class="BuqSaas-e-button is-link" href="{{route('admin.company.brand.services.index', ['company' => $company,'brand'=>$brand])}}">
                  <i class="far fa-angle-left"></i>
                  <span>Atrás</span>
               </a>
                <div class="BuqSaas-c-sectionTitle">
                    <h2>{{__('services.ServiceData')}}</h2>
                </div>
            </div>
            <div class="BuqSaas-l-form__body">
                @include('admin.brand.services.form')
            </div>
        </div>
        @else
            @include('admin.brand.services.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    @include('admin.brand.services.form')
                </div>
            </div>
        @endif
    </div>
@endsection

@section('jsPostApp')
    @parent
    @include('admin.common.special-text-form',['model'=>($service ?? new \App\Models\Service())])
    <script>
        window.Routes = {
            create: "{{route('admin.company.brand.services.create',['company'=>$company,'brand'=>$brand])}}"
        }
    </script>
    @include('admin.brand.services.gympass_scripts')
@endsection
