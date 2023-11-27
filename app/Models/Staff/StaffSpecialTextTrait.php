<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 20/06/2018
 * Time: 09:06 AM
 */

namespace App\Models\Staff;


trait StaffSpecialTextTrait
{
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id')->withTrashed();
    }

    public function otherTexts()
    {
        return $this->hasMany(StaffSpecialText::class, 'staff_id', 'staff_id')->orderBy('order');
    }

    public function tableModel()
    {
        return $this->hasOne(StaffSpecialText::class, 'id', 'id')->withTrashed();
    }
}
