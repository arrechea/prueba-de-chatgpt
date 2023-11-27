<?php

namespace App\Http\Controllers\Admin\Company;

use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogUserCategory;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Company\Company;
use App\Models\User\UserCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserCategoryController extends CompanyLevelController
{
    /**
     * UserCategoryController constructor.
     *
     * @param \App\Admin $admin
     */
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();

        $this
            ->middleware(
                function ($request, $next) use ($company) {
                    $user = auth()->user();

                    if (LibPermissions::userCannot($user, LibListPermissions::USER_VIEW, $company)) {
                        throw new NotFoundHttpException();
                    }

                    return $next($request);
                }
            );

        $this
            ->middleware(
                function ($request, $next) use ($company) {
                    if (\request()->ajax()) {
                        $filters = new Collection((array) $request->get('filters', []));

                        if (!$filters) {
                            return abort(404);
                        }

                        $companies_id = (int) $filters->filter(
                                function ($item) {
                                    return $item['name'] === 'comp_id';
                                }
                            )->first()['value'] ?? 0;

                        if ($companies_id !== \request()->route('company')->id) {
                            return abort(404);
                        }
                    }

                    return $next($request);
                }
            )
            ->only(['index']);

        $this
            ->middleware(
                function ($request, $next) use ($company) {
                    $category = \request()->route('category');

                    if (!$category || $category->companies_id != \request()->route('company')->id) {
                        return abort(404);
                    }

                    if ('POST' === \request()->method()) {
                        if (
                            !\request()->has('companies_id') ||
                            \request()->get('companies_id') != \request()->route('company')->id
                        ) {
                            return abort(404);
                        }
                    }

                    $user = auth()->user();

                    if (LibPermissions::userCannot($user, LibListPermissions::USER_EDIT, $company)) {
                        throw new NotFoundHttpException();
                    }

                    return $next($request);
                }
            )
            ->only(['edit', 'saveEdit']);

        $this
            ->middleware(
                function ($request, $next) use ($company) {
                    $category = \request()->route('category');

                    if (!$category || $category->companies_id != \request()->route('company')->id) {
                        return abort(404);
                    }

                    $user = auth()->user();

                    if (LibPermissions::userCannot($user, LibListPermissions::USER_DELETE, $company)) {
                        throw new NotFoundHttpException();
                    }

                    return $next($request);
                }
            )
            ->only(['delete', 'deletePost']);

        $this
            ->middleware(
                function ($request, $next) use ($company) {
                    if ('POST' === \request()->method()) {
                        if (
                            !\request()->has('companies_id') ||
                            \request()->get('companies_id') != \request()->route('company')->id
                        ) {
                            return abort(404);
                        }
                    }

                    $user = auth()->user();

                    if (LibPermissions::userCannot($user, LibListPermissions::USER_CREATE, $company)) {
                        throw new NotFoundHttpException();
                    }

                    return $next($request);
                }
            )
            ->only(['create', 'saveNew']);
    }

    /**
     * Lista de categorías de usuario
     *
     * @param \App\Http\Requests\AdminRequest $request
     * @param \App\Models\Company\Company     $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserCategory::class));
        }

        $catalogo = new CatalogUserCategory();

        return VistasGafaFit::view(
            'admin.company.user_categories.index',
            [
                'catalogo' => $catalogo,
                'company'  => $this->getCompany(),
            ]
        );
    }

    /**
     * Vista de edición de categoría de usuario
     *
     * @param \App\Http\Requests\AdminRequest $request
     * @param \App\Models\Company\Company     $company
     * @param \App\Models\User\UserCategory   $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, UserCategory $category)
    {
        $urlForm = route(
            'admin.company.user_categories.save',
            [
                'company'  => $this->getCompany(),
                'category' => $category->id,
            ]
        );

        return VistasGafaFit::view(
            'admin.company.user_categories.edit.index',
            [
                'urlForm'  => $urlForm,
                'category' => $category,
                'company'  => $this->getCompany(),
            ]
        );
    }

    /**
     * Guardado de los cambios a la categoría
     *
     * @param \App\Http\Requests\AdminRequest $request
     * @param \App\Models\Company\Company     $company
     * @param \App\Models\User\UserCategory   $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, UserCategory $category)
    {
        $entity = CatalogFacade::save($request, CatalogUserCategory::class);

        return redirect()->to($entity->link());
    }

    /**
     * Vista de creación
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company)
    {
        return VistasGafaFit::view(
            'admin.company.user_categories.create.index',
            [
                'urlForm' => route('admin.company.user_categories.save.new', ['company' => $this->getCompany()]),
            ]
        );
    }

    /**
     * Guarda una nueva categoría
     *
     * @param \App\Http\Requests\AdminRequest $request
     * @param \App\Models\Company\Company     $company
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company): RedirectResponse
    {
        $restored = UserCategory::onlyTrashed()
            ->where('name', $request->name)
            ->where('companies_id', $request->companies_id)
            ->restore();

        if (!$restored) {
            CatalogFacade::save($request, CatalogUserCategory::class);
        }

        return redirect()->route('admin.company.user_categories.index', ['company' => $this->getCompany()]);
    }

    /**
     * Vista de borrado de perfil
     *
     * @param \App\Http\Requests\AdminRequest $request
     * @param \App\Models\Company\Company     $company
     * @param \App\Models\User\UserCategory   $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, UserCategory $category)
    {
        return VistasGafaFit::view(
            'admin.company.user_categories.edit.delete',
            [
                'category' => $category,
                'id'       => $category->id,
                'company'  => $this->getCompany(),
            ]
        );
    }

    /**
     * Borrado de perfil
     *
     * @param Request                       $request
     *
     * @param \App\Models\Company\Company   $company
     * @param \App\Models\User\UserCategory $category
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, UserCategory $category)
    {
        CatalogFacade::delete($request, CatalogUserCategory::class);

        return redirect()->route('admin.company.user_categories.index', ['company' => $this->getCompany()]);
    }
}
