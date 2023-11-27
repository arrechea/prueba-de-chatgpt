<a href="#calendar_room_{{$room->id}}" class="btn btn-floating tooltipped" data-position="bottom" data-delay="70"
   data-tooltip="{{__('reservations.Reservations')}}"><i
        class="material-icons">book</i></a>
<div id="calendar_room_{{$room->id}}" class="modal modal-fixed-footer modalcalendar" data-method="get"
     data-href="{{route('admin.company.brand.locations.reservations.room.calendar',['company'=> $company, 'brand'=>$brand, 'location'=>$location,'room'=>$room])}}">
    <div class="modal-content" style="width: 96% !important;">
    </div>
</div>
