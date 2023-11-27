<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($importUser))
        <input type="hidden" name="id" value="{{$importUser->id}}">
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
                               value="{{old('logo_link', (isset($importUser) ? $importUser->logo_link: ''))}}"
                               required>
                        <label for="logo_link">{{__('mails.logoLink')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="content[title]" id="title" required
                               value="{{old('content.title', (isset($importUser) ? $importUser->content['title'] ?? '' : ''))}}">
                        <label for="title">{{__('mails.title')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="content[subtitle]" id="subtitle"
                               value="{{old('content.subtitle', (isset($importUser) ? $importUser->content['subtitle'] ?? '' : ''))}}">
                        <label for="subtitle">{{__('mails.subtitle')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l10">
                    <div class="input-field">
                        <label class="active" for="content">{{__('mails.content')}}</label>
                        <textarea class="materialize-textarea" name="content[content]"
                                  id="content">
                            {{old('content.content', (isset($importUser) ? $importUser->content['content'] ?? '' : ''))}}
                        </textarea>
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
                        <img src="{{$importUser->logo??''}}" width="180px" alt=""
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
                        <img src="{{$importUser->background_img??''}}" width="180px" alt=""
                             class="responsive-img uploadPhoto--image"/> <br>
                        <h5 class="header"><i
                                class="material-icons small">add_a_photo</i> {{__('mails.background_img')}}
                        </h5>
                        <input type='file' class="uploadPhoto--input" name="background_img"/>
                    </div>
                </div>
            </div>
            <div class="row">
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
        </div>
    </div>
</form>

