<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($invitationConfirm))
        <input type="hidden" name="id" value="{{$invitationConfirm->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m10">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="header">{{__('mails.Data')}}</h5>
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="text" id="text"
                               value="{{old('text', (isset($invitationConfirm) ? $invitationConfirm->text: ''))}}">
                        <label for="text">{{__('mails.greetingText')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="logo_link" id="logo_link"
                               value="{{old('logo_link', (isset($invitationConfirm) ? $invitationConfirm->logo_link: ''))}}">
                        <label for="logo_link">{{__('mails.logoLink')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l7">
                    <div class="input-field">
                        <input type="text" class="input" name="reservation_link" id="reservation_link"
                               value="{{old('reservation_link', (isset($invitationConfirm) ? $invitationConfirm->reservation_link: ''))}}">
                        <label for="reservation_link">{{__('mails.reservationLink')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col s12 m10">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="header">{{__('mails.images')}}</h5>
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="uploadPhoto">
                            <img src="{{$invitationConfirm->logo??''}}" width="180px" alt=""
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
                            <img src="{{$invitationConfirm->background_img??''}}" width="180px" alt=""
                                 class="responsive-img uploadPhoto--image"/> <br>
                            <h5 class="header"><i
                                    class="material-icons small">add_a_photo</i> {{__('mails.background_img')}}
                            </h5>
                            <input type='file' class="uploadPhoto--input" name="background_img"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m12 l7 edit-buttons">
            <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                    class="material-icons right small">save</i>{{__('brand.Save')}}</button>
        </div>
    </div>
</form>
