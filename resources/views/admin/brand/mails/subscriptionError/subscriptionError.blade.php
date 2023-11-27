<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($subscriptionError))
        <input type="hidden" name="id" value="{{$subscriptionError->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m8">
        <h5 class="">{{__('mails.Data')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="title" id="title"
                               value="{{old('title', (isset($subscriptionError) ? $subscriptionError->title: ''))}}">
                        <label for="title">{{__('mails.tittle')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="description" id="description"
                               value="{{old('description', (isset($subscriptionError) ? $subscriptionError->description: ''))}}">
                        <label for="description">{{__('mails.description')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="logo_link" id="logo_link"
                               value="{{old('logo_link', (isset($subscriptionError) ? $subscriptionError->logo_link: ''))}}">
                        <label for="logo_link">{{__('mails.logoLink')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <h5 class="">{{__('mails.images')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="{{$subscriptionError->logo??''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('mails.logo')}}
                            </h5>
                            <input type='file' class="uploadPhoto--input" name="logo"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="{{$subscriptionError->background_img??''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('mails.background_img')}}
                            </h5>
                            <input type='file' class="uploadPhoto--input" name="background_img"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l7 edit-buttons">
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
    </div>
</form>

