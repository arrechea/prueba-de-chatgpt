<div
    class="row config_settings"
    {!! old('methods['.$slug.'][active]', isset($method) ? true : null)!==null ? '' : 'style="display: none;"' !!}>
    <div class="col s12">
        {{--Development--}}
        <div class="row">
            <div class="input-field col s12 m6 l6">
                <input type="radio" name="methods[{{$slug}}][config][type]" value="development" id="development--{{$slug}}"
                       class="with-gap"
                @if(old("methods[{{$slug}}][config][type]")!=null)
                    {!! old("methods[{{$slug}}][config][type]")==='on' ? 'checked="checked"' : '' !!}
                    @elseif(isset($settings))
                    {!! isset($settings['type']) && $settings['type']==='development' ? 'checked="checked"' : '' !!}
                    @else
                    {!! 'checked="checked"' !!}
                    @endif
                >
                <label for="development--{{$slug}}">{{__('company.Development')}}</label>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="input-field col s12 m12">
                <input type="text" id="development_public_api_key_{{$slug}}"
                       name="methods[{{$slug}}][config][development_public_api_key]"
                       value="{{old('methods['.$slug.'][config][development_public_api_key]',$settings['development_public_api_key'] ?? '' )}}">
                <label for="development_public_api_key_{{$slug}}">{{__('company.DevelopmentPublicApiKey')}}</label>
            </div>
            <div class="input-field col s12 m12">
                <input type="text" id="development_private_api_key_{{$slug}}"
                       name="methods[{{$slug}}][config][development_private_api_key]"
                       value="{{old('methods['.$slug.'][config][development_private_api_key]',$settings['development_private_api_key'] ?? '' )}}">
                <label for="private_api_key_{{$slug}}">{{__('company.DevelopmentPrivateApiKey')}}</label>
            </div>
        </div>
        {{--Production--}}

        <div class="row">
            <div class="input-field col s12 m6 l6">
                <input type="radio" name="methods[{{$slug}}][config][type]" value="production" id="production--{{$slug}}"
                       class="with-gap" {!! old("methods[{{$slug}}][config][type]",isset($settings['type']) && $settings['type']==='production' ? true : null) !==null ? 'checked="checked"' : '' !!}>
                <label for="production--{{$slug}}">{{__('company.Production')}}</label>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="input-field col s12 m12">
                <input type="text" id="production_public_api_key_{{$slug}}"
                       name="methods[{{$slug}}][config][production_public_api_key]"
                       value="{{old('methods['.$slug.'][config][production_public_api_key]',$settings['production_public_api_key'] ?? '' )}}">
                <label for="production_public_api_key_{{$slug}}">{{__('company.ProductionPublicApiKey')}}</label>
            </div>
            <div class="input-field col s12 m12">
                <input type="text" id="production_private_api_key_{{$slug}}"
                       name="methods[{{$slug}}][config][production_private_api_key]"
                       value="{{old('methods['.$slug.'][config][production_private_api_key]',$settings['production_private_api_key'] ?? '' )}}">
                <label for="production_private_api_key_{{$slug}}">{{__('company.ProductionPrivateApiKey')}}</label>
            </div>
        </div>
    </div>
</div>
