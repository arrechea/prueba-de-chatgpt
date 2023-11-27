<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 03/08/2018
 * Time: 10:38 AM
 */

namespace App\Models\CompaniesColors;


use App\Models\Company\Company;

trait ColorRelations
{

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id')->withTrashed();
    }
}
