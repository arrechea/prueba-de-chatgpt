<div class="reservationWidget widget">
    <div class="widget__back"></div>
    <div class="widget__icon">
        <i class="material-icons">event</i>
    </div>
    <div class="widget__data">
        <h3 class="widget__data-title">{{__('reservations.ReservationMonth')}}</h3>
        <p class="widget__data-quantity">{{$reservationNumber}}</p>
    </div>
    <a class="widget__link" href="{{route('admin.company.brand.locations.metrics.reservations.index', ['company'=>$company, 'brand'=>$brand, 'location'=>$location])}}"></a>
    <a class="widget__add" href="{{route('admin.company.brand.locations.reservations.create', ['company' => $company,'brand'=>$brand, 'location'=>$location])}}">
        <i class="material-icons ">add</i><span> {{__('reservations.News')}}</span>
    </a>
</div>
