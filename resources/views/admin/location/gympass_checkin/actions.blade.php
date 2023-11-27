@if($checkin->isPending())
    <a
        class="btn btn-floating waves-effect waves-light tooltipped"
        href="#approve_checkin_modal--{{$checkin->id}}"
        data-position="top"
        data-delay="70"
        data-tooltip="{{__('gympass.checkinApprove')}}"><i
            class="material-icons ">check_circle</i></a>
@endif

@if($checkin->isPending())
    <a class="btn btn-floating waves-effect waves-light tooltipped"
       href="#reject_checkin_modal--{{$checkin->id}}"
       data-position="top"
       data-delay="70"
       data-tooltip="{{__('gympass.checkinReject')}}"
       data-href=""><i
            class="material-icons ">cancel</i></a>
@endif




<div id="approve_checkin_modal--{{$checkin->id}}" class="modal modal-small"
     style="max-height: 80%;">
    <div class="modal-content">
        <h5 class="header">{{__('gympass.checkinApproveQuestion')}}</h5>
        <form
            action="{{route('admin.company.brand.locations.gympass.checkin.process-checkin', ['company'=>$company,'brand' => $brand,'location'=>$location, 'checkin' => $checkin->id])}}"
            method="post"
            id="approve_checkin_form--{{$checkin->id}}"
            data-success-message="{{__('gympass.checkinApproved')}}"
        >
            {{csrf_field()}}

            <button
                type="submit"
                class="s12 modal-action modal-close waves-effect waves-green btn btndelete"
            >
                {{__('gympass.checkinAccept')}}
            </button>
        </form>
    </div>
</div>

<div id="reject_checkin_modal--{{$checkin->id}}" class="modal modal-small"
     style="max-height: 80%;">
    <div class="modal-content">
        <h5 class="header">{{__('gympass.checkinRejectQuestion')}}</h5>
        <form
            action="{{route('admin.company.brand.locations.gympass.checkin.reject-checkin', ['company'=>$company,'brand' => $brand,'location'=>$location, 'checkin' => $checkin->id])}}"
            method="post"
            id="approve_checkin_form--{{$checkin->id}}"
            data-success-message="{{__('gympass.checkinRejected')}}"
        >
            {{csrf_field()}}

            <button
                type="submit"
                class="s12 modal-action modal-close waves-effect waves-green btn btndelete"
            >
                {{__('gympass.checkinRejectButton')}}
            </button>
        </form>
    </div>
</div>


<script>
    jQuery(document).ready(function ($) {
        $('#approve_checkin_form--{{$checkin->id}},#reject_checkin_form--{{$checkin->id}}').on('submit', function (e) {
            e.preventDefault();
            let form = $(this);
            let data = form.serializeArray();
            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: data,
                success: function (data) {
                    let success_message = form.data('success-message');
                    Materialize.toast(success_message, 4000);
                },
                error: function (err) {
                    displayErrorsToast(err, "{{__('gympass.checkinApprovalError')}}");
                }
            }).always(function () {
                let dt_table = form.closest('.datatable');
                if (dt_table.length) {
                    let dt_id = dt_table.attr('id');
                    let datatable = window[dt_id];
                    if (datatable) {
                        setTimeout(function () {
                            datatable.draw();
                        }, 200);
                    }
                }
            });
        });
    })
</script>
