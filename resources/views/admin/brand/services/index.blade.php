@extends('admin.layout.master')
@section('content')
    <div class="main-container">
        @if(Auth::user()->isA('gafa-saas'))
            <div class="BuqSaas-l-list">
                <div class="BuqSaas-l-list__header">
                    <div class="BuqSaas-c-sectionTitle">
                        <h2>Servicios de: <strong>{{($brand->name)}}</h2>
                    </div>
                    <div class="BuqSaas-c-nav is-list">
                        <div class="BuqSaas-c-nav__filter">
                        </div>
                        <div class="BuqSaas-c-nav__add">
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::SERVICES_CREATE,$brand))
                                <a  class="BuqSaas-e-button is-add"
                                    href="{{route('admin.company.brand.services.create',['company'=>$company,'brand'=>$brand])}}">
                                    <i class="material-icons small">add</i>{{__('common.add')}}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="BuqSaas-l-list__body is-configServices">
                    @include('admin.catalog.table')
                </div>
            </div>
        @else 
            @include('admin.brand.services.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="card-title header">{{__('services.ListServices')}}</h5>

                    @include('admin.catalog.table')
                </div>
            </div>
        @endif
    </div>
@endsection
