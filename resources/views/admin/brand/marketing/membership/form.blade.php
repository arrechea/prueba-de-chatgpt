<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{$urlForm}}" method="post" class="row" autocomplete="off"
      enctype="multipart/form-data"> {{--todo: falta la forma final del formulario  --}}
    @include('admin.common.alertas')
    @if(isset($membership))
        <input type="hidden" name="id" value="{{$membership->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m8">
        <h5 class="">{{__('marketing.GeneralData')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="text" class="input" name="name" id="name"
                               value="{{old('name', (isset($membership) ? $membership->name: ''))}}" required>
                        <label for="name">{{__('marketing.Tittle')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <label class="active" for="credit">
                            {{__('marketing.CreditType')}} </label>
                        <select id="credit"
                                style="width: 100%;"
                                name="credits_id"
                                required>
                            <option value="">--</option>
                            @foreach($creditMembership as $credit_brand)
                                <option
                                    value="{{$credit_brand->id}}" {!! $credit_brand->id==old('credits_id', isset($membership) && $membership->credits->count()>0 ? $membership->credits()->first()->id : '' ) ? 'selected' : '' !!}>{{$credit_brand->name}}
                                </option>
                            @endforeach
                            @foreach($creditsgf as $creditgf)
                                <?php
                                $credits_com = App\Models\Credit\CreditsBrand::select('*')->where('credits_id', $creditgf->id)->get();
                                $brands_c = App\Librerias\Credits\LibCredits::getCreditsBrandsGF($company->id, $credits_com);
                                ?>

                                <option
                                    value="{{$creditgf->id}}" {!! $creditgf->id==old('credits_id', isset($membership) && $membership->credits->count()>0 ? $membership->credits()->first()->id : '' ) ? 'selected' : '' !!}>{{$creditgf->name}}
                                    <?php
                                    $brands_count = 0;
                                    ?>
                                    (@foreach($brands_c as $brand_company)
                                        <span>{{ $brands_count > 0? ",": "" }} {{$brand_company->name}}</span>
                                        <?php $brands_count++; ?>
                                    @endforeach
                                     )
                                </option>
                            @endforeach
                            @foreach($credits as $credit)

                                <?php
                                $credits_com = App\Models\Credit\CreditsBrand::select('*')->where('credits_id', $credit->id)->get();
                                $brands_c = App\Librerias\Credits\LibCredits::getCreditsBrandsGF($company->id, $credits_com);
                                ?>

                                <option
                                    value="{{$credit->id}}" {!! $credit->id==old('credits_id', isset($membership) && $membership->credits->count()>0 ? $membership->credits()->first()->id : '' ) ? 'selected' : '' !!}>
                                    {{$credit->name}}
                                    <?php
                                    $brands_count = 0;
                                    ?>
                                    @foreach($brands_c as $brand_company)
                                        <span>{{ $brands_count > 0? ",": "" }} {{$brand_company->name}}</span>
                                        <?php $brands_count++; ?>
                                    @endforeach
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


            </div>


            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="expiration_days" id="expiration_days"
                               value="{{old('expiration_days', (isset($membership) ? $membership->expiration_days: ''))}}">
                        <label for="expiration_days">{{__('marketing.expiration')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        @php( $assignedCategories = isset($membership) ? $membership->categories->pluck('id')->toArray() : [] )
                        <label for="categories" class="active">Categor&iacute;as</label>
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
                                   @else {!! isset($membership) && !$membership->hide_in_home ? 'checked' : '' !!}
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
                                   @else {!! isset($membership) && !$membership->hide_in_front ? 'checked' : '' !!}
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
                               value="{{old('order',(isset($membership) ? $membership->order : 0))}}">
                        <label for="order">{{__('marketing.Order')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="price" id="prices"
                               value="{{old('price',isset($membership) ? $membership->price : 0)}}">
                        <label for="prices">{{__('marketing.Price')}}</label>
                    </div>

                </div>
            </div>
        </div>
        <h5 class="">Nivel</h5>

        <div class="col col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s15 m15 l5">
                    <p>
                        <input type="radio" name="level" class="with-gap"
                               id="level_location"
                               value="location" {{isset($membership)&&$membership->level==='location' ? 'checked="checked"':''}}>
                        <label for="level_location">{{__('marketing.level.location')}}</label>
                    </p>
                </div>
                <div class="col s15 m15 l5">
                    <p>
                        <input type="radio" name="level" class="with-gap"
                               id="level_brand"
                               value="brand" {{isset($membership)&&$membership->level==='brand' ? 'checked="checked"':''}}>
                        <label for="level_brand">{{__('marketing.level.brand')}}</label>
                    </p>
                </div>
                {{--<div class="col s15 m15 l5">--}}
                {{--<p>--}}
                {{--<input type="radio" name="level" class="with-gap"--}}
                {{--id="level_company"--}}
                {{--value="company" {{isset($membership)&&$membership->level==='company' ? 'checked="checked"':''}}>--}}
                {{--<label for="level_company">{{__('marketing.level.company')}}</label>--}}
                {{--</p>--}}
                {{--</div>--}}
            </div>
        </div>

        <h5 class="">{{__('marketing.Description')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="text" class="input" name="short_description" id="short_description"
                               value="{{old('name', (isset($membership) ? $membership->short_description: ''))}}">
                        <label for="short_description">{{__('marketing.Description')}} {{__('marketing.Short')}}</label>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col s12 m12 l9">
                    <div class="{{$isSaas ? 'input-field text-area' : ''}}">
                        <label class="active" for="description">{{__('staff.Description')}}</label>
                        <textarea type="text" id="description" class="materialize-textarea" name="description"
                        >{{old('description',($membership->description ?? ''))}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="">{{__('marketing.Discount')}}</h5>
        <div class=" col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l2">
                    <p>
                        <input type="radio" id="price" name="discount_type" class="with-gap"
                               value="price" {{isset($membership)&&$membership->discount_type==='price' ? 'checked="checked"':''}}>
                        <label for="price">{{__('marketing.Price')}}</label>
                    </p>
                </div>

                <div class="col s12 m12 l2">
                    <p>
                        <input type="radio" id="percent" name="discount_type" class="with-gap"
                               value="percent" {{isset($membership)&&$membership->discount_type==='percent' ? 'checked="checked"':''}}>
                        <label for="percent">{{__('marketing.Percent')}}</label>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="discount_number" id="discount_number"
                               value="{{old('discount_number', (isset($membership) ? $membership->discount_number: '' ))}}">
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
                {{--@if (old('currencies_id', (isset($membership) ? $membership->currencies_id : '')) == $currency->id) selected="selected"@endif>{{__($currency->name)}}</option>--}}
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
                            <input type="text" class="calendar-date time-start pck-pink" name="discount_from"
                                   id="discount_from"
                                   value="{{old('discount_from', (isset($membership) ? $membership->discount_from: ''))}}">
                            <label for="discount_from">{{{__('marketing.discountF')}}}</label>
                        </div>
                    </div>
                    <div class="col s12 m12 l5">
                        <div class="input-field">
                            <input type="text" class="calendar-date time-end pck-pink" name="discount_to"
                                   id="discount_to"
                                   value="{{old('discount_to', (isset($membership) ? $membership->discount_to: ''))}}">
                            <label for="discount_to">{{__('marketing.discountTo')}}</label>
                        </div>
                    </div>
                    <div class="col s12">
                        <p class="start-end-datepicker-alert"
                           style="color: var(--alert-color);font-size: x-small;margin-top:0"
                           hidden>{{__('timepicker.until-date-alert')}}</p>
                    </div>
                </div>
            </div>


        </div>

        <h5 class="">{{__('marketing.ReservationsConfiguration')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="reservations_min" id="reservations_min"
                               value="{{old('reservations_min', (isset($membership) ? $membership->reservations_min: ''))}}">
                        <label for="reservations_min">{{__('marketing.reservationsMin')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="reservations_max" id="reservations_max"
                               value="{{old('reservations_max', (isset($membership) ? $membership->reservations_max:''))}}">
                        <label for="reservations_max">{{__('marketing.reservationsMax')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="meeting_max_reservation" id="meeting_max_reservation"
                               value="{{old('meeting_max_reservation', (isset($membership) ? $membership->meeting_max_reservation:''))}}"
                               required>
                        <label for="meeting_max_reservation">{{__('marketing.meetingMax')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" id="reservations_limit" name="reservations_limit"
                               value="{{old('reservations_limit', (isset($membership) ? $membership->reservations_limit: ''))}}">
                        <label for="reservations_limit">{{__('marketing.limit')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" id="reservations_limit_daily" name="reservations_limit_daily"
                               value="{{old('reservations_limit_daily', (isset($membership) ? $membership->reservations_limit_daily: ''))}}">
                        <label for="reservations_limit_daily">{{__('marketing.limit_daily')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" id="total_purchase" name="total_purchase"
                               value="{{old('total_purchase', (isset($membership) ? $membership->total_purchase: 0))}}"
                               disabled>
                        <label for="total_purchase">{{__('marketing.totalPurchase')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" id="global_purchase" name="global_purchase"
                               value="{{old('global_purchase', (isset($membership) ? $membership->global_purchase: 999999999999))}}">
                        <label for="global_purchase">{{__('marketing.global_purchase')}}</label>
                    </div>
                </div>

            </div>
        </div>

        <h5 class="">{{__('marketing.SubscriptionsConfiguration')}}</h5>
        <div class="col col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="switch">
                        <label>
                            {{__('marketing.NotSubscribable')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('can_subscribe','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($membership) && $membership->isSubscribable() ? 'checked' : '' !!}
                                   @endif
                                   name="can_subscribe" id="can_subscribe">
                            <span class="lever"></span>
                            {{__('marketing.Subscribable')}}
                        </label>
                    </div>
                </div>

                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <input type="number" class="input" name="number_of_tries" id="number_of_tries"
                               value="{{old('number_of_tries', (isset($membership) ? $membership->number_of_tries : '3'))}}">
                        <label for="number_of_tries">{{__('marketing.NumberOfTries')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m4">
        <div class="col s12 m10 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="{{$membership->pic??''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('marketing.MemberPhoto')}}</h5>
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
                               @else {!! isset($membership) && $membership->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('brand.Active')}}
                    </label>
                </div>
            </div>
            @if(isset($membership) && !$membership->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('marketing.MembershipActiveWarning')}}</p>
                </div>
            @endif
        </div>

        {{--@if(isset($membership))--}}
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

        @if (isset($membership))
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::MEMBERSHIP_DELETE,$brand))
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
            <div class="col s12 m12 l7">
                <a class="waves-effect waves-light btn btnguardar"
                   href="#actualizar_membresias" style="background-color: silver">
                    @if($isSaas)
                        <span>{{__('brand.UpdateMemberships')}}</span>
                    @else
                        {{__('brand.UpdateMemberships')}}
                    @endif
                </a>
            </div>
        @endif
    </div>
</form>

@if (isset($membership))
    <div id="eliminar_combo" class="modal modal - fixed - footer modaldelete model--border-radius" data-method="get"
         data-href="{{route('admin.company.brand.marketing.membership.delete', ['company'=>$company,'brand' => $brand, 'combos' => $membership->id])}}">
        <div class="modal-content"></div>
    </div>
    <div
        id="actualizar_membresias"
        class="modal modal - fixed - footer model--border-radius modaldelete"
    >
        <div class="modal-content">
            <h5 class="header">{{__('brand.UpdateMemberships.title')}}</h5>
            <p>{{__('brand.UpdateMemberships.text')}}</p>
            <form
                action="{{route('admin.company.brand.marketing.membership.sync', ['company'=>$company,'brand' => $brand, 'membership' => $membership->id])}}"
                method="post"
            >
                {{csrf_field()}}

                <button
                    type="submit"
                    class="s12 modal-action modal-close waves-effect waves-green btn btndelete"
                >
                    {{__('brand.UpdateMemberships')}}
                </button>
            </form>
        </div>
    </div>
@endif

{{--@if(isset($membership))--}}
{{--<div id="applicable_services" class="User--assignmentRoles modal modal-fixed-footer modalroles"--}}
{{--data-method="get"--}}
{{--data-href="{{route('admin.company.brand.marketing.membership.services',[--}}
{{--'company'=>$company,--}}
{{--'brand'=>$brand,--}}
{{--'membership'=>$membership--}}
{{--])}}">--}}
{{--<div class="modal-content">@cargando</div>--}}
{{--<div class="modal-footer">--}}
{{--<a type="submit" id="services_form_button"--}}
{{--class="modal-action modal-close waves-effect waves-green btn">{{__('administrators.Save')}}</a>--}}
{{--</div>--}}

{{--</div>--}}
{{--@endif--}}
