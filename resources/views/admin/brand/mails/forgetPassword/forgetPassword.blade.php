<form action="{{$urlForm}}" class="row" autocomplete="off" method="post" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($password))
        <input type="hidden" name="id" value="{{$password->id}}">
    @endif
    {{csrf_field()}}
    <input type="hidden" name="brands_id" value="{{$brand->id}}">
    <div class="col s12 m10">
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <h5 class="header">{{__('mails.Data')}}</h5>
                <div class="col s12 m12 l6">
                    <div class="input-field">
                        <input type="text" class="input" name="tittle" id="tittle"
                               value="{{old('tittle', (isset($password) ? $password->tittle: ''))}}">
                        <label for="tittle">{{__('mails.tittle')}}</label>
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
                            <img src="{{$password->logo??''}}" width="180px" alt=""
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
                            <img src="{{$password->background_img??''}}" width="180px" alt=""
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
