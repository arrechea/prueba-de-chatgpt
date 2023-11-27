<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
@if(isset($location))
    {{--Location Modal de edición/creación de usuario--}}
    <div style="" class="modal modal-assign-giftcard" id="assign_modal"
         data-method="get" data-href="" data-url="{{route('admin.company.brand.locations.giftcards.assign',
         ['company'=>$company,'brand'=>$brand,'location'=>$location])}}">
        <div class="modal-content"></div>
        <div class="modal-footer" style="width: 96%;">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        </div>
    </div>

    <div id="LocationUser--editModal" style="width: 250px;height: 250px" class="User--assignmentRoles modal modalroles"
         data-method="get"
         data-href=""
         data-url="{{route('admin.company.brand.locations.users.edit',['company'=>$company,'brand'=>$brand,'location'=>$location])}}">
        <div class="modal-content">@cargando</div>
        <div class="modal-footer"></div>
    </div>

    <div id="LocationPurchase--createModal"></div>

    <div id="credits--userModal" class="modal modal-fixed-footer " data-method="get"
         data-href=""
{{--         style="width: 65% !important; height: 67% !important;"--}}
    >
        <div class="modal-content" style="text-align: left; padding:0 !important;">
        </div>
        <div class="modal-footer" style="{{!$isSaas ? 'width: 97% !important;' : ' ' }}">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>
        </div>
    </div>

    <div class="modal modal-fixed-footer modalreservationsUser" style="z-index: 1005 !important;"
         id="listasUsersR--userModal" data-method="get"
         data-href="">
        <div class="modal-content">
        </div>
        <div class="modal-footer">
            <a href="#!" style="margin-left: 24px" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>
        </div>
    </div>

    <div id="info--userModal" class="modal modal-fixed-footer" data-method="get"
         data-href="">
        <div class="modal-content">
        </div>
        <div class="modal-footer" style="width: 97% !important;">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>
        </div>
    </div>

    <div id="purchases--userModal" class="modal modal-fixed-footer" data-method="get"
         data-href=""
         style="width: 75% !important; height: 74% !important;">
        <div class="modal-content">
        </div>
        <div class="modal-footer" style="width: 97% !important;">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn ">{{__('credits.close')}}</a>
        </div>
    </div>

    <div id="lock--userModal" class="modal unblock-modal modal-fixed-footer" data-method="get"
         data-href="">
        <div class="modal-content">
        </div>
    </div>
@endif
