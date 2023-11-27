<h5 class="header card-title">{{__('administrators.AdminData')}}</h5>
<form class="row" method="post" action="{{$urlForm}}" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($admin))
        <input type="hidden" name="id" value="{{$admin->id}}">
    @endif
    {{csrf_field()}}
    <?php
    $name_array = isset($admin->name) ? explode(' ', $admin->name) : [
        0 => '',
        1 => ''
    ];
    ?>
    <div class="col s12 m8">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="input-field col s12 m12 l3 ">
                    <input type="text" id="first_name" class="input" name="first_name"
                           value="{{old('first_name',($name_array[0] ?? ''))}}"
                           required>
                    <label for="first_name">{{__('administrators.Name')}}</label>
                </div>
                <div class="input-field col s12 m12 l3 ">
                    <input type="text" id="lastname" class="input" name="last_name"
                           value="{{old('last_name',($name_array[1] ?? ''))}}" required>
                    <label for="lastname">{{__('administrators.Lastname')}}</label>
                </div>
                <div class="row">
                    <div class="col s12 m12 l6">
                        <div class="input-field ">
                            <input type="email" id="email" class="input" name="email"
                                   value="{{old('email',($admin->email ?? ''))}}" required>
                            <label for="email">{{__('administrators.E-mail')}}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l6">
                        <div class="input-field  ">
                            <input type="password" id="pass" class="input"
                                   {{isset($admin) ? '' : 'required'}} name="password">
                            <label for="pass">{{__('administrators.Password')}}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m12 l6">
                        <div class="input-field ">
                            <input type="password" id="repass" class="input"
                                   {{isset($admin) ? '' : 'required'}} name="password_confirmation">
                            <label for="repass">{{__('administrators.ConfirmPassword')}} </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(isset($profiles))
            <h5 class="">{{__('administrators.Profiles')}}</h5>
            <div class="card-panel panelcombos">
                {{--<div class="card-content">--}}
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{__('administrators.profiles.status') }}</th>
                        <th>{{__('administrators.profiles.company')}}</th>
                        <th>{{__('administrators.profiles.name')}}</th>
                        <th>{{__('administrators.profiles.actions')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($profiles as $profile)
                        <tr class="collection-item">
                            <td>
                                @include('admin.common.check',['active'=>($profile['status']==='active')])
                            </td>
                            <td>
                                {{$profile['company']}}
                            </td>
                            <td>{{$profile['name']}}</td>
                            <td>
                                @if($profile['url'])
                                    <a href='{{$profile['url']}}'><i
                                            class='material-icons'>mode_edit</i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{--</div>--}}
            </div>
        @endif
    </div>
    <div class="col s12 m4">
        <div class="row">
            <div class="card-panel panelcombos">
                <div class="file-field input-field">
                    <div class="uploadPhoto">
                        <img src="{{$admin->pic??''}}" width="180px" alt=""
                             class="responsive-img uploadPhoto--image"/> <br>
                        <h5 class="header"><i
                                class="material-icons small">add_a_photo</i> {{__('administrators.ProfilePhoto')}}
                        </h5>
                        <input type='file' class="uploadPhoto--input" name="pic"/>
                    </div>
                </div>
            </div>

            <div class="switch">
                <label>
                    {{__('administrators.Inactive')}}
                    <input type="checkbox"
                           @if(!!old()) {!! old('status','')==='on' ? 'checked' : '' !!}
                           @else {!! isset($admin) && $admin->isActive() ? 'checked' : '' !!}
                           @endif
                           name="status">
                    <span class="lever"></span>
                    {{__('administrators.Active')}}
                </label>
            </div>
            @if(isset($admin) && !$admin->isActive())
                <div class="col s12 m12 l12">
                    <p style="color: var(--alert-color)">{{__('administrators.ActiveWarning')}}</p>
                </div>
            @endif

            {{--Aqui los botones para la opcion de editar usuario solo si se tiene la variable admin --}}
            @if( isset($admin))
            <!-- Modal Trigger -->
                <a class=" waves-effect waves-light btn deep-purple lighten-3 modalGafaFit btnasignacionr"
                   {{-- devuelve vista parcial de asignacion de roles para editar--}}
                   href="#User--roles"
                   style="margin: 0;">{{__('administrators.AssignRoles')}}</a>
            @endif
            <button type="submit" class="waves-effect waves-light btn btnguardar"><i
                    class="material-icons right small">save</i>{{__('administrators.Save')}}</button>
        </div>


        {{--@if (isset($admin))--}}
        {{--<div class="row">--}}
        {{--<div class="col s12 m12 l6 edit-buttons">--}}
        {{--@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::ADMIN_DELETE))--}}
        {{--<a class="waves-effect waves-light btn btnguardar" href="#eliminar_admin"--}}
        {{--style="background-color: grey"><i--}}
        {{--class="material-icons right small">clear</i>{{__('gafacompany.Delete')}}</a>--}}
        {{--@endif--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--@endif--}}

    </div>
</form>
@if(isset($admin))
    <div id="User--roles" class="User--assignmentRoles modal modal-fixed-footer modalroles"
         data-method="get"
         data-href="{{route('admin.administrator.assignmentRoles',[
                    'admin'=>$admin->id
                    ])}}">
        <div class="modal-content">@cargando</div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
               href="#"> <i class="material-icons small">clear</i>
                {{__('brand.Cancel')}}</a>
            <a type="submit"
               class="modal-action modal-close waves-effect waves-green btn edit-button">
                <i class="material-icons small">done</i>
                {{__('administrators.Save')}}</a>
        </div>
    </div>
@endif

@if (isset($admin))
    <div id="eliminar_admin" class="modal modal - fixed - footer modaldelete" data-method="get"
         data-href="{{route('admin.administrator.delete', ['admin' => $admin->id,])}}">
        <div class="modal-content"></div>
    </div>
@endif
