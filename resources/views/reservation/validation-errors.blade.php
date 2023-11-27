<div id="CreateReservationFancyTemplate--Block">
    <div id="CreateReservationFancyTemplate--Close">
        <img src="{{ url('images/fancy/close.svg') }}">
    </div>
    @include('admin.layout.colors')
    <?php $css = $location->brand->map_css??'reservation-template.css';?>
    <link rel="stylesheet" href="{{mixGafaFit("/css/admin/{$css}")}}"/>
    <link rel="stylesheet" href="{{mixGafaFit('/css/admin/reservation-extra.css')}}"/>
    {{--  <link rel="stylesheet" href="{{mixGafaFit('/css/admin/reservation-responsive.css')}}"/>  --}}
    <div id="CreateReservationFancyTemplate">
        <div id="CreateReservationFancyTemplate--errors">
            <h3 class="CreateReservationFancy--title">{{__('reservations.sorry.notValidForm')}}</h3>
            <ul>
                @foreach ($errors->toArray() as $error)
                    @foreach ($error as $string)
                        <li>{{$string}}</li>
                    @endforeach
                @endforeach
            </ul>
            {{--  <button id="CreateReservationFancy--Block--exit" class="AppReservation--button AppReservation--button--next"
                    type="button">
                {{__('reservations.sorry.exit')}}
            </button>  --}}
        </div>
    </div>
    <div id="CreateReservationFancyTemplate--Back"></div>
    <script>

        $('#CreateReservationFancyTemplate--Close').on('click', function () {
            $('#CreateReservationFancyTemplate--Block').remove();
        });
    </script>
</div>
