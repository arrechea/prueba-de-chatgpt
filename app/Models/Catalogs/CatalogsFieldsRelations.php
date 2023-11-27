<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 27/11/2018
 * Time: 11:15
 */

namespace App\Models\Catalogs;


trait CatalogsFieldsRelations
{
    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function catalog_group()
    {
        return $this->belongsTo(CatalogGroup::class, 'catalogs_groups_id');
    }

    public function canRepeat()
    {
        return $this->can_repeat === true || $this->can_repeat === 1;
    }

    public function isHiddenInList()
    {
        return $this->hidden_in_list === true || $this->hidden_in_list === 1;
    }

    public function isShownInList()
    {
        return !$this->isHiddenInList();
    }

    public function isSortable()
    {
        return $this->sortable === true || $this->sortable === 1;
    }

    public function catalog_field_options()
    {
        return $this->hasMany(CatalogsFieldsOptions::class, 'catalogs_fields_id');
    }

    public function activate()
    {
        $this->status = $this->isActive() ? 'inactive' : 'active';
        $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(CatalogGroup::class, 'catalogs_groups_id');
    }
}
