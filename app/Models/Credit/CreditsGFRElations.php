<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 6/25/2019
 * Time: 16:35
 */

namespace App\Models\Credit;


use App\Models\Brand\Brand;
use App\Models\Company\Company;

trait CreditsGFRElations
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credits_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id', 'id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id', 'id')->withTrashed();
    }

}
