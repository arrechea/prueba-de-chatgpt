
<div class="model--border-radius">
    <form method="post" enctype="multipart/form-data" id="credits_form">
        {{csrf_field()}}
        <h5 class="">{{__('marketing.ApplicableCredits')}}</h5>
        <div id="ApplicableCredits" class="panelcombos col panelcombos_full">
            <ul>
                @foreach($credits as $credit)

                    <?php
                    $selected = in_array($credit->id, $discount_credits->pluck('id')->toArray());
                        $credits_com = App\Models\Credit\CreditsBrand::select('*')->where('credits_id', $credit->id)->get();
                        $brands_c = App\Librerias\Credits\LibCredits::getCreditsBrandsGF($company->id, $credits_com);
                    ?>
                    <li>
                        <div class="col s12 m6 l6">
                            <input type="checkbox" class="service_checkbox" data-id="{{$credit->id}}"
                                   id="credits[{{$credit->id}}][active]"
                                   name="credits[{{$credit->id}}][active]"
                                {!! $selected ? 'checked="checked"' : '' !!}>
                            <label for="credits[{{$credit->id}}][active]">{{$credit->name}}
                                <?php
                                    $brands_count = 0;
                                ?>
                               (@foreach($brands_c as $brand_company)
                                    <span>{{ $brands_count > 0? ",": "" }} {{$brand_company->name}}</span>
                                    <?php $brands_count++; ?>
                                @endforeach
                                )
                            </label>
                            <input hidden name="credits[{{$credit->id}}][id]" value="{{$credit->id}}">

                        </div>
                    </li>
                @endforeach

                    @foreach($creditsgf as $creditgf)

                        <?php
                        $selected = in_array($creditgf->id, $discount_credits->pluck('id')->toArray());
                        $credits_com = App\Models\Credit\CreditsBrand::select('*')->where('credits_id', $creditgf->id)->get();
                        $brands_c = App\Librerias\Credits\LibCredits::getCreditsBrandsGF($company->id, $credits_com);
                        ?>
                        <li>
                            <div class="col s12 m6 l6">
                                <input type="checkbox" class="service_checkbox" data-id="{{$creditgf->id}}"
                                       id="credits[{{$creditgf->id}}][active]"
                                       name="credits[{{$creditgf->id}}][active]"
                                    {!! $selected ? 'checked="checked"' : '' !!}>
                                <label for="credits[{{$creditgf->id}}][active]">{{$creditgf->name}}
                                    <?php
                                    $brands_count = 0;
                                    ?>
                                    (@foreach($brands_c as $brand_company)
                                        <span>{{ $brands_count > 0? ",": "" }} {{$brand_company->name}}</span>
                                        <?php $brands_count++; ?>
                                    @endforeach
                                    )
                                </label>
                                <input hidden name="credits[{{$creditgf->id}}][id]" value="{{$creditgf->id}}">

                            </div>
                        </li>
                    @endforeach

                @foreach($creditsBrand as $creditBrand)

                    <?php
                        $selected = in_array($creditBrand->id, $discount_credits->pluck('id')->toArray());
                    ?>
                    <li>
                        <div class="col s12 m6 l6">
                            <input type="checkbox" class="service_checkbox" data-id="{{ $creditBrand->id}}"
                                id="credits[{{ $creditBrand->id}}][active]"
                                name="credits[{{ $creditBrand->id}}][active]"
                                {!! $selected ? 'checked="checked"' : '' !!}>
                            <label for="credits[{{ $creditBrand->id}}][active]">{{ $creditBrand->name}}
                            <input hidden name="credits[{{ $creditBrand->id}}][id]" value="{{ $creditBrand->id}}">

                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </form>
</div>

