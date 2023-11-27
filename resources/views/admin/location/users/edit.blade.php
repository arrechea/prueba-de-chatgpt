<?php $isSaas = Auth::user()->isA('gafa-saas') ?>
<div class="model--border-radius">
    @if($isSaas)
        <div class="BuqSaas-l-form">
            <div class="BuqSaas-l-form__header">
                <div class="BuqSaas-c-sectionTitle">
                    <h2>{{__('maps.Create-maps')}}</strong></h2>
                </div>
            </div>

            <div class="BuqSaas-l-form__body">
                @else
                    <h5 class="">{{__('users.Title')}}</h5>
                @endif


                <form id="location-edit-user-form" action="{{$urlForm}}" method="post" type="multipart/form-data">
                    {{csrf_field()}}
                    @include('admin.common.alertas')
                    {{--    <input type="hidden" name="users_id" value="{{$user->users_id}}">--}}
                    <input hidden name="companies_id" value="{{$company->id}}">
                    <input type="hidden" name="id" value="{{$user->id}}">
                    <div class="panelcombos col panelcombos_full">
                        @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::GYMPASS_USER_VIEW,$location))
                            @if(isset($user) && $company->isGympassActive() && $user->getDotValue('extra_fields.gympass.gympass_id'))
                                <div class="row">
                                    <div class="input-field col s12 m6 l3">
                                        <input type="number" id="gympass_gym_id" class="input" name="gympass_gym_id"
                                               readonly
                                               value="{{old('gympass_gym_id',($user->getDotValue('extra_fields.gympass.gympass_id') ?? ''))}}"
                                        >
                                        <label
                                            for="gympass_gym_id" class="active">{{__('gympass.userGympassId')}}</label>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if(isset($user))
                            <h5 class="memberSince">{{__('users.MemberSince')}}
                                : {{date_format($user->created_at, 'd/m/Y')}}</h5>
                        @endif
                        <div class="row">
                            <div class="input-field col s12 m12 l3">
                                <input type="text" id="first_name" class="input" name="first_name"
                                       value="{{old('first_name',($user->first_name ?? ''))}}"
                                       required>
                                <label
                                    for="first_name" {!! $user->first_name ? 'class="active"' : ''!!}>{{__('users.Name')}}</label>
                            </div>

                            <div class="input-field col s12 m12 l3">
                                <input type="text" id="lastname" class="input" name="last_name"
                                       value="{{old('last_name',($user->last_name ?? ''))}}" required>
                                <label
                                    for="lastname" {!! $user->last_name ? 'class="active"' : ''!!}>{{__('users.Lastname')}}</label>
                            </div>

                            <div class=" input-field col s12 m12 l6">
                                <label
                                    for="birth_date" {!! $user->birth_date ? 'class="active"' : ''!!}>{{__('users.Birthday')}}</label>
                                <input type="text" id="birth_date" class="calendar-date pck-pink" name="birth_date"
                                       value="{{old('birth_date',($user->birth_date ?? ''))}}"/>
                                <input hidden id="prueba_date" value="{{old('birth_date',($user->birth_date ?? ''))}}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 m12 l6">
                                <div class="input-field  ">
                                    <input type="password" id="pass" class="input"
                                           {{isset($user) ? '' : 'required'}} name="password">
                                    <label for="pass">{{__('users.Password')}}</label>
                                </div>
                            </div>
                            <div class="col s12 m12 l6">
                                <div class="input-field ">
                                    <input type="password" id="repass" class="input"
                                           {{isset($user) ? '' : 'required'}} name="password_confirmation">
                                    <label for="repass">{{__('users.ConfirmPassword')}} </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s6">
                                <div class="col s5 m4">
                                    <p>
                                        <input type="radio" id="w" name="gender" class="with-gap"
                                               value="female" {{isset($user)&&$user->gender==='female' ? 'checked="checked"':''}}>
                                        <label for="w">{{__('users.W')}}</label>
                                    </p>
                                </div>
                                <div class="col s5 m4">
                                    <p>
                                        <input type="radio" id="m" name="gender" class="with-gap"
                                               value="male" {{isset($user)&&$user->gender==='male' ? 'checked="checked"':''}}>
                                        <label for="m">{{__('users.M')}}</label>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <br><br>


                    </div>
                    <div class="panelcombos col panelcombos_full">
                        <h5 class="">{{__('users.Address')}}</h5>
                        <div class="row">
                            <div class="col s12 m12 l3">
                                <div class="input-field ">
                                    <input type="text" id="street" class="input" name="address"
                                           value="{{old('address',($user->address ?? ''))}}">
                                    <label
                                        for="street" {!! $user->address ? 'class="active"' : ''!!}>  {{__('users.Street')}}</label>
                                </div>
                            </div>

                            <div class="col s12 m12 l3">
                                <div class="input-field ">
                                    <input type="text" id="innumber" class="input" name="internal_number"
                                           value="{{old('internal_number',($user->internal_number ?? ''))}}">
                                    <label
                                        for="innumber" {!! $user->internal_number ? 'class="active"' : ''!!}>{{__('users.Number')}} {{__('users.Interior')}}</label>
                                </div>
                            </div>

                            <div class="col s12 m12 l6">
                                <div class="input-field ">
                                    <input type="text" id="number" class="input" name="external_number"
                                           value="{{old('external_number',($user->external_number ?? ''))}}">
                                    <label
                                        for="number" {!! $user->external_number ? 'class="active"' : ''!!}>{{__('users.Number')}} {{__('users.Exterior')}}</label>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col s12 m12 l6">
                                <div class="input-field ">
                                    <input type="text" id="sub" class="input" name="municipality"
                                           value="{{old('municipality',($user->municipality ?? ''))}}">
                                    <label
                                        for="sub" {!! $user->municipality ? 'class="active"' : ''!!}>{{__('users.Suburb')}}</label>
                                </div>
                            </div>
                            <div class="col s12 m12 l6">
                                <div class="input-field ">
                                    <input type="text" id="post" class="input" name="postal_code"
                                           value="{{old('postal_code',($user->postal_code ?? ''))}}">
                                    <label
                                        for="post" {!! $user->postal_code ? 'class="active"' : ''!!}>{{__('users.Postcode')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div id="places-selectors"></div>
                        </div>
                    </div>

                    <div class="panelcombos col panelcombos_full">
                        <h5 class="">{{__('users.Contact')}}</h5>
                        <div class="row">
                            <div class="col s12 m12 l6">
                                <div class="input-field ">
                                    <input type="email" id="email" class="input" name="email" required
                                           value="{{old('email',($user->email ?? ''))}}">
                                    <label
                                        for="email" {!! $user->email ? 'class="active"' : ''!!}>{{__('users.Email')}}</label>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col s12 m12 l6">
                                <div class="input-field  ">
                                    <input type="text" id="phone" class="input " name="phone"
                                           value="{{old('phone',($user->phone ?? ''))}}">
                                    <label
                                        for="phone" {!! $user->phone ? 'class="active"' : ''!!}>{{__('users.Phone')}} </label>
                                </div>

                            </div>
                            <div class="col s12 m12 l6">
                                <div class="input-field ">
                                    <input type="text" id="mob" class="input " name="cel_phone"
                                           value="{{old('cel_phone',($user->cel_phone ?? ''))}}">
                                    <label
                                        for="mob" {!! $user->cel_phone ? 'class="active"' : ''!!}>{{__('users.Mobile')}}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="special-text-form"></div>

                    <div class="panelcombos col panelcombos_full">
                        <div class="row">
                            <div class="file-field input-field">
                                <div class="uploadPhoto">
                                    <img src="{{$user->picture ?? ''}}" width="180px" alt=""
                                         class="responsive-img uploadPhoto--image"/> <br>
                                    <h5 class="header"><i
                                            class="material-icons small">add_a_photo</i> {{__('users.ProfilePicture')}}
                                    </h5>
                                    <input type='file' class="uploadPhoto--input" name="picture"/>
                                </div>
                            </div>
                        </div>

                        <div class="edit-buttons {{!$isSaas ? 'right' : ' '}}">
                            <button id="save-button" type="submit"
                                    class="{{$isSaas ? 'BuqSaas-e-button is-save' : 'waves-effect waves-light btn'}}">
                                @if($isSaas)
                                    <div>
                                        <i class="fal fa-save"></i>
                                        <span>{{__('users.Save')}}</span>
                                    </div>
                                @else
                                    <i class="material-icons right small">save</i>
                                    {{__('users.Save')}}
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
                @if($isSaas)
            </div>
        </div>
    @endif

</div>


<?php
$modelToEdit = $user ?? new \App\Models\GafaFitModel();
?>
@include('admin.common.places-script')
@include('admin.common.special-text-form',['model'=>($user ?? new \App\Models\User\UserProfile())])

<script>
    $(document).ready(function () {
        // $(".uploadPhoto--input").on('change', function () {
        //     let input = this;
        //     let padre = $(this).closest('.uploadPhoto');
        //     if (input.files && input.files[0]) {
        //         let reader = new FileReader();
        //
        //         reader.onload = function (e) {
        //             padre.find('.uploadPhoto--image').attr('src', e.target.result);
        //         };
        //
        //         reader.readAsDataURL(input.files[0]);
        //     }
        // });
        initPhotoInputs();

        let date_options = {
            selectYears: 150,
            selectMonths: true,
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
            today: 'Hoy',
            clear: 'Borrar',
            close: 'Cerrar',
            // Setter
            onStart: function () {
                let valor = this.get('value');
                if (valor !== '') {
                    let date = new Date(valor);
                    this.set('select', [date.getFullYear(), date.getMonth(), date.getDate()])
                }
            },
        };

        $('#birth_date').pickadate(date_options);

        $('#location-edit-user-form').submit(function (e) {
            e.preventDefault();
            let data = new FormData(this);
            let modal = $(this).closest('.modal');
            $.ajax({
                url: $(this).attr('action'),
                type: 'post',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (dat) {
                    let return_id = modal.data('return-id-input');
                    let success_function = modal.data('success-function');
                    if (return_id !== null && return_id !== '') {
                        let return_input = $('#' + return_id);
                        return_input.val(dat.id);
                    }
                    if (success_function) {
                        let y = eval(success_function);
                        if (typeof y === 'function') {
                            y(dat);
                        }
                    }

                    Materialize.toast("{{__('users.MessageSaveSuccess')}}", 4000);
                },
                error: function (err) {
                    displayErrorsToast(err, "{{__('users.MessageSaveError')}}");
                }
            });
        });

        inputsAsterisk();
    });


</script>

<style>
    .card-panel {
        background: none;
    }

    #LocationUser--editModal {
        height: 70% !important;
        width: 60% !important;
        min-width: 350px;
    }

    #special-text-form h5 {
        margin-left: 12px;
    }
</style>
