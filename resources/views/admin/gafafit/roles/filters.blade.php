<div class="row select2GafafitContainer">
    <div class="input-field col s12 m3">
        <label class="active" for="company_filter">
            {{__('roles.label-company')}}
        </label>
        <select name="company_filter" id="company_filter" class="select2-filter select col-s3"
                data-url="{{route('admin.roles.companies')}}"
                data-placeholder="{{ __('roles.select-company-placeholder')}}">
        </select>
    </div>
    <div class="input-field col s12 m3">
        <label class="active" for="brand_filter">
            {{__('roles.label-brand')}}
        </label>
        <select name="brand_filter" id="brand_filter" class="select2-filter select col-s3"
                data-url="{{route('admin.roles.brands')}}"
                data-placeholder="{{__('roles.select-brand-placeholder')}}">
        </select>
    </div>
    <div class="input-field col s12 m3">
        <label class="active" for="location_filter">
            {{__('roles.label-location')}}
        </label>
        <select name="location_filter" id="location_filter" class="select2-filter select col-s3"
                data-url="{{route('admin.roles.locations')}}"
                data-placeholder="{{ __('roles.select-location-placeholder')}}">
        </select>
    </div>
    <div class="input-field col s12 m3 right">
        <a href="{{route('admin.roles.create')}}" class="waves-effect waves-light btn btn-floating" id="new-role-button">
            <i class="material-icons medium tooltipped" data-tooltip="{{__('roles.button-new')}}" data-position="right">add</i>
        </a>
    </div>
</div>
<br>

