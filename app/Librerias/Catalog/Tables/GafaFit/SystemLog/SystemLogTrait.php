<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 26/09/2018
 * Time: 01:25 PM
 */

namespace App\Librerias\Catalog\Tables\GafaFit\SystemLog;


use App\Admin;

trait SystemLogTrait
{
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admins_id');
    }
}
