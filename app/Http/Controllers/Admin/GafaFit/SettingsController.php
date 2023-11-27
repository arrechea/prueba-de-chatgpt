<?php

namespace App\Http\Controllers\Admin\GafaFit;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Settings\LibSettings;
use App\Librerias\Vistas\VistasGafaFit;

use App\Settings;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TraitConImagen;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class SettingsController extends AdminController
{

    use  TraitConImagen, SoftDeletes;

    /**
     * Settings constructor.
     *
     * @param Admin $admin
     *
     * @internal param Request $request
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SETTINGS_EDIT)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return VistasGafaFit::view('admin.gafafit.settings.index');
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function saveEdit(Request $request)
    {
        $this->validate($request, [

        ]);

        LibSettings::saveSettings($request);

        return redirect()->back();
    }

}

