<h5 class="header card-title">{{__('users.Title')}}</h5>
<form class="row" method="post" action="{{$urlForm}}" autocomplete="off" enctype="multipart/form-data">

    @include('admin.common.alertas')
    <input type="hidden" name="users_id" value="{{$user_id}}">
    <input hidden name="companies_id" value="{{$company->id}}">
    @if(isset($profileToEdit))
        <input type="hidden" name="id" value="{{$profileToEdit->id}}">
    @endif
    {{csrf_field()}}

    <div class="col s12 m8">
        @if(isset($profileToEdit))
            <h5 class="">{{__('users.MemberSince')}}
                : {{date_format($profileToEdit->created_at, 'd/m/Y')}}</h5>
        @endif
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="input-field col s12 m12 l3">
                    <input type="text" id="first_name" class="input" name="first_name"
                           value="{{old('first_name',($profileToEdit->first_name ?? ''))}}"
                           required>
                    <label for="first_name">{{__('users.Name')}}</label>
                </div>

                <div class="input-field col s12 m12 l3">
                    <input type="text" id="lastname" class="input" name="last_name"
                           value="{{old('last_name',($profileToEdit->last_name ?? ''))}}" required>
                    <label for="lastname">{{__('users.Lastname')}}</label>
                </div>

                <div class=" input-field col s12 m12 l4">
                    <label for="birthday">{{__('users.Birthday')}}</label>
                    <input type="text" id="birthday" class="calendar-date pck-pink" name="birth_date"
                           value="{{old('birth_date',($profileToEdit->birth_date ?? ''))}}"/>
                    <input hidden id="prueba_date" value="{{old('birth_date',($profileToEdit->birth_date ?? ''))}}">
                </div>

                <div class="col s12 m12 l6">
                    <div class="input-field  ">
                        <input type="password" id="pass" class="input"
                               {{isset($profileToEdit) ? '' : 'required'}} name="password">
                        <label for="pass">{{__('users.Password')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field ">
                        <input type="password" id="repass" class="input"
                               {{isset($profileToEdit) ? '' : 'required'}} name="password_confirmation">
                        <label for="repass">{{__('users.ConfirmPassword')}} </label>
                    </div>
                </div>

                <div class="col s6">
                    <div class="col s5 m4">
                        <p>
                            <input type="radio" id="w" name="gender" class="with-gap"
                                   value="female" {{isset($profileToEdit)&&$profileToEdit->gender==='female' ? 'checked="checked"':''}}>
                            <label for="w">{{__('users.W')}}</label>
                        </p>
                    </div>
                    <div class="col s5 m4">
                        <p>
                            <input type="radio" id="m" name="gender" class="with-gap"
                                   value="male" {{isset($profileToEdit)&&$profileToEdit->gender==='male' ? 'checked="checked"':''}}>
                            <label for="m">{{__('users.M')}}</label>
                        </p>
                    </div>
                </div>
                <br><br>
            </div>

        </div>

        <h5 class="">{{__('users.Address')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l3">
                    <div class="input-field ">
                        <input type="text" id="street" class="input" name="address"
                               value="{{old('address',($profileToEdit->address ?? ''))}}">
                        <label for="street">  {{__('users.Street')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l3">
                    <div class="input-field ">
                        <input type="text" id="innumber" class="input" name="internal_number"
                               value="{{old('internal_number',($profileToEdit->internal_number ?? ''))}}">
                        <label for="innumber">{{__('users.Number')}} {{__('users.Interior')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l4">
                    <div class="input-field ">
                        <input type="text" id="number" class="input" name="external_number"
                               value="{{old('external_number',($profileToEdit->external_number ?? ''))}}">
                        <label for="number">{{__('users.Number')}} {{__('users.Exterior')}}</label>
                    </div>
                </div>

                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="text" id="sub" class="input" name="municipality"
                               value="{{old('municipality',($profileToEdit->municipality ?? ''))}}">
                        <label for="sub">{{__('users.Suburb')}}</label>
                    </div>
                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field ">
                        <input type="text" id="post" class="input" name="postal_code"
                               value="{{old('postal_code',($profileToEdit->postal_code ?? ''))}}">
                        <label for="post">{{__('users.Postcode')}}</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="places-selectors"></div>
            </div>
        </div>

        <h5 class="">{{__('users.Contact')}}</h5>
        <div class="col s12 m12 card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="email" id="email" class="input" name="email" required
                               value="{{old('email',($profileToEdit->email ?? ''))}}">
                        <label for="email">{{__('users.Email')}}</label>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field  ">
                        <input type="text" id="phone" class="input " name="phone"
                               value="{{old('phone',($profileToEdit->phone ?? ''))}}">
                        <label for="phone">{{__('users.Phone')}} </label>
                    </div>

                </div>
                <div class="col s12 m12 l4">
                    <div class="input-field ">
                        <input type="text" id="mob" class="input " name="cel_phone"
                               value="{{old('cel_phone',($profileToEdit->cel_phone ?? ''))}}">
                        <label for="mob">{{__('users.Mobile')}}</label>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div class="col s12 m4">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="file-field input-field">
                        <div class="">
                            <div class="uploadPhoto">
                                <img src="{{$profileToEdit->picture ?? ''}}" width="180px" alt=""
                                     class="responsive-img uploadPhoto--image"/> <br>
                                <h5 class="header"><i
                                        class="material-icons small">add_a_photo</i> {{__('users.ProfilePicture')}}
                                </h5>
                                <input type='file' class="uploadPhoto--input" name="picture"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field">
                @php( $assignedCategories = isset($profileToEdit) ? $profileToEdit->categories->pluck('id')->toArray() : [] )
                <label for="categories">Categorías</label>
                <select name="categories[]" multiple="multiple" id="categories" style="width: 100%;" class="select2-not">
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
        <div class="row">
            <div class="">
                <div class="switch">
                    <label>
                        {{__('users.Inactive')}}
                        <input type="checkbox"
                               @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                               @else {!! isset($profileToEdit) && $profileToEdit->isActive() ? 'checked' : '' !!}
                               @endif
                               name="status">
                        <span class="lever"></span>
                        {{__('users.Active')}}
                    </label>
                </div>
            </div>
            @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_VERIFY,$company))
                <div class="">
                    <div class="switch">
                        <label>
                            {{__('users.NotVerified')}}
                            <input type="checkbox"
                                   @if(!!old()) {!! old('verified','')==='on' ? 'checked' : '' !!}
                                   @else {!! isset($profileToEdit) && $profileToEdit->isVerified() ? 'checked' : '' !!}
                                   @endif
                                   name="verified">
                            <span class="lever"></span>
                            {{__('users.Verified')}}
                        </label>
                    </div>
                </div>
            @endif
            @if(isset($profileToEdit) && (!$profileToEdit->isActive()||!$profileToEdit->isVerified()))
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('users.ActiveWarning')}}</p>
                </div>
            @endif
            <div class="col s12 m12 l12 input-field">
                <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                        class="material-icons right small">save</i>{{__('users.Save')}}</button>
            </div>
        </div>
    </div>


    {{--Aqui los botones para la opcion de editar usuario solo si se tiene la variable profileToEdit --}}
    {{--@if( isset($profileToEdit))--}}
    {{--<div class="col s4">--}}
    {{--<div class="row">--}}
    {{--<div class="col s4 m12">--}}
    {{--<!-- Modal Trigger -->--}}
    {{--<a class=" waves-effect waves-light btn deep-purple lighten-3 modalGafaFit"--}}
    {{-- devuelve vista parcial de asignacion de roles para editar--}}
    {{--style="top: 26px; float: right;"--}}
    {{--href="#User--roles"--}}
    {{--style="margin: 0;">{{__('users.Assignment')}} {{__('users.Roles')}}</a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--@endif--}}



    {{--<div class="row">--}}
    {{--@if (isset($profileToEdit))--}}
    {{--<div class="col s12 m12 l7 edit-buttons">--}}
    {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_DELETE,$company))--}}
    {{--<a class="waves-effect waves-light btn btnguardar" href="#eliminar_c"--}}
    {{--style="background-color: grey"><i--}}
    {{--class="material-icons right small">clear</i>{{__('staff.Delete')}}</a>--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--@endif--}}
    {{--</div>--}}


</form>
@if(isset($profileToEdit))
    <!-- Estructura del modal donde llama a la vista parcial -->
    <div id="User--roles" class="modal modal-fixed-footer modalroles" data-href="{{route('admin.company.users.delete',[
                    'company'=>$company,
                    'user'=>$profileToEdit->id,
                    ])}}">
        <div class="modal-content">@cargando</div>
        <div class="modal-footer">
            <a href="#"
               class="modal-action modal-close waves-effect waves-green btn">{{__('users.Save')}}</a>
        </div>
    </div>
@endif

{{--@if (isset($profileToEdit))--}}
{{--<div id="eliminar_c" class="modal modal - fixed - footer modaldelete" data-method="get"--}}
{{--data-href="{{route('admin.company.users.delete',[--}}
{{--'company'=>$company,--}}
{{--'user'=>$profileToEdit->id,--}}
{{--])}}">--}}
{{--<div class="modal-content"></div>--}}
{{--</div>--}}
{{--@endif--}}
