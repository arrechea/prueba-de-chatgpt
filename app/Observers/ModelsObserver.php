<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 28/02/18
 * Time: 16:56
 */

namespace App\Observers;

use App\Librerias\Models\LibSlugs;
use App\Models\Catalogs\InterfaceSpecialTexts;
use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ModelsObserver
{
    /**
     * Listen to the Model created event.
     *
     * @param GafaFitModel|Model $model
     *
     * @return void
     */
    public function creating(GafaFitModel $model)
    {
        $tabla = $model->getTable();

        if ($model instanceof InterfaceSpecialTexts) {
            $this->slugDefault($model, function (&$builder) use ($model) {
                $builder->where('companies_id', $model->companies_id);
//                $builder->where('brands_id', $model->brands_id);
            });
        } else if (Schema::hasColumn($tabla, 'slug')) {
            $this->slugDefault($model);
        }
    }

    /**
     * @param GafaFitModel  $model
     * @param \Closure|null $filter
     *
     * @internal param bool $isSpecialText
     */
    private function slugDefault(GafaFitModel $model, \Closure $filter = null)
    {
        /*
         * Generate slug
         */
        $columnsToSlug = $model->getColumnsForSlugify();
        $claseModelo = get_class($model);
        $title = '';
        if (count($columnsToSlug) > 0) {
            foreach ($columnsToSlug as $column) {
                $title .= (string)($model->$column);
            }
        }

        $slug = LibSlugs::GenerateSlug($claseModelo, $title, null, $filter);
        if (!empty($slug)) {
            //Asignamos el slug
            $model->slug = $slug;
        }
    }
}
