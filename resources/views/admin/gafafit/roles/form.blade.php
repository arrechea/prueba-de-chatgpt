<div class="card-panel panelcombos">
    <form method="post" action="{{$formAction}}">
        @if(isset($role))
            <input name="id" value="{{$role->id}}" type="hidden"/>
            <div id="RolAttributes" style="display: none">{{$role}}</div>
        @else
            <div id="RolAttributes" style="display: none">{"companies_id":
                1,"company": {{\App\Models\Company\Company::find(1)}}}
            </div>
        @endif

        {{csrf_field()}}
        <div class="row">
            <button class="btn right waves-effect waves-light"
                    type="submit" name="save">
                <i class='material-icons right small'>save</i>{{__('roles.button-save')}}
            </button>
        </div>
        <div id="RoleCreate">
            @cargando
        </div>
        <div class="row">
            <ul class="collapsible" data-collapsible="accordion"
                style="border-bottom: 1px solid var(--menuleft-secondary-color)">
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

@section('jsPostApp')
    @parent
    <script>
        window.Roles = {
            urls: {
                company: "{{route('admin.roles.companies')}}",
                brandsLocations: "{{route('admin.roles.brandsLocations')}}",
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
    <div id="RoleEdit--company" style="display: none">{{\App\Models\Company\Company::find(1) ?? ''}}</div>
    <script src="{{mixGafaFit('js/admin/react/roles/create/build.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endsection
