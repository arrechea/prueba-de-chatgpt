<div class="Reservation--users">
    <a href="#listasUsersR_{{$profile->id}}" class="btn btn-floating tooltipped" data-position="bottom" data-delay="70"
       data-tooltip="{{__('reservations.Reservations')}}"><i
            class="material-icons">book</i></a>
    <div class="modal modal-fixed-footer modalreservationsUser" style="z-index: 1005 !important;"
         id="listasUsersR_{{$profile->id}}" data-method="get"
         data-href="{{route('admin.company.brand.locations.reservations.users.reservation', ['company'=> $company, 'brand'=>$brand, 'location'=>$location,'profile'=>$profile])}}">
        <div class="modal-content" style="width: 95% !important;">

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        InitModals($('#listasUsersR_{{$profile->id}}'));
    })
</script>
