<form id="room-delete-form"
      action="{{route('admin.company.brand.locations.rooms.delete.post',['company'=>$company,'brand'=>$brand,'location'=>$location,'room'=> $room])}}"
      method="post">
    {{csrf_field()}}
    <input type="hidden" value="{{$room->id}}" name="id">
    <h5 class="" style="left: 35px;">{{__('messages.delete-room')}}</h5>

</form>
