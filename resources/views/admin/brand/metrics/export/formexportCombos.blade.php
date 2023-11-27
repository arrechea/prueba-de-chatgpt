
<input hidden name="brands_id" value="{{$brand->id}}">
<input hidden name="companies_id" value="{{$company->id}}">
<div class="period-selectors col s12 m8 l6">
    <div class="input-field col s5">
        <label for="start">{{__('metrics.start')}}</label>
        <input type="text" id="start" class="calendar-date pck-pink" name="start"
               value="{{(Carbon\Carbon::now()->subWeek() ?? '')}}"/>
    </div>
    <div class="col s1">{{__('metrics.to')}}</div>
    <div class="input-field col s5">
        <label for="end">{{__('metrics.end')}}</label>
        <input type="text" id="end" class="calendar-date pck-pink" name="end"
               value="{{(Carbon\Carbon::now() ?? '')}}"/>
    </div>
</div>

<p>
    <a class="btn btn-small waves-effect waves-light" type="submit"
      id="exportCombo" href="#">
        <span>{{__('metrics.search')}}</span>

    </a>
</p>

@section('jsPostApp')
    @parent
    <script>
        $(document).ready(function () {

            $('#exportCombo').on('click', function (e) {
                let start_date = $('#start').val();
                let end_date = $('#end').val();

                if (start_date) {
                    let base_url = "{{route('admin.company.brand.metrics.export.export.combo',[
                            'company'=>$company,
                            'brand'=>$brand,
                            'start'=>"_start_",
                            'end'=>"_end_"
                        ])}}";

                    base_url = base_url.replace("_start_", start_date).replace("_end_", end_date);

                    var pastURL = location.href;
                    window.open(base_url, '_blank');
                }
            });
        })
    </script>
@endsection
