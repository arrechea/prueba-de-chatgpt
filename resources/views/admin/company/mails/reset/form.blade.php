<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($forgotPassword))
        <input type="hidden" name="id" value="{{$forgotPassword->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="companies_id" value="{{$company->id}}">
    <div class="col s12 m8">
        <h5 class="">{{__('brand.Data')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="logo_link" id="logo_link"
                               value="{{old('logo_link', (isset($forgotPassword) ? $forgotPassword->logo_link: ''))}}"
                               required>
                        <label for="logo_link">{{__('mails.logoLink')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="text_1" id="text_1"
                               value="{{old('text_1', (isset($forgotPassword) ? $forgotPassword->text_1: ''))}}">
                        <label for="text_1">{{__('mails.text-1')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l10">
                    <div class="input-field">
                        <label class="active" for="text_2">{{__('mails.text-2')}}</label>
                        <textarea class="materialize-textarea" name="text_2"
                                  id="text_2">{{old('text_2', (isset($forgotPassword) ? $forgotPassword->text_2: ''))}}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <h5 class="">{{__('mails.images')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="file-field input-field">
                    <div class="uploadPhoto">
                        <img src="{{$forgotPassword->logo??''}}" width="180px" alt=""
                             class="responsive-img uploadPhoto--image"/> <br>
                        <h5 class="header"><i
                                class="material-icons small">add_a_photo</i> {{__('mails.logo')}}
                        </h5>
                        <input type='file' class="uploadPhoto--input" name="logo"/>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="file-field input-field">
                    <div class="uploadPhoto">
                        <img src="{{$forgotPassword->background_img??''}}" width="180px" alt=""
                             class="responsive-img uploadPhoto--image"/> <br>
                        <h5 class="header"><i
                                class="material-icons small">add_a_photo</i> {{__('mails.background_img')}}
                        </h5>
                        <input type='file' class="uploadPhoto--input" name="background_img"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <button type="submit" class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn btnguardar'}}">
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
        </div>
    </div>
</form>

