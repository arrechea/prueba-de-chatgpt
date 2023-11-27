<?php
$delete_overbooking = \App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::OVERBOOKING_DELETE, $location) && !$meeting->isEnd();
?>
<tr class="user-overbooking" id="user-overbooking-{{$item->id}}">
    <td>{{$item->profile->first_name}}  {{$item->user->last_name}}</td>
    <td>{{$item->profile->email}}</td>
    <td>{{__("gender.{$item->profile->gender}")}}</td>
    <td>
        @if($delete_overbooking)
            <a class="btn btn-floating overbooking-user-delete-button"
               href="#delete-overbooking-user-modal-{{$item->id}}"
               data-id="{{$item->id}}"><i class="material-icons">delete</i></a>

            <div id="delete-overbooking-user-modal-{{$item->id}}"
                 class="modal modal-fixed-footer modal-delete-overbooking">
                <div class="modal-content" style="width: 90%;">
                    <form>
                        {{csrf_field()}}
                        <input hidden name="id" value="{{$item->id}}">
                        <h5 class="header"
                            style="left: 35px;">{{__('overbooking.MessageDeleteOverbooking')}}</h5>

                        <a class="s12 m6 modal-close waves-effect waves-green btn overbooking-delete-modal-button"
                           id="overbooking-delete-button-{{$item->id}}"
                           data-href="{{route('admin.company.brand.locations.overbooking.delete',['company'=>$company,'brand'=>$brand,'location'=>$location,'waitlist'=>$item])}}">
                            {{__('gafacompany.Delete')}}
                        </a>
                    </form>
                </div>
            </div>
        @endif
    </td>
</tr>
