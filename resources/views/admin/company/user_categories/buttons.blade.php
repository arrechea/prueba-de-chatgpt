@if (\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::USER_EDIT, $company))
    @php
        $edit_route = route(
            'admin.company.user_categories.edit',
            [
                'company'  => $company,
                'category' => (int) $category->id,
            ]
        );
        $delete_route = route(
            'admin.company.user_categories.delete',
            [
                'company'  => $company,
                'category' => (int) $category->id,
            ]
        );
    @endphp
    <a class="gafa-e-btn is-tool" href="{{ $edit_route }}">
        <i class="material-icons ">mode_edit</i>
    </a>
    @if (0 === count($category->profiles))
        <a class="gafa-e-btn is-tool" href="#eliminar_b" style="background-color: grey">
            <i class="material-icons">delete</i>
        </a>

        <div class="modal modal-fixed-footer modaldelete"
             data-href="{{ route('admin.company.user_categories.delete.post', ['company' => $company, 'id' => $category->id]) }}"
             data-method="get" id="eliminar_b">
            <div class="modal-content"></div>
            <div class="modal-footer">
                <a class="modal-action modal-close waves-effect waves-green btn edit-button save-button-footer"
                   href="#">
                    <i class="material-icons small">clear</i>{{__('brand.Cancel')}}
                </a>
                <button class="s12 modal-action modal-close waves-effect waves-green btn edit-button model-delete-button"
                        data-name="user-category" type="submit">
                    <i class="material-icons small mat">done</i> {{__('gafacompany.Delete')}}
                </button>
            </div>
        </div>
    @endif
@endif
