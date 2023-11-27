<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{--$urlForm--}}" method="post" class="row" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($discountCode))
        <input type="hidden" name="id" value="{{$discountCode->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m8">
        <h5 class="">{{__('discounts.GeneralData')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l8">
                    <div class="input-field">
                        <input type="text" class="input" name="code" id="code"
                               value="{{old('code', (isset($discountCode) ? $discountCode->code: ''))}}" required>
                        <label for="code">{{__('discounts.code')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l8">
                    <div class="input-field">
                        <input type="text" class="input" name="short_description" id="short_description"
                               value="{{old('short_description', (isset($discountCode) ? $discountCode->short_description: ''))}}">
                        <label for="short_description">{{__('discounts.shortDescript')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l8">
                    <div class="input-field select-container">
                        @php( $assignedCategories = isset($discountCode) ? $discountCode->categories->pluck('id')->toArray() : [] )
                        <label for="categories">Categor&iacute;as</label>
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
                <div class="col s12 m12 l9">
                     <div class="{{$isSaas ? 'input-field text-area' : ''}}">
                        <label class="active" for="terms">{{__('discounts.terms')}}</label>
                        <textarea type="text" id="terms" class="materialize-textarea" name="terms" style="width: 95%;"> {{old('terms',($discountCode->terms ?? ''))}}</textarea>
                    </div>
                </div>
            </div>
            <div class="col s12 m12 l12">
                <div class="switch">
                    <label>
                        {{__('discounts.private')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('is_public','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($discountCode) && $discountCode->isPublic() ? 'checked' : '' !!}
                               @endif
                               name="is_public">
                        <span class="lever"></span>
                        {{__('discounts.public')}}
                    </label>
                </div>
            </div>
            <div class="col s12 m12 l12">
                <div class="switch">
                    <label>
                        {{__('discounts.no_in_home')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('in_home','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($discountCode) && $discountCode->isVisibleInHome() ? 'checked' : '' !!}
                               @endif
                               name="in_home">
                        <span class="lever"></span>
                        {{__('discounts.in_home')}}
                    </label>
                </div>
            </div>

        </div>
        <h5 class="">{{__('discounts.DiscountSettings')}}</h5>
        <div class="col 12 m12 card-panel panelcombos">
            <div class="row">

                <div class="col s12 m12 l6">
                    <label class="header">{{__('discounts.typediscount')}}</label>
                    <div class="switch">
                        <label>
                            {{__('discounts.percent')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('discount_type','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($discountCode) && $discountCode->isPrice() ? 'checked' : '' !!}
                                   @endif
                                   name="discount_type">
                            <span class="lever"></span>
                            {{__('discounts.price')}}
                        </label>
                    </div>
                </div>
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="number" class="input" name="discount_number" id="discount_number"
                               value="{{old('discount_number', (isset($discountCode) ? $discountCode->discount_number : ''))}}"
                               required>
                        <label for="discount_number">{{__('discounts.discountNumber')}}</label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class=" input-field col s12 m12 l6">
                    <label for="discount_from">{{__('discounts.discountFrom')}}</label>
                    <input type="text" id="discount_from" class="calendar-date pck-pink" name="discount_from"
                           value="{{old('discount_from',($discountCode->discount_from ?? ''))}}"/>
                </div>
                <div class=" input-field col s12 m12 l6">
                    <label for="discount_to">{{__('discounts.discountTo')}}</label>
                    <input type="text" id="discount_to" class="calendar-date pck-pink" name="discount_to"
                           value="{{old('discount_to',($discountCode->discount_to ?? ''))}}"/>
                </div>
            </div>
        </div>
        {{--<div class="col 12 m12 card-panel panelcombos">--}}
        {{--<div class="row">--}}
        {{--<h5 class="header">{{__('discounts.validitys')}}</h5>--}}
        {{--<div class="row">--}}
        {{--<div class="col s12 m12 l12">--}}
        {{--<div class="switch">--}}
        {{--<label>--}}
        {{--{{__('discounts.no_is_valid_for_all')}}--}}
        {{--<input type="checkbox"--}}
        {{--@if(!!old()) {!! old('is_valid_for_all','')==='on' ? 'checked' : '' !!}--}}
        {{--@else {!! isset($discountCode) && $discountCode->isValidForAll() ? 'checked' : '' !!}--}}
        {{--@endif--}}
        {{--name="is_valid_for_all">--}}
        {{--<span class="lever"></span>--}}
        {{--{{__('discounts.is_valid_for_all')}}--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col s12 m12 l12">--}}
        {{--<div class="switch">--}}
        {{--<label>--}}
        {{--{{__('discounts.no_is_valid_for_combos')}}--}}
        {{--<input type="checkbox"--}}
        {{--@if(!!old()) {!! old('is_valid_for_combos','')==='on' ? 'checked' : '' !!}--}}
        {{--@else {!! isset($discountCode) && $discountCode->isValidForCombos() ? 'checked' : '' !!}--}}
        {{--@endif--}}
        {{--name="is_valid_for_combos">--}}
        {{--<span class="lever"></span>--}}
        {{--{{__('discounts.is_valid_for_combos')}}--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col s12 m12 l12">--}}
        {{--<div class="switch">--}}
        {{--<label>--}}
        {{--{{__('discounts.no_is_valid_for_memberships')}}--}}
        {{--<input type="checkbox"--}}
        {{--@if(!!old()) {!! old('is_valid_for_memberships','')==='on' ? 'checked' : '' !!}--}}
        {{--@else {!! isset($discountCode) && $discountCode->isValidForMemberships() ? 'checked' : '' !!}--}}
        {{--@endif--}}
        {{--name="is_valid_for_memberships">--}}
        {{--<span class="lever"></span>--}}
        {{--{{__('discounts.is_valid_for_memberships')}}--}}
        {{--</label>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}

        <h5 class="">{{__('discounts.DiscountUse')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                @if(isset($discountCode))
                    <div class="col s12">
                        <div class="input-field discount-used">
                            <input type="text" disabled id="total_used"
                                   value="{{$discountCode->purchaseDiscountCodesApplied()->count()}}">
                            <label for="total_used">{{__('discounts.totalUsed')}}</label>
                        </div>
                    </div>
                @endif
                <div class="input-field col s12 m12 l6">
                    <input type="number" class="input" name="total_uses" id="total_uses"
                           value="{{old('total_uses', (isset($discountCode)? $discountCode->total_uses: ''))}}">
                    <label for="total_uses">{{__('discounts.totalUse')}}</label>
                </div>

                <div class="input-field col s12 m12 l6">
                    <input type="number" class="input" name="users_uses" id="users_uses"
                           value="{{old('users_uses', (isset($discountCode)? $discountCode->users_uses: '1'))}}">
                    <label for="users_uses">{{__('discounts.UserUse')}}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m12 l6">
                    <input type="number" class="input" name="purchases_min" id="purchases_min"
                           value="{{old('purchases_min', (isset($discountCode)? $discountCode->purchases_min: ''))}}">
                    <label for="purchases_min">{{__('discounts.purchases_min')}}</label>
                </div>
                <div class="input-field col s12 m12 l6">
                    <input type="number" class="input" name="purchases_max" id="purchases_max"
                           value="{{old('purchases_max', (isset($discountCode)? $discountCode->purchases_max: ''))}}">
                    <label for="purchases_max">{{__('discounts.purchases_max')}}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m4">
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="file-field input-field">
                    <div class="uploadPhoto">
                        <img src="{{$discountCode->pic??''}}" width="180px" alt=""
                             class="responsive-img uploadPhoto--image"/> <br>
                        <h5 class="header"><i
                                class="material-icons small">add_a_photo</i> {{__('discounts.photo')}}</h5>
                        <input type='file' class="uploadPhoto--input" name="pic"/>
                    </div>
                </div>
            </div>

            <div class="col s12 m12 l12">
                <div class="switch">
                    <label>
                        {{__('discounts.inactive')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($discountCode) && $discountCode->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('discounts.active')}}
                    </label>
                </div>
            </div>
            @if(isset($discountCode) && !$discountCode->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('discounts.ActiveWarning')}}</p>
                </div>
            @endif
        </div>
    </div>
    <div class="col s12 m4">
        @if(isset($discountCode))
            <div class="col s12 m12 l7 input-field">
                <a class="{{$isSaas ? 'BuqSaas-e-button is-sidebarTool' : 'waves-effect waves-light btn btnguardar'}}"
                   href="#applicable_credits"
                   style="margin: 0;">{{__('marketing.ApplicableCredits')}}</a>
            </div>
        @endif

        <div class="col s12 m12 l7 edit-buttons input-field">
            <button type="submit" class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
                @if($isSaas)
                    <div>
                        <i class="fal fa-save"></i>
                        <span>{{__('staff.Save')}}</span>
                    </div>
                @else
                    <i class="material-icons right small">save</i>
                    {{__('staff.Save')}}
                @endif

            </button>
        </div>
        <div class="row">
            @if (isset($discountCode))
                <div class="col s12 m12 l7 edit-buttons">
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::DISCOUNT_DELETE,$brand))
                        <a class="{{$isSaas ? 'BuqSaas-e-button is-delete' : 'waves-effect waves-light btn btnguardar'}}" href="#eliminar_code" style="{{$isSaas ? '' : 'background-color: grey'}}">
                           @if($isSaas)
                              <i class="far fa-times"></i>
                              <span>{{__('discounts.Delete')}}</span>
                           @else
                              <i class="material-icons right small">clear</i>
                              {{__('discounts.Delete')}}
                           @endif
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</form>



@if (isset($discountCode))
    <div id="eliminar_code" class="modal modal - fixed - footer modaldelete model--border-radius" data-method="get"
         data-href="{{route('admin.company.brand.discount-code.delete', ['company'=>$company, 'brand' => $brand,'discountCode' => $discountCode])}}">
        <div class="modal-content"></div>
    </div>

    <div id="applicable_credits"
         class="User--assignmentRoles modal modal-fixed-footer modal-discount-code-credits model--border-radius"
         data-method="get" data-href="{{route('admin.company.brand.discount-code.credits',[
                    'company'=>$company,
                    'brand'=>$brand,
                    'discountCode'=>$discountCode
                    ])}}">
        <div class="modal-content">@cargando</div>
        <div class="modal-footer">
            <a type="submit" id="credits_form_button"
               class="modal-action modal-close waves-effect waves-green btn">{{__('administrators.Save')}}</a>
        </div>
    </div>
@endif
