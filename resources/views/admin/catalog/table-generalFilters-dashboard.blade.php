<div class="DatatableFilters filters">
    @if($searchable)
        <div class="form__section">
            {{--  <label class="active" for="model_status">
                {{__('datatable.search')}}
            </label>  --}}
            <input type="text" name="criterio" placeholder="{{__('datatable.search')}}">
        </div>
    @endif
    @if($hasStatus===true)
        <div class="form__section">
            {{--  <label class="active" for="model_status">
                {{__('filters.statusFilter')}}
            </label>  --}}
            <select name="model_status" id="model_status" class="">
                <option value="all">{{__('filters.all')}}</option>
                <option value="active">{{__('filters.actives')}}</option>
                <option value="inactive">{{__('filters.inactives')}}</option>
            </select>
        </div>
    @endif
</div>
