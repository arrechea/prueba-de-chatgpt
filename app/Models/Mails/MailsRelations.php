<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 25/06/2018
 * Time: 01:56 PM
 */

namespace App\Models\Mails;


use App\Models\Brand\Brand;

trait MailsRelations
{

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id')->withTrashed();
    }

}
