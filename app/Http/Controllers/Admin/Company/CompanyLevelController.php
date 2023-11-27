<?php

namespace App\Http\Controllers\Admin\Company;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Models\Company\Company;
use Illuminate\Support\Facades\Auth;

class CompanyLevelController extends AdminController
{
    /**
     * @var Company|\Illuminate\Routing\Route|object|string
     */
    private $company;

    /**
     * CompanyLevelController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $companySlug = request()->route('company');

        if ($companySlug instanceof Company) {
            $company = $companySlug;
        } else {
            $company = Company::withTrashed()->where('slug', $companySlug)->first();
        }
        $this->company = $company;
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }
}
