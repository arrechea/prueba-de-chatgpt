<a class="reservationWidget widget" href="{{route('admin.company.brand.metrics.reservations.index', ['company'=>$company, 'brand'=>$brand])}}">
    <div class="widget__back"></div>
    <div class="widget__icon">
        <i class="material-icons">event</i>
    </div>
    <div class="widget__data">
        <h3 class="widget__data-title">{{__('reservations.ReservationMonth')}}</h3>
        <p class="widget__data-quantity">{{$reservationNumber}}</p>
    </div>
</a>
