@yield('appPost')
<script>
    window.imageLang = {
        size: "{{__('image.size')}}",
        dimensions: "{{__('image.dimensions')}}"
    };
    window.imageRoutes = {
        size: "{{route('image.size')}}"
    }
</script>
<!-- Scripts -->
<script type="text/javascript" src="{{ asset('js/all.js') }}"></script>
{{--Datatables--}}
<script src="{{ asset('js/vendor/datatable/jquery.dataTables.min.js') }}"></script>
{{--End Datatables--}}
{{--Init FullCalendar--}}
<script src='{{asset('js/vendor/moment/moment.min.js')}}'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js"></script>
<script src='{{asset('js/vendor/fullcalendar/fullcalendar.js')}}'></script>
<script src='{{asset('js/vendor/fullcalendar/theme-chooser.js')}}'></script>
{{--End FullCalendar--}}

@yield('jsPreApp')

{{-- APP AND INIT --}}
<script src="{{ asset('js/forgeapp.js') }}"></script>
<script src="{{ asset('js/init.js') }}"></script>
<script src="{{ mixGafaFit('js/common.js') }}"></script>
<script src="{{asset('js/forge.js')}}"></script>
@include('common.change-language-script')
<script>
    function getLoadingImage() {
        return '<div class="loading"><img class="CreateReservationFancy--processing" width="200" src="{{asset('images/processing.gif')}}" alt=""></div>';
    }
</script>

{{--Timezone--}}
<script>
    window.guest_timezone = !!moment.tz ? moment.tz.guess() : '';
    @if(isset($brand))
        window.brand_timezone = '{{$brand->getTimezone()}}';
    window.brand_time = "{{$brand->now()}}";
    @endif
        window.system_time = "{{\Carbon\Carbon::now()}}";
    window.system_timezone = '{{config('app.timezone')}}';
    $('.calendar-date').on('mousedown', function (event) {
        event.preventDefault();
    });

    $('.dropdown-content .select-dropdown').on('mousedown', function (event) {
        event.preventDefault();
    });

    $('.select-dropdown').on('mousedown', function (event) {
        event.preventDefault();
    });

    $('body').on('click', '#discount_from,#discount_to,.input-field .picker__input,.picker__input,.calendar-date', function (event) {
        event.preventDefault();
    });

    $('body').on('mousedown', '#discount_from,#discount_to,.input-field .picker__input,.picker__input,.calendar-date', function (event) {
        event.preventDefault();
    });
</script>

@php
    $user=auth()->user();
    $email = $user->email;
    $profile=$user->getProfileInThisCompany();
    $name=$profile ? $profile->first_name : $user->name;
@endphp

<script>
    window.intercomSettings = {
        api_base: 'https://api-iam.intercom.io/',
        app_id: 'doykmkmb',
        name: "{{$name}}", // Full name
        email: "{{$email}}", // Email address
        created_at: "{{$user->created_at}}" // Signup date as a Unix timestamp
    };
</script>
<script src="{{ asset('js/intercom-helper.min.js') }}"></script>

