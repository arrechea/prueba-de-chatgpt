<?php
$countries = \App\Models\Countries::all();
$states = $modelToEdit->country->states ?? '[]';
//    $cities = $modelToEdit->state->cities ?? '[]';
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<div id="PlacesCountries" style="display: none">{{$countries ?? '[]'}}</div>
<div id="PlacesStates"
     style="display: none">
    {{old('countries_id')!==null ? json_encode(array_values(old('states',[]))) : ($states ?? '[]')}}
</div>
{{--<div id="PlacesCities" style="display: none">--}}
{{--{{old('countries_id')!==null ? json_encode(array_values(old('cities',[]))) : ($cities ?? '[]')}}--}}
{{--</div>--}}
<div
    id="PlacesLang"
    style="display: none">{{new \Illuminate\Support\Collection(\Illuminate\Support\Facades\Lang::get('places'))}}</div>
<div id="countries_id--IsRequired" hidden>{{isset($country_required) ? '1' : ''}}</div>
<div id="country_states_id--IsRequired" hidden>{{isset($state_required) ? '1' : ''}}</div>
<div id="city--IsRequired" hidden>{{isset($city_required) ? '1' : ''}}</div>
<script>
    window.Places = {
        countries: JSON.parse($('#PlacesCountries').text()),
        states: JSON.parse($('#PlacesStates').text()),
        // cities: JSON.parse($('#PlacesCities').text()),
        default: {
            country: "{{old('countries_id',$modelToEdit->countries_id ?? null)}}",
            state: "{{old('country_states_id',$modelToEdit->country_states_id ?? null)}}",
            city: "{{old('city',$modelToEdit->city ?? null)}}",
        },
    }
</script>
<script src="{{mixGafaFit('js/admin/react/places/selects/build.js')}}"></script>
