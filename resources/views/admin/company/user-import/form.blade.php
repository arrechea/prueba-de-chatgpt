<h5 class="header card-title">{{__('gafacompany.TitleEdit')}}</h5>
<form method="post" action="{{$urlForm}}" class="row" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    @if(isset($compToEdit))
        <input type="hidden" name="id" value="{{$compToEdit->id}}">
    @endif
    {{csrf_field()}}
    <div class="col s12 m8">
        <h5 class="">{{__('gafacompany.General')}}</h5>
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="col s12 m12 l5">
                    <div class="input-field">
                        <label for="csv">{{__('gafacompany.Name')}}</label>
                        <input type='file' class="" name="csv"/>
                        {{--                        <input type="text" id="name" class="input" name="name"--}}
                        {{--                               value="{{old('name',(isset($compToEdit) ? $compToEdit->name : ''))}}" required>--}}
                        <span class="material-icons">chess_pawn</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col s12 m4">
        <div class="row">

            <div class="col s12 m12 l7 edit-buttons">
                @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_IMPORT_EDIT,$compToEdit))
                    <div class="col s12 m12 l7 edit-buttons">
                        <button type="button" id="test" class="waves-effect waves-light btn btnguardar"><i
                                class="material-icons right small">save</i>{{__('gafacompany.Save')}}</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>
