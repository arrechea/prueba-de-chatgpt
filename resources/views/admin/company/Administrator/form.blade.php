<h5 class="header card-title">{{__('administrators.AdminData')}}</h5>
<form method="post" action="{{$urlForm}}" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    <input hidden id="admins_id" name="admins_id" value="{{$adminToEdit->id}}">
    <input hidden id="companies_id" name="companies_id" value="{{$company->id}}">
    @if(isset($adminProfile))
        <input type="hidden" name="id" value="{{$adminProfile->id}}">
    @endif
    {{csrf_field()}}
    <div class="col s12 m8">
        <h5 class="">{{__('company.GeneralData')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="input-field col s12 m12 l3">
                    <input type="text" id="first_name" class="input" name="first_name"
                           value="{{old('first_name',($adminProfile->first_name ?? ''))}}"
                           required>
                    <label for="first_name">{{__('company.Name')}}</label>
                </div>

                <div class="input-field col s12 m12 l3">
                    <input type="text" id="lastname" class="input" name="last_name"
                           value="{{old('last_name',($adminProfile->last_name ?? ''))}}" required>
                    <label for="lastname">{{__('company.Lastname')}}</label>
                </div>

                <div class=" input-field col s12 m12 l4">
                    <label for="birthday">{{__('company.Birthday')}}</label>
                    <input type="text" id="birthday" class="calendar-date pck-pink" name="birth_date"
                           value="{{old('birth_date',($adminProfile->birth_date ?? ''))}}"/>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l2">
                    <p>
                        <input type="radio" id="w" name="gender" class="with-gap"
                               value="female" {{isset($adminProfile)&&$adminProfile->gender==='female' ? 'checked="checked"':''}}>
                        <label for="w">{{__('company.W')}}</label>
                    </p>
                </div>
                <div class="col s12 m12 l2">
                    <p>
                        <input type="radio" id="m" name="gender" class="with-gap"
                               value="male" {{isset($adminProfile)&&$adminProfile->gender==='male' ? 'checked="checked"':''}}>
                        <label for="m">{{__('company.M')}}</label>
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field  ">
                        <input type="password" id="pass" class="input"
                               {{isset($adminProfile) ? '' : 'required'}} name="password">
                        <label for="pass">{{__('company.Password')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field ">
                        <input type="password" id="repass" class="input"
                               {{isset($adminProfile) ? '' : 'required'}} name="password_confirmation">
                        <label for="repass">{{__('company.Confirm')}} {{__('company.Password')}} </label>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="">{{__('company.Address')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="text" id="street" class="input" name="address"
                               value="{{old('address',($adminProfile->address ?? ''))}}">
                        <label for="street">  {{__('company.Street')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field ">
                        <input type="text" id="number" class="input" name="external_number"
                               value="{{old('external_number',($adminProfile->external_number ?? ''))}}">
                        <label for="number">{{__('company.Number')}} {{__('company.Exterior')}}</label>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="text" id="sub" class="input" name="municipality"
                               value="{{old('municipality',($adminProfile->municipality ?? ''))}}">
                        <label for="sub">{{__('company.Suburb')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field ">
                        <input type="text" id="post" class="input" name="postal_code"
                               value="{{old('postal_code',($adminProfile->postal_code ?? ''))}}">
                        <label for="post">{{__('company.Postcode')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="places-selectors"></div>
            </div>
        </div>

        <h5 class="">{{__('company.Contact')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="email" id="email" class="input" name="email"
                               value="{{old('email',($adminProfile->email ?? ''))}}" required>
                        <label for="email">{{__('company.E-mail')}}</label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field  ">
                        <input type="text" id="phone" class="input " name="phone"
                               value="{{old('phone',($adminProfile->phone ?? ''))}}">
                        <label for="phone">{{__('company.Phone')}} </label>
                    </div>
                </div>


                <div class="col s12 m12 l4">
                    <div class="input-field ">
                        <input type="text" id="mob" class="input " name="cel_phone"
                               value="{{old('cel_phone',($adminProfile->cel_phone ?? ''))}}">
                        <label for="mob">{{__('company.Mobile')}}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <div class="row">
            <div class="card-panel panelcombos">
                <div class="file-field input-field">
                    <div class="uploadPhoto">
                        <img src="{{$adminProfile->pic??''}}" width="180px" alt=""
                             class="responsive-img uploadPhoto--image"/> <br>
                        <h5 class="header"><i
                                class="material-icons small">add_a_photo</i> {{__('company.ProfilePicture')}}
                        </h5>
                        <input type='file' class="uploadPhoto--input" name="pic"/>
                    </div>
                </div>
            </div>
            <div class="switch">
                <label>
                    {{__('company.Inactive')}}
                    <input type="checkbox"
                           @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                           @else {!! isset($adminProfile) && $adminProfile->isActive() ? 'checked' : '' !!}
                           @endif
                           name="status">
                    <span class="lever"></span>
                    {{__('company.Active')}}
                </label>
            </div>
            @if(isset($adminProfile) && !$adminProfile->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('administrators.ActiveWarning')}}</p>
                </div>
            @endif

            {{--Aqui los botones para la opcion de editar usuario solo si se tiene la variable adminToEdit --}}


            @if( isset($adminToEdit) && \App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_ASIGN_ROLES,$company))
                <div class="col s12 m12 l7 input-field">
                    <!-- Modal Trigger -->
                    <a class="waves-effect waves-light btn deep-purple lighten-3 modalGafaFit btnasignacionr"
                       {{--devuelve vista parcial de asignacion de roles para editar--}}
                       href="#User--roles"
                       style="margin: 0;white-space: normal !important;">{{__('company.AssignmentRoles')}}</a>
                </div>
            @endif
            <div class="col s12 m12 l7 edit-buttons">
                <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                        class="material-icons right small">save</i>{{__('gafacompany.Save')}}</button>
            </div>
        </div>
    </div>
</form>
@if(isset($adminToEdit) && \App\Librerias\Permissions\LibPermissions::userCan(auth()->user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_ASIGN_ROLES,$company))
    <div id="User--roles" class="User--assignmentRoles modal modal-fixed-footer modalroles" data-method="get"
         data-href="{{route('admin.company.administrator.assignmentRoles',[
                    'company'=>$company,
                    'administrator'=>$adminToEdit,
                    'profile'=>$adminProfile,
                    ])}}">
        <div class="modal-content">@cargando</div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
               href="#"> <i class="material-icons small">clear</i>
                {{__('brand.Cancel')}}</a>
            <a type="submit"
                class="modal-action modal-close waves-effect waves-green btn edit-button"><i
                    class="material-icons small">done</i>{{__('company.Save')}}</a>
        </div>
    </div>
@endif


@section('jsPostApp')
    @parent

    {{--<script src="{{asset('js/common.js')}}"></script>--}}

@endsection
