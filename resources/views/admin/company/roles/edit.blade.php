@extends('admin.layout.master')

@section('content')
    <div class="main-container">
        @include('admin.gafafit.administrators.components.tabs')
        <div class="row">
            <div class="card-panel">
                <h5 class="card-title header">{{__('roles.title-edit')}}</h5>
                <form>
                    <div id="RoleCreate">
                        @cargando
                    </div>
                    <div class="row">
                        <ul class="collapsible" data-collapsible="accordion">
                            <li>
                                <div class="collapsible-header">{{__('roles.label-gafafit-permissions')}}</div>
                                <div class="collapsible-body">
                                    @include('admin.gafafit.roles.group-abilities',['entity_type'=>null])
                                </div>
                            </li>
                            <li>
                                <div class="collapsible-header">{{__('roles.label-company-permissions')}} </div>
                                <div class="collapsible-body">
                                    @include('admin.gafafit.roles.group-abilities',['entity_type'=>App\Models\Company\Company::class])
                                </div>
                            </li>
                            <li>
                                <div class="collapsible-header">{{__('roles.label-brand-permissions')}}</div>
                                <div class="collapsible-body">
                                    @include('admin.gafafit.roles.group-abilities',['entity_type'=>\App\Models\Brand\Brand::class])
                                </div>
                            </li>
                            <li>
                                <div class="collapsible-header">{{__('roles.label-location-permissions')}}</div>
                                <div class="collapsible-body">
                                    @include('admin.gafafit.roles.group-abilities',['entity_type'=>\App\Models\Location\Location::class])
                                </div>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@section('jsPostApp')
    @parent
    <script>
        window.Roles = {
            urls: {
                company: "{{route('admin.roles.companies')}}",
                brand: "{{route('admin.roles.brands')}}",
                location: "{{route('admin.roles.locations')}}"
            },
            lang: {
                button_save: "{{__('roles.button-save')}}",
                role_name: "{{__('roles.label-role-name')}}",
                company_placeholder: "{{__('roles.select-company-placeholder')}}",
                brand_placeholder: "{{__('roles.select-brand-placeholder')}}",
                location_placeholder: "{{__('roles.select-location-placeholder')}}",
                gafafit_permissions: "{{__('roles.label-gafafit-permissions')}}"
            }
        };
    </script>
    <script src="{{mixGafaFit('js/admin/react/roles/create/build.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endsection
