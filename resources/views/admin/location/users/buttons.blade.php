{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::USER_EDIT,$profile))--}}
<a class="btn btn-floating waves-effect waves-light tooltipped" href="#" data-position="top"
   data-delay="70" id="userList--credits_button_{{$profile->id}}"
   data-tooltip="{{__('credits.Credits')}}"><i
        class="material-icons ">local_activity</i></a>
{{--@endif--}}
{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::USER_VIEW,$profile))--}}
<a class="btn btn-floating waves-effect waves-light tooltipped" href="#" data-position="top"
   data-delay="70" id="userList--info_button_{{$profile->id}}"
   data-tooltip="{{__('credits.userInfo')}}"
   data-href=""><i
        class="material-icons ">remove_red_eye</i></a>
{{--@endif--}}
<a href="#" class="btn btn-floating tooltipped" data-position="top" data-delay="70"
   data-tooltip="{{__('reservations.Reservations')}}" id="userList--listasUsersR_button_{{$profile->id}}"><i
        class="material-icons">book</i></a>


<a href="#" class="btn btn-floating tooltipped" data-position="top" data-delay="70"
   data-tooltip="{{__('credits.purchases')}}" id="userList--purchases_button_{{$profile->id}}"><i
        class="material-icons">monetization_on</i></a>

@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_EDIT,$location))
    <a class="btn btn-floating tooltipped" id="location-user-button-{{$profile->id}}"
       onclick="userButtonOnClick(this.event,{{$profile->id}})" data-position="top"
       data-delay="70"
       data-tooltip="users" data-user-id="{{$profile->id}}">
        <i class="material-icons">edit</i>
    </a>
@endif

{{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_UNBLOCK,$location) && $profile->blocked_reserve === true)--}}

{{--    <a href="#" class="btn btn-floating tooltipped" data-position="top" data-delay="70"--}}
{{--       data-tooltip="{{__('credits.unblock')}}" id="userList--lock_button_{{$profile->id}}"><i--}}
{{--            class="material-icons">lock_open</i></a>--}}

{{--@endif--}}


{{---------modals---------}}
{{--<div class="modal modal-fixed-footer modalreservationsUser" style="z-index: 1005 !important;"--}}
     {{--id="listasUsersR_{{$profile->id}}" data-method="get"--}}
     {{--data-href="">--}}
    {{--<div class="modal-content" style="width: 95% !important;">--}}
    {{--</div>--}}
    {{--<div class="modal-footer" style="width: 97% !important;">--}}
        {{--<a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>--}}
    {{--</div>--}}
{{--</div>--}}
{{--<div id="info_{{$profile->id}}" class="modal modal-fixed-footer" data-method="get"--}}
     {{--data-href="{{route('admin.company.brand.locations.users.info',['company'=>$company,'brand'=>$brand,'location'=>$location,'profile' => $profile])}}"--}}
     {{--style="width: 65% !important; height: 67% !important;">--}}
    {{--<div class="modal-content modal-inforUsers">--}}
    {{--</div>--}}
    {{--<div class="modal-footer" style="width: 97% !important;">--}}
        {{--<a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>--}}
    {{--</div>--}}
{{--</div>--}}
{{--<div id="credits_{{$profile->id}}" class="modal modal-fixed-footer" data-method="get"--}}
     {{--data-href="{{route('admin.company.brand.locations.users.credits',['company'=>$company,'brand'=>$brand,'location'=>$location,'profile' => $profile])}}"--}}
     {{--style="width: 65% !important; height: 67% !important;">--}}
    {{--<div class="modal-content" style="text-align: left; padding:0 !important;">--}}
    {{--</div>--}}
    {{--<div class="modal-footer" style="width: 97% !important;">--}}
        {{--<a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>--}}
    {{--</div>--}}
{{--</div>--}}
{{--<div id="purchases_{{$profile->id}}" class="modal modal-fixed-footer" data-method="get"--}}
     {{--data-href="{{route('admin.company.brand.locations.users.purchase',['company'=>$company,'brand'=>$brand,'location'=>$location,'profile' => $profile])}}"--}}
     {{--style="width: 65% !important; height: 67% !important;">--}}
    {{--<div class="modal-content" style="text-align: left; padding:0 !important;">--}}
    {{--</div>--}}
    {{--<div class="modal-footer" style="width: 97% !important;">--}}
        {{--<a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>--}}
    {{--</div>--}}
{{--</div>--}}

<script>
    $(document).ready(function () {
        InitModals($('#info_{{$profile->id}}'));
        InitModals($('#listasUsersR_{{$profile->id}}'));
        $('.tooltipped').tooltip({delay: 50});

        $('#userList--credits_button_{{$profile->id}}').on('click', function () {
            UserListModalOpen("{{route('admin.company.brand.locations.users.credits',['company'=>$company,'brand'=>$brand,'location'=>$location,'profile' => $profile])}}", 'credits--userModal')
        });

        $('#userList--listasUsersR_button_{{$profile->id}}').on('click', function () {
            UserListModalOpen("{{route('admin.company.brand.locations.reservations.users.reservation', ['company'=> $company, 'brand'=>$brand, 'location'=>$location,'profile'=>$profile])}}", 'listasUsersR--userModal')
        });

        $('#userList--info_button_{{$profile->id}}').on('click', function () {
            UserListModalOpen("{{route('admin.company.brand.locations.users.info',['company'=>$company,'brand'=>$brand,'location'=>$location,'profile' => $profile])}}", 'info--userModal')
        });

        $('#userList--purchases_button_{{$profile->id}}').on('click', function () {
            UserListModalOpen("{{route('admin.company.brand.locations.users.purchase',['company'=>$company,'brand'=>$brand,'location'=>$location,'profile' => $profile])}}", 'purchases--userModal')
        });

        {{--$('#userList--lock_button_{{$profile->id}}').on('click', function () {--}}
        {{--    console.log('bloqueo');--}}
        {{--    UserListModalOpen("{{route('admin.company.brand.locations.users.unblock.confirm',['company'=>$company,'brand'=>$brand,'location'=>$location,'profile' => $profile])}}", 'lock--userModal')--}}
        {{--});--}}
    })
</script>
