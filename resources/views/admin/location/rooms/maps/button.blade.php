@if(\App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::MAPS_EDIT,$location))
    <a class="btn btn-floating waves-effect waves-light"
       href="{{route('admin.company.brand.locations.room-maps.edit',['company'=>$company,'brand'=>$brand,'location'=>$location,'maps'=>$maps->id])}}"><i
            class="material-icons">mode_edit</i></a>
@endif

<a id="Clone_map" class="btn btn-floating waves-effect waves-light "
   href="#clone_{{$maps->id}}"><i
        class="material-icons">control_point_duplicate</i></a>
<div style="" class="modal modal-fixed-footer modalclone modaldelete" id="clone_{{$maps->id}}">
    <div class="modal-content" style="width: auto !important;">
        <div class="row">
            <h5 class="header">{{__('maps.clone-message')}}</h5>
        </div>
    </div>
    <div class="modal-footer second-modal-footer" >
        <div class="row" style="margin-top: 20px">
            <div class="">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                   href="#"> <i class="material-icons small">clear</i>
                    {{__('brand.Cancel')}}</a>
                <a
                    href="{{route('admin.company.brand.locations.room-maps.clone.map', [
                        'company'  => $company,
                        'brand'    => $brand,
                        'location' => $location,
                        'maps'     => $maps,
                    ])}}"
                    class="modal-action waves-effect waves-green btn maps--clone edit-button">
                    <i class="material-icons small">save_alt</i>
                    {{__('maps.clone')}}
                </a>
            </div>
        </div>
    </div>
</div>
