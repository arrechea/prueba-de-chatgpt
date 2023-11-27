<?php

namespace App\Librerias\Models;

use Illuminate\Support\Str as Str;

abstract class LibSlugs
{

    /**
     * Regresa una clase instanciada por slug
     *
     * @param string $class
     * @param string $slug
     *
     * @return mixed
     */
    final static public function GetOneBySlug(string $class, string $slug)
    {
        return $class::where('slug', '=', $slug)->first();
    }

    /**
     * Genera slugs
     *
     * @param string        $class
     * @param string        $name
     * @param null|int      $id
     *
     * @param \Closure|null $filter
     *
     * @return string
     */
    final static public function GenerateSlug(string $class, string $name, $id = null, \Closure $filter = null)
    {
        $slug = Str::slug($name);
        if ($id != null) {
            // -- If the element exists in the DB verify the slug
            $item = $class::find($id);
            if ($item != null && preg_match("/" . $slug . "(-[0-9]+)?/", $item->slug)) {
                return $item->slug;
            }
        }

        $builder = $class::where('slug', 'regexp', "^$slug-(-[0-9]+)?")
            ->orWhere('slug', $slug);
        if (method_exists($class, 'bootSoftDeletes')) {
            //$builder es softDelete
            $builder->withTrashed();
        }
        if ($filter) {
            $filter($builder);
        }
        $view = $builder->get();

        if (count($view) == 0)
            return $slug;
        $slug_max = 0;
        foreach ($view as $elem) {
            $temp_slug = $elem->slug;
            $slug_array = explode("-", $temp_slug);
            $slug_int = end($slug_array);
            if (is_numeric($slug_int)) {
                $slug_max = $slug_int > $slug_max ? $slug_int : $slug_max;
            }
        }
        $sufix = ($slug_max + 1);
        $slug_mod = $slug . "-" . $sufix;

        return $slug_mod;
    }
}
