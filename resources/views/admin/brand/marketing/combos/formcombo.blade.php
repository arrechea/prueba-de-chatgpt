<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{$urlForm}}" method="post" class="row" autocomplete="off"
      enctype="multipart/form-data"> {{--todo: falta la forma final del formulario  --}}
    @include('admin.common.alertas')
    @if(isset($combos))
        <input type="hidden" name="id" value="{{$combos->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m8">

        <h5 class="">{{__('marketing.GeneralData')}}</h5>
        <div class="col col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="text" class="input" name="name" id="name"
                               value="{{old('name', (isset($combos) ? $combos->name: ''))}}" required>
                        <label for="name">{{__('marketing.Tittle')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <label class="active" for="credit">
                            {{__('marketing.CreditType')}}</label>
                        <select id="credit"
                                style="width: 100%;"
                                name="credits_id"
                                required>
                            <option value="">--</option>
                            @foreach($credit_brands as $credit_brand)
                                <option
                                    value="{{$credit_brand->id}}" {!! $credit_brand->id==old('credits_id',$combos->credits_id ?? '' ) ? 'selected' : '' !!}>{{$credit_brand->name}}</option>
                            @endforeach

                            @foreach($creditsgf as $creditgf)
                                <?php
                                $credits_com = App\Models\Credit\CreditsBrand::select('*')->where('credits_id', $creditgf->id)->get();
                                $brands_c = App\Librerias\Credits\LibCredits::getCreditsBrandsGF($company->id, $credits_com);
                                ?>

                                <option
                                    value="{{$creditgf->id}}" {!! $creditgf->id==old('credits_id',$combos->credits_id ?? '' ) ? 'selected' : '' !!}>{{$creditgf->name}}
                                    <?php
                                    $brands_count = 0;
                                    ?>
                                    @foreach($brands_c as $brand_company)
                                        {{ $brands_count > 0? ",": "" }} {{$brand_company->name}}
                                        <?php $brands_count++; ?>
                                    @endforeach
                                </option>
                            @endforeach

                            @foreach($credits as $credit)
                                <?php
                                $credits_com = App\Models\Credit\CreditsBrand::select('*')->where('credits_id', $credit->id)->get();
                                $brands_c = App\Librerias\Credits\LibCredits::getCreditsBrandsGF($company->id, $credits_com);
                                ?>
                                <option
                                    value="{{$credit->id}}" {!! $credit->id==old('credits_id',$combos->credits_id ?? '' ) ? 'selected' : '' !!}>
                                    {{$credit->name}}
                                    <?php
                                    $brands_count = 0;
                                    ?>
                                    @foreach($brands_c as $brand_company)
                                        {{ $brands_count > 0? ",": "" }} {{$brand_company->name}}
                                        <?php $brands_count++; ?>
                                    @endforeach
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="credits" id="credits"
                               value="{{old('credits', (isset($combos) ? $combos->credits: ''))}}" required>
                        <label for="credits">{{__('marketing.credits')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="expiration_days" id="expiration_days"
                               value="{{old('expiration_days', (isset($combos) ? $combos->expiration_days: ''))}}">
                        <label for="expiration_days">{{__('marketing.expiration')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        @php( $assignedCategories = isset($combos) ? $combos->categories->pluck('id')->toArray() : [] )
                        <label class="active" for="categories">Categor&iacute;as</label>
                        <select name="categories[]" multiple="multiple" id="categories" style="width: 100%;"
                                class="select2-not">
                            <option value="" selected="" disabled></option>
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}" {!! in_array($category->id, $assignedCategories, true) ? 'selected' : '' !!}>
                                    {{ __($category->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l5">
                    <label class="header">{{__('marketing.Show')}}</label>
                    <div class="switch">
                        <label>
                            {{__('marketing.Hide')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('show','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($combos) && !$combos->hide_in_home ? 'checked' : '' !!}
                                   @endif
                                   name="show">
                            <span class="lever"></span>
                            {{__('marketing.show')}}
                        </label>
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <label class="header">{{__('marketing.ShowFront')}}</label>
                    <div class="switch">
                        <label>
                            {{__('marketing.Hide')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('showFront','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($combos) && !$combos->hide_in_front ? 'checked' : '' !!}
                                   @endif
                                   name="showFront">
                            <span class="lever"></span>
                            {{__('marketing.show')}}
                        </label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="order" id="order"
                               value="{{old('order',(isset($combos) ? $combos->order : 0))}}">
                        <label for="order">{{__('marketing.Order')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="price" id="prices"
                               value="{{old('price',isset($combos) ? $combos->price : 0)}}">
                        <label for="prices">{{__('marketing.Price')}}</label>
                    </div>

                </div>
            </div>
        </div>
        {{--<h5 class="">Nivel</h5>--}}
        {{--<div class="col col s12 m12 card-panel panelcombos">--}}
            {{--<div class="row">--}}
                {{--<div class="col s15 m15 l5">--}}
                    {{--<p>--}}
                        {{--<input type="radio" name="level" class="with-gap"--}}
                               {{--id="level_location"--}}
                               {{--value="location" {{isset($combos)&&$combos->level==='location' ? 'checked="checked"':''}}>--}}
                        {{--<label for="level_location">{{__('marketing.level.location')}}</label>--}}
                    {{--</p>--}}
                {{--</div>--}}
                {{--<div class="col s15 m15 l5">--}}
                    {{--<p>--}}
                        {{--<input type="radio" name="level" class="with-gap"--}}
                               {{--id="level_brand"--}}
                               {{--value="brand" {{isset($combos)&&$combos->level==='brand' ? 'checked="checked"':''}}>--}}
                        {{--<label for="level_brand">{{__('marketing.level.brand')}}</label>--}}
                    {{--</p>--}}
                {{--</div>--}}
                {{--<div class="col s15 m15 l5">--}}
                    {{--<p>--}}
                        {{--<input type="radio" name="level" class="with-gap"--}}
                               {{--id="level_company"--}}
                               {{--value="company" {{isset($combos)&&$combos->level==='company' ? 'checked="checked"':''}}>--}}
                        {{--<label for="level_company">{{__('marketing.level.company')}}</label>--}}
                    {{--</p>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

        <h5 class="">{{__('marketing.Description')}}</h5>
        <div class="col col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="text" class="input" name="short_description" id="short_description"
                               value="{{old('name', (isset($combos) ? $combos->short_description: ''))}}">
                        <label for="short_description">{{__('marketing.Description')}} {{__('marketing.Short')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="{{$isSaas ? 'input-field text-area' : ''}}col s12 m12 l9">
                    <label class="active" for="description">{{__('staff.Description')}}</label>
                    <textarea type="text" id="description" class="materialize-textarea" name="description"
                    >{{old('description',($combos->description ?? ''))}}</textarea>
                </div>
            </div>
        </div>

        <h5 class="">{{__('marketing.Discount')}}</h5>
        <div class=" col col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l2">
                    <p>
                        <input type="radio" id="price" name="discount_type" class="with-gap"
                               value="price" {{isset($combos)&&$combos->discount_type==='price' ? 'checked="checked"':''}}>
                        <label for="price">{{__('marketing.Price')}}</label>
                    </p>
                </div>

                <div class="col s12 m12 l2">
                    <p>
                        <input type="radio" id="percent" name="discount_type" class="with-gap"
                               value="percent" {{isset($combos)&&$combos->discount_type==='percent' ? 'checked="checked"':''}}>
                        <label for="percent">{{__('marketing.Percent')}}</label>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="discount_number" id="discount_number"
                               value="{{old('discount_number', (isset($combos) ? $combos->discount_number: '' ))}}">
                        <label for="discount_number">{{__('marketing.discountN')}}</label>
                    </div>
                </div>


                {{--<div class="col s12 m12 l3">--}}
                {{--<label for="currency"> {{__('company.Currency')}}--}}
                {{--<select id="currency" class="select2 select" data-name="currencies" name="currencies_id"--}}
                {{--style="width: 100%">--}}
                {{--<option value="">$</option>--}}
                {{--@foreach($currencies as $currency)--}}
                {{--<option value="{{$currency->id}}"--}}
                {{--@if (old('currencies_id', (isset($combos) ? $combos->currencies_id : '')) == $currency->id) selected="selected"@endif>{{__($currency->name)}}</option>--}}
                {{--@endforeach--}}
                {{--</select>--}}

                {{--</label>--}}
                {{--</div>--}}
                {{--</div>--}}

            </div>

            <div class="row">
                <div class="start-end-datepicker">
                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <input type="text" class=" time-start pck-pink" name="discount_from"
                                   id="discount_from"
                                   value="{{old('discount_from', (isset($combos) ? $combos->discount_from: ''))}}">
                            <label for="discount_from">{{{__('marketing.discountF')}}}</label>
                        </div>
                    </div>
                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <input type="text" class="calendar-date time-end pck-pink" name="discount_to"
                                   id="discount_to"
                                   value="{{old('discount_to', (isset($combos) ? $combos->discount_to: ''))}}">
                            <label for="discount_to">{{__('marketing.discountTo')}}</label>
                        </div>
                        {{--<div class="col s12">--}}
                        <p class="start-end-datepicker-alert"
                           style="color: var(--alert-color);font-size: x-small;margin-top:0"
                           hidden>{{__('timepicker.until-date-alert')}}</p>
                        {{--</div>--}}
                    </div>


                </div>
            </div>
        </div>

        <h5 class="">{{__('marketing.ReservationsConfiguration')}}</h5>
        <div class="col col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="reservations_min" id="reservations_min"
                               value="{{old('reservations_min', (isset($combos) ? $combos->reservations_min: ''))}}">
                        <label for="reservations_min">{{__('marketing.reservationsMin')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="reservations_max" id="reservations_max"
                               value="{{old('reservations_max', (isset($combos) ? $combos->reservations_max:''))}}">
                        <label for="reservations_max">{{__('marketing.reservationsMax')}}</label>
                    </div>
                </div>
            </div>
        </div>

        {{--<h5 class="">{{__('marketing.SubscriptionsConfiguration')}}</h5>--}}
        {{--<div class="col col s12 m12 card-panel panelcombos">--}}
        {{--<div class="row">--}}
        {{--<div class="col s12 m12 l7">--}}
        {{--<div class="switch">--}}
        {{--<label>--}}
        {{--{{__('marketing.NotSubscribable')}}--}}
        {{--<input type="checkbox"--}}
        {{--@if(!!old()) {!! old('can_subscribe','')==='on' ? 'checked' : '' !!}--}}
        {{--@else {!! isset($combos) && $combos->isSubscribable() ? 'checked' : '' !!}--}}
        {{--@endif--}}
        {{--name="can_subscribe" id="can_subscribe">--}}
        {{--<span class="lever"></span>--}}
        {{--{{__('marketing.Subscribable')}}--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--</div>--}}

        {{--<div class="col s12 m12 l3">--}}
        {{--<div class="input-field">--}}
        {{--<input type="number" class="input" name="number_of_tries" id="number_of_tries"--}}
        {{--value="{{old('number_of_tries', (isset($combos) ? $combos->number_of_tries : '3'))}}">--}}
        {{--<label for="number_of_tries">{{__('marketing.NumberOfTries')}}</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
    </div>
    <div class="col s12 m4">
        <div class="col s12 m10 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="{{$combos->pic??''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('marketing.CombosPhoto')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="pic"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="switch">
                    <label>
                        {{__('brand.Inactive')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($combos) && $combos->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('brand.Active')}}
                    </label>
                </div>
            </div>
            @if(isset($combos) && !$combos->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('marketing.ComboActiveWarning')}}</p>
                </div>
            @endif
        </div>

        {{--@if(isset($combos))--}}
        {{--<a class="waves-effect waves-light btn deep-purple lighten-3"--}}
        {{-- devuelve vista parcial de asignacion de roles para editar--}}
        {{--href="#applicable_services"--}}
        {{--style="margin: 0;">{{__('marketing.ApplicableServices')}}</a>--}}
        {{--@endif--}}

        <div class="col s12 m12 l7 edit-buttons input-field">
            <button type="submit"
                    class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                @if($isSaas)
                    <div>
                        <i class="fal fa-save"></i>
                        <span>{{__('brand.Save')}}</span>
                    </div>
                @else
                    <i class="material-icons right small">save</i>
                    {{__('brand.Save')}}
                @endif
            </button>
        </div>

        @if (isset($combos))
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMBOS_DELETE,$brand))
                <div class="col s12 m12 l7">
                    <a class="{{$isSaas ? 'BuqSaas-e-button is-delete' : 'waves-effect waves-light btn btnguardar'}}"
                       href="#eliminar_combo" style="{{$isSaas ? '' : 'background-color: grey'}}">
                        @if($isSaas)
                            <i class="far fa-times"></i>
                            <span>{{__('brand.Delete')}}</span>
                        @else
                            <i class="material-icons right small">clear</i>
                            {{__('brand.Delete')}}
                        @endif
                    </a>
                </div>
            @endif
        @endif
    </div>
</form>

@if (isset($combos))
    <div id="eliminar_combo" class="modal modal - fixed - footer modaldelete model--border-radius" data-method="get"
         data-href="{{route('admin.company.brand.marketing.combos.delete', ['company'=>$company,'brand' => $brand, 'combos' => $combos->id])}}">
        <div class="modal-content"></div>
    </div>
@endif

{{--@if(isset($combos))--}}
{{--<div id="applicable_services" class="User--assignmentRoles modal modal-fixed-footer modalroles"--}}
{{--data-method="get"--}}
{{--data-href="{{route('admin.company.brand.marketing.combos.services',[--}}
{{--'company'=>$company,--}}
{{--'brand'=>$brand,--}}
{{--'combos'=>$combos--}}
{{--])}}">--}}
{{--<form method="post"--}}
{{--action="{{route('admin.company.brand.marketing.combos.services.save',['company'=>$company,'brand'=>$brand,'combos'=>$combos])}}">--}}
{{--<div class="modal-content">@cargando</div>--}}
{{--<div class="modal-footer">--}}
{{--<a type="submit" id="services_form_button"--}}
{{--class="modal-action modal-close waves-effect waves-green btn">{{__('administrators.Save')}}</a>--}}
{{--</div>--}}
{{--</form>--}}
{{--</div>--}}
{{--@endif--}}

