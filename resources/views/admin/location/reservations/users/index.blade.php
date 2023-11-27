@extends('admin.layout.master')
@section('content')
    <div class="main-container">

        @if(Auth::user()->isA('gafa-saas'))

            <div class="BuqSaas-l-list">
                <div class="BuqSaas-l-list__header">
                    <div class="BuqSaas-c-sectionTitle">
                        <h2>Reservaciones de: <strong>{{($location->name)}}</strong></h2>
                    </div>
                    <div class="BuqSaas-c-nav is-list">
                        <div class="BuqSaas-c-nav__filter">
                            @include('admin.location.reservations.tabs-saas')
                        </div>
                        <div class="BuqSaas-c-nav__add">
                            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::RESERVATION_CREATE, $location))
                                <a  class="BuqSaas-e-button is-add"
                                    href="{{route('admin.company.brand.locations.reservations.create', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
                                    <i class="material-icons small">add</i>{{__('common.add')}}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="BuqSaas-l-list__body is-usersReservation">
                    @include('admin.catalog.table')
                </div>
            </div>
        @else
            @include('admin.location.reservations.tabs')
            <div class="row">
                <div class="card-panel radius--forms">
                    <h5 class="header">{{__('reservations.Users')}}</h5>
                    <div class="card-panel panelcombos">
                        @include('admin.catalog.table')
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection


@section('css')
    <link rel='stylesheet' href="{{url('js/vendor/fullcalendar/fullcalendar.css')}}"/>
@endsection

@section('jsPostApp')
    @parent
    <script src='//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js'></script>
    <script src="{{asset('plugins/fullcalendar/locale/es.js')}}"></script>
    <script src="{{asset('js/vendor/fullcalendar/fullcalendar.js')}}"></script>
    <script src="{{asset('js/calendar/calendar.js')}}"></script>

    @include('admin.location.calendar.jsOptions')
@endsection
