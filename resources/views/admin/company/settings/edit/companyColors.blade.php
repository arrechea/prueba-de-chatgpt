<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">

    @include('admin.common.alertas')
    @if (isset($colorComp))
        <input type="hidden" name="id" value="{{$colorComp->id}}">
        @endif
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <div class="col s12 m12">
            <div class="row">
                <div>
                    <a class="btn" href="{{route('admin.company.settings.index',['company' => $company])}}"><i class="material-icons">replay</i>
                        <span style="display: inline-block">{{__('colors.back')}}</span></a>
                </div>
                <br>
            </div>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="input-field col s12 m6 l6">
                    <input type="text" class="color txt--color" id="color_black" name="color_black"
                           value="{{old('color_black',(isset($colorComp) ? $colorComp->color_black: '#000000'))}}"/>
                    <label class="colors--label" for="color_black">{{__('colors.black')}}</label>
                </div>
                <div class="input-field col s12 m6 l6">
                    <input type="text" class="color txt--color" id="color_main" name="color_main"
                           value="{{old('color_main', isset($colorComp) ? $colorComp->color_main: '#323232')}}" />
                    <label class="colors--label" for="color_main">{{__('colors.main')}}</label>
                </div>
                {{-- <div class="input-field col s12 m4 l4">
                    <input type="text"  class="color txt--color" id="color_secondary" name="color_secondary"
                           value="{{old('color_secondary', isset($colorComp) ? $colorComp->color_secondary: '#626262')}}"/>
                    <label class="colors--label" for="color_secondary">{{__('colors.secondary')}}</label>
                </div> --}}
                {{-- <div class="input-field col s12 m4 l4">
                    <input type="text"  class="color txt--color" id="color_secondary2" name="color_secondary2"
                           value="{{old('color_secondary2',isset($colorComp) ? $colorComp->color_secondary2: '#929292')}}"/>
                    <label class="colors--label" for="color_secondary2">{{__('colors.secondary2')}}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input type="text"  class="color txt--color" id="color_secondary3" name="color_secondary3"
                           value="{{old('color_secondary3', isset($colorComp) ? $colorComp->color_secondary3: '#C2C2C2')}}"/>
                    <label class="colors--label" for="color_secondary3">{{__('colors.secondary3')}}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input type="text"  class="color txt--color" id="color_light" name="color_light"
                           value="{{old('color_light', isset($colorComp) ? $colorComp->color_light: '#C2C2C2')}}"/>
                    <label class="colors--label" for="color_light">{{__('colors.light')}}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input type="text"  class="color txt--color" id="color_menutop" name="color_menutop"
                           value="{{old('color_menutop', isset($colorComp) ? $colorComp->color_menutop: '#323232')}}"/>
                    <label class="colors--label" for="color_menutop">{{__('colors.menutop')}}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input type="text"  class="color txt--color" id="color_menuleft" name="color_menuleft"
                           value="{{old('color_menuleft', isset($colorComp) ? $colorComp->color_menuleft: '#323232')}}"/>
                    <label class="colors--label" for="color_menuleft">{{__('colors.menuleft')}}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input type="text"  class="color txt--color" id="color_menuleft_secondary" name="color_menuleft_secondary"
                           value="{{old('color_menuleft_secondary', isset($colorComp) ? $colorComp->color_menuleft_secondary: '#323232')}}"/>
                    <label class="colors--label" for="color_menuleft_secondary">{{__('colors.menuSecondary')}}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input type="text"  class="color txt--color" id="color_menuleft_selected" name="color_menuleft_selected"
                           value="{{old('color_menuleft_selected', isset($colorComp) ? $colorComp->color_menuleft_selected:'#626262')}}"/>
                    <label class="colors--label" for="color_menuleft_selected">{{__('colors.menuSelected')}}</label>
                </div>
                <div class="input-field col s12 m4 l4">
                    <input type="text" class="color txt--color" id="color_alert" name="color_alert"
                           value="{{old('color_alert', isset($colorComp)? $colorComp->color_alert: '#FF0000')}}" />
                    <label class="colors--label" for="color_alert">{{__('colors.alert')}}</label>
                </div> --}}

            </div>
            <div class="">
                <button type="submit" class="waves-effect waves-light btn btnguardar right"><i
                        class="material-icons right small">save</i>{{__('gafacompany.Save')}}</button>
            </div>
            <br>

        </div>
    </div>
</form>


@section('jsPostApp')
    @parent

    <script src="{{asset('plugins/pickers/tinyColorPicker/colors.js')}}"></script>
    <script src="{{asset('plugins/pickers/tinyColorPicker/jqColorPicker.js')}}"></script>
    {{-- <script type="text/javascript" src="jqColorPicker.min.js"></script> --}}
    <script type="text/javascript">
        $('.color').colorPicker(); // that's it
        // $().colorPicker.destroy(); // for singlePageApps
    </script>

@endsection
