<?php

namespace App\Http\Controllers;

use App\Admin;

class AdminController extends Controller
{
    /**
     * @var Admin
     */
    protected $admin;

    /**
     * CompanyController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {

        $this->admin = $admin;
    }

    /**
     * @return Admin
     */
    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}
