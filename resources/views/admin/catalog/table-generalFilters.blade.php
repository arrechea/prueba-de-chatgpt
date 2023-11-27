<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<div class="DatatableFilters {{$isSaas ? 'BuqSaas-c-filters' : ''}}">
    @if($searchable)
        <div class="input-field col s12 m2 right mr-5 {{$isSaas ? 'is-search' : ''}}">
            @if($isSaas)
                <i class="far fa-search"></i>
            @else
                <label class="active" for="model_status">
                    {{__('datatable.search')}}
                </label>
            @endif
            <input type="text" placeholder="{{$isSaas ? 'Search' : ''}}"  name="criterio">
        </div>
    @endif
    @if($hasStatus===true)
        <div class="input-field col s12 m2 right mr-5">
            @if($isSaas)
                <i class="far fa-chevron-down"></i>
            @else
                <label class="active" for="model_status">
                    {{__('filters.statusFilter')}}
                </label>
            @endif
            <select name="model_status" id="model_status" class="">
                <option value="all">{{__('filters.all')}}</option>
                <option value="active">{{__('filters.actives')}}</option>
                <option value="inactive">{{__('filters.inactives')}}</option>
            </select>
        </div>
    @endif
</div>
