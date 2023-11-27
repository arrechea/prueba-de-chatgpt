<div id="gympass_filters" class="select2GafafitContainer1">
    <input hidden="hidden" value="{{$location->id}}" name="locations_id">

    <div class="row">
        <div class="input-field col s12 m3">
            <label class="active" for="status">
                {{__('roles.label-brand')}}
            </label>
            <select name="gympass_status" id="gympass_status" class=" select col-s3"
                    {{--                data-url="{{route('admin.company.roles.brands',['company'=>$company])}}"--}}
                    data-placeholder="{{__('roles.select-brand-placeholder')}}">
                <option value="">Todos</option>
                @foreach(\App\Models\GympassCheckin\GympassCheckin::getStatuses() as $slug=>$name)
                    <option value="{{$slug}}">{{$name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

{{--<script>--}}
{{--    jQuery(document).ready(function ($) {--}}
{{--        let dt_table = $('#gympass_status').closest('.datatable');--}}
{{--        if (dt_table.length) {--}}
{{--            let dt_id = dt_table.attr('id');--}}
{{--            let datatable = window[dt_id];--}}
{{--            if (datatable.length) {--}}
{{--                datatable.draw();--}}
{{--            }--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
