<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 4/9/2019
 * Time: 12:59
 */

namespace App\Models\Credit;


use App\Models\Brand\Brand;
use App\Models\Company\Company;

trait CreditsBrandRelations
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

    public function inGafa(){
        $this->gafa_fit = true;
    }


}
