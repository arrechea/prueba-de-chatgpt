<div class="row">
    <div class="col s12">
        <div class="row ">
            <p>{{__($name)}}</p>
        </div>
        <div class="input-field payment_checkbox col s12 m6">
            <input type="checkbox" id="active_check_{{$slug}}" name="methods[{{$slug}}][active]"
                   class="payment_method_active"
                {!! old('methods['.$slug.'][active]', isset($method) ? true : null)!==null ? 'checked="checked"' : '' !!}>
            <label for="active_check_{{$slug}}">{{__('company.Active')}}</label>
        </div>
        <div class="input-field payment_checkbox col s12 m6">
            <input type="checkbox" id="active_front_{{$slug}}" name="methods[{{$slug}}][front]"
                   class="payment_method_front" {!! old("methods[{{$slug}}][active]", isset($method) ? true : null)!==null ? '' : 'disabled' !!}
                {!! old('methods['.$slug.'][front]', isset($method) && $method->pivot->front ? true : null)!==null ? 'checked="checked"' : '' !!}>
            <label for="active_front_{{$slug}}">{{__('company.ActiveFront')}}</label>
        </div>
        <div class="input-field payment_checkbox col s12 m6">
            <input type="checkbox" id="active_back_{{$slug}}" name="methods[{{$slug}}][back]"
                   class="payment_method_back" {!! old("methods[{{$slug}}][active]", isset($method) ? true : null)!==null ? '' : 'disabled' !!}
                {!! old('methods['.$slug.'][back]', isset($method) && $method->pivot->back ? true : null)!==null ? 'checked="checked"' : '' !!}>
            <label for="active_back_{{$slug}}">{{__('company.ActiveBack')}}</label>
        </div>
    </div>
</div><br>
