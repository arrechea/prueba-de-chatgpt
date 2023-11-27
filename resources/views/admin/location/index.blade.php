@extends('admin.layout.master')
@section('content')
    <div class="main-container dashboard">
        <section class="dashboard__title">
            <div class="template__title has-icon">
                <h2><i class="material-icons">dashboard</i> <span>{{__('menu.dashboard')}}</span></h2>
            </div>
        </section>
        <div class="dashboard__container">
            @php($permiso_bi=\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MENU_BUSINESS_INTELLIGENCE,$location))
            @if($permiso_bi)
                <section class="dashboard__hero">
                    <div class="dashboard__purchases">
                        @include('widgets.purchases-location')
                    </div>
                    <div class="dashboard__reservations">
                        @include('widgets.reservations-location')
                    </div>
                </section>
            @endif
            <section class="dashboard__content @if(!$permiso_bi) is-fullWidth @endif">
                <div class="rooms">
                    <div class="rooms__header">
                        <div class="template__title has-icon is-dashboard">
                            <h3><i class="material-icons">crop_square</i> <span>{{__('location.Rooms')}}</span></h3>
                        </div>
                        @include('admin.catalog.table-dashboard')
                    </div>
            </section>
        </div>
    </div>
@endsection

@section('jsPreApp')
    <script>
        var addDataRoute = '{{route('admin.company.brand.locations.rooms.create',['company'=>$company,'brand'=>$brand,'location'=>$location])}}';
    </script>
@endsection
