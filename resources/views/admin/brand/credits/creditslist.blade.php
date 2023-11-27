<?php
$credit_service = $credits_services->where('pivot.services_id', $service->id)->first();
$selected = in_array($service->id, $credits_services->pluck('pivot.services_id')->toArray());
?>
<li class="row">
    <div style="padding-top: 15px"></div>
    <div class="col s12 m12 l6">
        <input type="checkbox" class="service_checkbox" data-id="{{$i}}" id="services[{{$i}}][active]"
               name="services[{{$i}}][active]"
            {!! $selected ? 'checked="checked"' : '' !!}>
        <label for="services[{{$i}}][active]">{{$service->name}}</label>
    </div>
    <div class="col s12 m4 l2">
        <label for="services[{{$i}}][credits]">{{__('credits.CreditsPerService')}}</label>
        <input name="services[{{$i}}][credits]" id="services[{{$i}}][credits]" type="number"
               min="1"
               {!! $selected ? '' : 'disabled' !!}
               value="{{isset($credit_service) ? $credit_service->pivot->credits : 1 }}">
    </div>

    <input hidden name="services[{{$i}}][id]" value="{{$service->id}}"/>
    @if($service->childServicesRecursive->count()>0)
        <ul>
            @foreach($service->childServicesRecursive as $childService)
                {!! \App\Librerias\Servicies\LibServices::printServicesInCredits($childService, $credits_services )!!}
            @endforeach
        </ul>
    @endif
</li>

