<?php

namespace App\Http\Controllers\Admin\GafaFit;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\GafaFit\CatalogUser;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;


class UserController extends AdminController
{
    /**
     * UserController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_VIEW)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_EDIT)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_CREATE)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_DELETE)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->admin = $admin;
    }

    /**
     * Vista indice donde muestra todos los usuarios
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogUser::class));
        }

        $catalogo = new CatalogUser();

        return VistasGafaFit::view('admin.gafafit.users.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * Vista para la edicion usuario solo si tiene los permisos con la variable userToEdit llena el formulario con
     * los datos del usuario a editar
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function edit(User $user)
    {
        return VistasGafaFit::view('admin.gafafit.users.edit.index', [
            'userToEdit' => $user,
            'urlForm'    => route('admin.users.save', [
                'user' => $user,
            ]),
        ]);
    }

    /**
     * Vista creacion de un nuevo usuario se le asigna un id de compañia al momento de hacer el perfil
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return VistasGafaFit::view('admin.gafafit.users.create.index', [
            'urlForm' => route('admin.users.save.new'),
        ]);
    }

    /**
     * @param Request $request
     * @param User    $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, User $user)
    {
        CatalogFacade::save($request, CatalogUser::class);

        return redirect()->back();
    }


    /**
     * Create user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request)
    {
        $nuevo = CatalogFacade::save($request, CatalogUser::class);

        return redirect()->to($nuevo->link());
    }
}
