<?php

namespace App\Jobs;

use App\Admin;
use App\Models\Log\CompaniesRequests;
use App\Models\Log\SystemLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class LogSystem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $user;
    private $company;
    private $isAdmin;
    private $controller;
    private $parameters;
    private $variables;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $user
     * @param $company
     */
    public function __construct($user, $isAdmin, $company, $controller, $parameters, $variables)
    {
        $this->user = $user;
        $this->company = $company;
        $this->isAdmin = $isAdmin;
        $this->controller = $controller;
        $this->parameters = $parameters;
        $this->variables = $variables;

        $this->onQueue('log_system');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->getUser();
        $company = $this->getCompany();
        $isAdmin = $this->getisAdmin();
        $now = Carbon::now();
//        if ($isAdmin) {
//            SystemLog::insert([
//                'admins_id'  => $user,
//                'controller' => $this->getController(),
//                'parameters' => $this->getParameters(),
//                'variables'  => $this->getVariables(),
//                'updated_at' => $now,
//                'created_at' => $now,
//            ]);
//        }
        if ($company) {
            $year = $now->year;
            $month = $now->month;
            CompaniesRequests::updateOrCreate([
                'companies_id' => $company,
                'year'         => $year,
                'month'        => $month,
            ], [
                'month_requests' => DB::raw('month_requests+1'),
            ]);
        }
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @return mixed
     */
    public function getisAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return mixed
     */
    public function getVariables()
    {
        return $this->variables;
    }
}
