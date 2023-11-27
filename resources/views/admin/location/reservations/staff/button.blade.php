<a class="btn btn-floating tooltipped" href="#calendar_staff_{{$staff->id}}" data-position="bottom" data-delay="70" data-tooltip="{{__('reservations.Reservations')}}"><i
        class="material-icons">book</i></a>

<div id="calendar_staff_{{$staff->id}}" class="modal modal-fixed-footer modalcalendar" data-method="get"
     data-href="{{route('admin.company.brand.locations.reservations.staff.calendar',['company'=> $company, 'brand'=>$brand, 'location'=>$location,'staff'=>$staff])}}">
    <div class="modal-content" style="width: 96% !important;">
    </div>
</div>

