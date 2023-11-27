<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Products\CatalogCategories;
use App\Librerias\Catalog\Tables\Brand\Products\CatalogProduct;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Products\Category;
use App\Models\Products\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends BrandLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::PRODUCTS_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::PRODUCTS_EDIT, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
            'saveCategory',
        ]);

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::PRODUCTS_CREATE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
            'saveNewCategory',
        ]);

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::PRODUCTS_DELETE, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deleteCategory',
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param bool    $marketing
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexSaas(Request $request, Company $company, Brand $brand)
    {
        $categories = Category::where([
            'companies_id' => $company->id,
            'brands_id'    => $brand->id,
        ])->withCount('products')->get();

        $products = Product::where([
            ['companies_id', $company->id],
            ['brands_id', $brand->id],
        ])->get();

        $categoryUrl = route('admin.company.brand.products.category.save', [
            'company'  => $company,
            'brand'    => $brand,
            'category' => '_|_',
        ]);

        $categoryNewUrl = route('admin.company.brand.products.category.save.new', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        $categoryDeleteUrl = route('admin.company.brand.products.category.delete', [
            'company'  => $company,
            'brand'    => $brand,
            'category' => '_|_',
        ]);

        $productUrl = route('admin.company.brand.products.save', [
            'company' => $company,
            'brand'   => $brand,
            'product' => '_|_',
        ]);

        $productNewUrl = route('admin.company.brand.products.save.new', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        $productDeleteUrl = route('admin.company.brand.products.delete', [
            'company' => $company,
            'brand'   => $brand,
            'product' => '_|_',
        ]);

        $productListUrl = route('admin.company.brand.products.list', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        return VistasGafaFit::view('admin.brand.products.management.index-saas', [
            'categories'        => $categories,
            'categoryUrl'       => $categoryUrl,
            'categoryNewUrl'    => $categoryNewUrl,
            'categoryDeleteUrl' => $categoryDeleteUrl,
            'langFile'          => new Collection(Lang::get('item-list')),
//            'products'          => $products,
            'productUrl'        => $productUrl,
            'productNewUrl'     => $productNewUrl,
            'productDeleteUrl'  => $productDeleteUrl,
            'productListUrl'    => $productListUrl
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param bool    $marketing
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand)
    {
        $categories = Category::where([
            'companies_id' => $company->id,
            'brands_id'    => $brand->id,
        ])->withCount('products')->get();

        $products = Product::where([
            ['companies_id', $company->id],
            ['brands_id', $brand->id],
        ])->get();

        $categoryUrl = route('admin.company.brand.products.category.save', [
            'company'  => $company,
            'brand'    => $brand,
            'category' => '_|_',
        ]);

        $categoryNewUrl = route('admin.company.brand.products.category.save.new', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        $categoryDeleteUrl = route('admin.company.brand.products.category.delete', [
            'company'  => $company,
            'brand'    => $brand,
            'category' => '_|_',
        ]);

        $productUrl = route('admin.company.brand.products.save', [
            'company' => $company,
            'brand'   => $brand,
            'product' => '_|_',
        ]);

        $productNewUrl = route('admin.company.brand.products.save.new', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        $productDeleteUrl = route('admin.company.brand.products.delete', [
            'company' => $company,
            'brand'   => $brand,
            'product' => '_|_',
        ]);

        $productListUrl = route('admin.company.brand.products.list', [
            'company' => $company,
            'brand'   => $brand,
        ]);

        return VistasGafaFit::view('admin.brand.products.management.index', [
            'categories'        => $categories,
            'categoryUrl'       => $categoryUrl,
            'categoryNewUrl'    => $categoryNewUrl,
            'categoryDeleteUrl' => $categoryDeleteUrl,
            'langFile'          => new Collection(Lang::get('item-list')),
//            'products'          => $products,
            'productUrl'        => $productUrl,
            'productNewUrl'     => $productNewUrl,
            'productDeleteUrl'  => $productDeleteUrl,
            'productListUrl'    => $productListUrl
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request, Company $company, Brand $brand)
    {
        $categories_id = $request->input('category');

        $category = Category::withCount('products')->find($categories_id);

        return response()->json([
            'products' => Product::where([
                ['companies_id', $company->id],
                ['brands_id', $brand->id],
                ['product_categories_id', $category->id ?? null],
            ])->get(),
            'category' => $category,
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        $request->merge([
            'companies_id' => $company->id,
            'brands_id'    => $brand->id,
        ]);

        $product = CatalogFacade::save($request, CatalogProduct::class);

        return $product;
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Product $product
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel|Product
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, Brand $brand, Product $product)
    {
        $request->merge([
            'companies_id' => $company->id,
            'brands_id'    => $brand->id,
        ]);

        $product = CatalogFacade::save($request, CatalogProduct::class);

        return $product;
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param Product $product
     *
     * @return Product
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request, Company $company, Brand $brand, Product $product)
    {
        $request->merge([
            'id' => $product->id,
        ]);

        CatalogFacade::delete($request, CatalogProduct::class);

        return $product;
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNewCategory(Request $request, Company $company, Brand $brand)
    {
        $request->merge([
            'companies_id' => $company->id,
            'brands_id'    => $brand->id,
        ]);

        $category = CatalogFacade::save($request, CatalogCategories::class);
        $category->products_count = 0;

        return $category;
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Category $category
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel|Category
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveCategory(Request $request, Company $company, Brand $brand, Category $category)
    {
        $request->merge([
            'companies_id' => $company->id,
            'brands_id'    => $brand->id,
        ]);

        $category = CatalogFacade::save($request, CatalogCategories::class);
        $category->products_count = $category->products->count();

        return $category;
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Category $category
     *
     * @return Category
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deleteCategory(Request $request, Company $company, Brand $brand, Category $category)
    {
        $request->merge([
            'id' => $category->id,
        ]);

        CatalogFacade::delete($request, CatalogCategories::class);

        return $category;
    }
}
