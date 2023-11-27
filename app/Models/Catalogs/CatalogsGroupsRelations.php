<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 26/11/2018
 * Time: 12:48 PM
 */

namespace App\Models\Catalogs;


trait CatalogsGroupsRelations
{
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function canRepeat(): bool
    {
        return $this->can_repeat === true || $this->can_repeat === 1;
    }

    public function fields()
    {
        return $this->hasMany(CatalogField::class, 'catalogs_groups_id');
    }

    public function activeFields()
    {
        return $this->fields()->where('status', 'active');
    }

    public function controls()
    {
        return $this->hasMany(CatalogsGroupsControl::class, 'catalogs_groups_id');
    }

    public function getConcatSections()
    {
        $controls = $this->controls;
        $controls->groupBy('catalogs_groups_id');
        $sections = $controls->map(function ($item) {
            $new = new CatalogsGroupsControl();
            $new->section = __("catalog-sections.$item->section");

            return $new;
        });

        return $sections->pluck('section')->implode(', ');
    }

    public function catalog(){
        return $this->belongsTo(Catalogs::class,'catalogs_id');
    }
}
