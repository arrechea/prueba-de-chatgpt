
@if(\App\Librerias\Permissions\LibPermissions::userCan(Auth::user(), \App\Librerias\Permissions\LibListPermissions::COMPANY_EDIT))
    <a class="gafa-e-btn is-tool" href="{{route('admin.credits.edit', ['gafacredit' => $creditsGafa->id])}}"><i
            class="material-icons ">mode_edit</i></a>
@endif


