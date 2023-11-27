<?php
$type = old('type', $offer->type ?? null)
?>
<form method="post" action="{{$urlForm}}" class="row" autocomplete="off" enctype="multipart/form-data">
    <h5 class="col s12 header">{{__('marketing.OfferTitle')}}</h5>
    @include('admin.common.alertas')
    @if(isset($offer))
        <input type="hidden" name="id" value="{{$offer->id}}">
    @endif
    <input hidden id="prev_buy_get_get" value="{{old('buy_get_get',$offer->buy_get_get ?? '')}}">
    <input hidden id="prev_buy_get_buy" value="{{old('buy_get_buy',$offer->buy_get_buy ?? '')}}">
    <input hidden id="prev_discount_number" value="{{old('discount_number',$offer->discount_number ?? '')}}">
    <input hidden id="prev_discount_type"
           value="@if(old('discount_type_check')) {!! 'percent' !!} @elseif(isset($offer)) {!! $offer->discount_type ?? '' !!} @else {!! 'price' !!} @endif">
    <input hidden id="prev_credits" value="{{old('credits',$offer->credits ?? '')}}">
    <label class="col s8">{{__('marketing.OfferData')}}</label>
    <input hidden name="companies_id" value="{{$company->id}}">
    <input hidden name="brands_id" value="{{$brand->id}}">
    {{csrf_field()}}
    <div class="col s12 m8">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="input-field">
                    <input type="text" id="name" class="input" name="name"
                           value="{{old('name',(isset($offer) ? $offer->name : ''))}}" required>
                    <label for="name">{{__('marketing.Title')}}</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class=" input-field col s12 m12 l3">
                <label for="from">{{__('marketing.From')}}</label>
                <input type="text" id="from" class="calendar-date pck-pink" name="from"
                       value="{{old('from',($offer->from ?? ''))}}" required>
            </div>
            <div class=" input-field col s12 m12 l3">
                <label for="to">{{__('marketing.To')}}</label>
                <input type="text" id="to" class="calendar-date pck-pink" name="to"
                       value="{{old('to',($offer->to ?? ''))}}" required>
            </div>
        </div>
        <div class="row">
            <div class=" input-field col s12 m12 l3">
                <label for="from">{{__('marketing.Code')}}</label>
                <input type="text" id="code" class="input" name="code"
                       value="{{old('code',($offer->code ?? ''))}}"/>
            </div>
            <div class=" input-field col s12 m12 l3">
                <label for="user_limit">{{__('marketing.UserLimit')}}</label>
                <input type="number" id="user_limit" class="input" name="user_limit"
                       value="{{old('user_limit',($offer->user_limit ?? ''))}}"/>
            </div>
        </div>

        <label class="col s12 m12 l12">{{__('marketing.OfferType')}}</label>
        <div class="row">
            <div class="col s12 m12 l12" id="marketing-type">
                <input type="radio" id="buy_get" name="type" class="with-gap"
                       value="buy_get"
                       @if(!$type)
                       checked="checked"
                       @else
                       {!! $type==='buy_get' ? 'checked="checked"' : '' !!}
                       @endif
                       required>
                <label for="buy_get">{{__('marketing.TwoForOne')}}</label>
                <input type="radio" id="discount" name="type" class="with-gap"
                       value="discount" {!! $type==='discount' ? "checked='checked'" : '' !!}>
                <label for="discount">{{__('marketing.Discount')}}</label>
                <input type="radio" id="credits" name="type" class="with-gap"
                       value="credits" {!! $type==='credits' ? "checked='checked'" : '' !!}>
                <label for="credits">{{__('marketing.Credits')}}</label>
            </div>
        </div>
        <br>
        <div class="row" id="marketing_content_type">

        </div>
        <label class="col s12 m12 l12">{{__('marketing.AppliedTo')}}</label>
        <div class="row">
            <div class="col s12 m12 l12">
                <input type="radio" id="general_classes" name="applied_to" class="with-gap"
                       value="general_classes" checked="checked">
                <label for="general_classes">{{__('marketing.GeneralClasses')}}</label>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <div class="row">
            <div class="col s12 m12 l12">
                <div class="file-field input-field">
                    <div class="">
                        <div class="uploadPhoto">
                            <img src="{{$offer->image ?? ''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('marketing.OfferPhoto')}}</h5>
                            <input type='file' class="uploadPhoto--input" name="image"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="switch">
                        <label>
                            {{__('gafacompany.Inactive')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($offer) && $offer->isActive() ? 'checked' : '' !!}
                                   @endif
                                   name="active">
                            <span class="lever"></span>
                            {{__('gafacompany.Active')}}
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l7 edit-buttons">
                    <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                            class="material-icons right small">save</i>{{__('gafacompany.Save')}}</button>
                </div>
            </div>

            <div class="row">
                @if (isset($offer))
                    <div class="col s12 m12 l7 edit-buttons">
                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::COMPANY_DELETE))
                            <a class="waves-effect waves-light btn btnguardar" href="#eliminar_c"
                               style="background-color: grey"><i
                                    class="material-icons right small">clear</i>{{__('gafacompany.Delete')}}</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>
@if (isset($offer))
    <div id="eliminar_c" class="modal modal - fixed - footer modaldelete" data-method="get"
         data-href="{{route('admin.company.brand.marketing.offers.delete', ['company'=>$company,'brand'=>$brand,'offer'=>$offer])}}">
        <div class="modal-content"></div>
    </div>
@endif
