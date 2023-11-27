<input hidden name="brands_id" value="{{$brand->id}}" />
<input hidden name="companies_id" value="{{$company->id}}" />
<div class="period-selectors col s12 m8 l6" id="exportMembership-form">
    <div class="input-field col s5">
        <label for="start">{{ __("metrics.start") }}</label>
        <input
            type="text"
            id="start"
            class="calendar-date pck-pink"
            name="start"
            value="{{(Carbon\Carbon::now()->subWeek() ?? '')}}"
        />
    </div>
    <div class="col s1">{{ __("metrics.to") }}</div>
    <div class="input-field col s5">
        <label for="end">{{ __("metrics.end") }}</label>
        <input
            type="text"
            id="end"
            class="calendar-date pck-pink"
            name="end"
            value="{{(Carbon\Carbon::now() ?? '')}}"
        />
    </div>
</div>

<p>
    <a
        id="exportMembership"
        class="waves-effect waves-light btn btnguardar"
        href="#"
        ><i class="material-icons right small">save</i
        >{{ __("metrics.search") }}</a
    >
</p>

@section('jsPostApp') @parent
<script>
    $(document).ready(function () {

        $('#exportMembership').on('click', function (e) {
            let start_date = $('#start').val();
            let end_date = $('#end').val();

            if (start_date) {
                let base_url = "{{route('admin.company.brand.metrics.export.export.membership',[
                    'company'=>$company,
                    'brand'=>$brand,
                    'start'=>"_start_",
                    'end'=>"_end_"
                ])}}";

                base_url = base_url.replace("_start_", start_date).replace("_end_", end_date);

                window.open(base_url, '_blank');
            }
        });
    })
</script>
@endsection
