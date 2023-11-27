<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 23/05/2018
 * Time: 01:07 PM
 */

namespace App\Models\Service;


use App\Models\Service;

trait ServiceSpecialTextTrait
{
    /**
     * @return mixed
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id', 'id')->withTrashed();
    }

    public function otherTexts()
    {
        return $this->hasMany(ServiceSpecialText::class, 'services_id', 'services_id')->orderBy('order');
    }

    public function tableModel()
    {
        return $this->hasOne(ServiceSpecialText::class, 'id', 'id')->withTrashed();
    }
}
