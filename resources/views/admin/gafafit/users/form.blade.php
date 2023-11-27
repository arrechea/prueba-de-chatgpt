<h5 class="header">{{__('users.UserData')}}</h5>
<form method="post" action="{{$urlForm}}" autocomplete="off">
    @include('admin.common.alertas')
    @if(isset($userToEdit))
        <input type="hidden" name="id" value="{{$userToEdit->id}}">
    @endif
    {{csrf_field()}}
    <div class="col s12 m8">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="text" id="name" class="input" name="name"
                               value="{{old('name',(isset($userToEdit) ? $userToEdit->name : ''))}}" required>
                        <label for="name">{{__('users.Name')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="email" id="email" name="email" class="input"
                               value="{{old('email',(isset($userToEdit) ? $userToEdit->email : ''))}}" required>
                        <label for="email">{{__('users.Email')}}</label>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field  ">
                        <input type="password" id="pass" class="input"
                               {{isset($userToEdit) ? '' : 'required'}} name="password"/>
                        <label for="pass">{{__('users.Password')}}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s12 m12 l6">
                    <div class="input-field ">
                        <input type="password" id="repass" class="input"
                               {{isset($userToEdit) ? '' : 'required'}} name="password_confirmation"/>
                        <label for="repass">{{__('users.Confirm')}} {{__('users.Password')}} </label>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col s12 m4">
        <div class="row">
            <div class="switch">
                <label>
                    {{__('users.Inactive')}}
                    <input type="checkbox"
                           @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                           @else {!! isset($userToEdit) && $userToEdit->isActive() ? 'checked' : '' !!}
                           @endif
                           name="status">
                    <span class="lever"></span>
                    {{__('users.Active')}}
                </label>
            </div>
        </div>
        @if(isset($userToEdit) && !$userToEdit->isActive())
            <div class="col s12 m12 l12">
                <p style="color: var(--alert-color)">{{__('users.ActiveWarning')}}</p>
            </div>
        @endif

        @if( isset($userToEdit))
            {{--<div class="col s12 m12 l12 input-field">--}}
            {{--<a type="button" class="waves-effect waves-light btn deep-purple lighten-3"--}}
            {{--href="#consulta"--}}
            {{--style="top: 30px; ">{{__('administrators.ConsultReservations')}}</a>--}}
            {{--</div>--}}
        @endif
        <div class="">
            <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                    class="material-icons right small">save</i>{{__('users.Save')}}</button>
        </div>
    </div>


    {{--@if (isset($userToEdit))--}}
    {{--<div class="row">--}}
    {{--<div class="col s12 m12 l4 edit-buttons">--}}
    {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_DELETE))--}}
    {{--<a class="waves-effect waves-light btn btnguardar" href="#eliminar_usr"--}}
    {{--style="background-color: grey"><i--}}
    {{--class="material-icons right small">clear</i>{{__('gafacompany.Delete')}}</a>--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--@endif--}}
</form>

@if(isset($userToEdit))

    <!-- Modal Structure -->
    <div id="consulta" class="modal modal-fixed-footer modalConsulta model--border-radius">
        <div class="modal-content">

            @include('admin.company.users.edit.reservations')
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn ">Guardar</a>
        </div>
    </div>
@endif

{{--@if (isset($userToEdit))--}}
{{--<div id="eliminar_usr" class="modal modal - fixed - footer modaldelete" data-method="get"--}}
{{--data-href="{{route('admin.users.delete', ['user' => $userToEdit->id,])}}">--}}
{{--<div class="modal-content"></div>--}}
{{--</div>--}}
{{--@endif--}}

