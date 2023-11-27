<h5 class="header card-title">{{__('company.UserCategoriesData')}}</h5>

<form class="row" method="post" action="{{ $urlForm }}" autocomplete="off" enctype="multipart/form-data">
    @include('admin.common.alertas')
    <input hidden name="companies_id" value="{{ $company->id }}">
    @if (isset($category))
        <input type="hidden" name="id" value="{{ $category->id }}">
    @endif
    {{ csrf_field() }}
    <div class="col s12 m8">
        <div class="card-panel panelcombos">
            <div class="row">
                <div class="input-field col s12 m12 l9">
                    <input type="text" id="name" class="input" name="name"
                           value="{{ old('name', $category->name ?? '') }}" required>
                    <label for="name">{{ __('company.Name') }}</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m12 l9">
                    <textarea type="text" id="description" class="materialize-textarea"
                              name="description">{{ old('description', (isset($category) ? $category->description : '')) }}</textarea>
                    <label for="description" class="active">{{ __('company.Description') }}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col s12 m4">
        <div class="row">
            <div class="col s12 m12 l12 input-field">
                <button type="submit" class="waves-effect waves-light btn btnguardar">
                    <i class="material-icons right small">save</i>{{__('company.Save')}}
                </button>
            </div>
        </div>

        @if (isset($category) && (0 === count($category->profiles)))
            <div class="row">
                <div class="col s12 m12 l7 edit-buttons">
                    @if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(),\App\Librerias\Permissions\LibListPermissions::USER_EDIT, $company))
                        <a class="waves-effect waves-light btn btnguardar" href="#eliminar_b"
                           style="background-color: grey">
                            <i class="material-icons right small">clear</i>{{__('company.Delete')}}
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <input type="hidden" id="companies_id" name="companies_id"
           value="{{ old('companies_id', isset($category) ? $category->companies_id : $company->id) }}">
</form>

@if (isset($category))
    <!-- Estructura del modal donde llama a la vista parcial -->
    <div id="eliminar_b" class="modal modal-fixed-footer modaldelete" data-method="get"
         data-href="{{ route('admin.company.user_categories.delete.post', ['company' => $company, 'category' => $category->id]) }}">
        <div class="modal-content"></div>
        <div class="modal-footer">
            <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer" href="#">
                <i class="material-icons small">clear</i>{{__('brand.Cancel')}}
            </a>
            <button type="submit" data-name="user-category"
                    class="s12 modal-action modal-close waves-effect waves-green btn edit-button model-delete-button">
                <i class="material-icons small mat">done</i> {{__('gafacompany.Delete')}}
            </button>
        </div>
    </div>
@endif
