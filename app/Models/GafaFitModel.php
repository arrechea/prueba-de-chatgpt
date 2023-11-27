<?php

namespace App\Models;

use App\Models\Brand\Brand;
use App\Observers\ModelsObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class GafaFitModel extends Model
{
    static $columnDataSchema = [];

    /**
     * Boot System
     */
    protected static function boot()
    {
        parent::boot();
        //Registramos el observador global de modelos
        static::observe(ModelsObserver::class);
    }

    /**
     * Nombre de la columna para transformar en slug
     *
     * @return string
     * @deprecated
     */
    public function getColumnForSlugify(): string
    {
        return 'name';
    }

    /**
     * @return array
     */
    public function getColumnsForSlugify(): array
    {
        return [
            'name',
        ];
    }

    /**
     * Url
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        $tabla = $this->getTable();

        return $this->getColumnDataSchema($tabla);
    }

    /**
     * Get column structure
     *
     * @param string $tabla
     *
     * @return mixed
     */
    protected function getColumnDataSchema(string $tabla)
    {
        if (!isset(static::$columnDataSchema[ $tabla ])) {
            static::$columnDataSchema[ $tabla ] = Schema::hasColumn($tabla, 'slug') ? 'slug' : 'id';
        }

        return static::$columnDataSchema[ $tabla ];
    }

    /*    public function getArrayableItems(array $values)
        {
            //Fake attributes
            if (!in_array('created_at_timezoned', $this->appends)) {
                $this->appends[] = 'created_at_timezoned';
            }

            return parent::getArrayableItems($values);
        }*/

    /**
     *
     */
    public function getCreatedAtTimezonedAttribute()
    {
        $time = $this->created_at;

        return (new Carbon($time->toDateTimeString()))->setTimezone($this->getTimezone());
    }

    /**
     * @return Carbon
     */
    public function now(): Carbon
    {
        //timezone of brand
        if ($brand = $this->getBrandForTime()) {
            return $brand->now();
        }

        //timezone app
        return Carbon::now();
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getTimezone(): string
    {
        //timezone of brand
        if ($brand = $this->getBrandForTime()) {
            return $brand->getTimezone();
        }

        //timezone app
        return config('app.timezone');
    }

    /**
     * @return Brand|null
     */
    private function getBrandForTime()
    {
        if (method_exists($this, 'brand')) {
            $brand = $this->brand;
            if (
                !$brand
                &&
                !is_null($this->brands_id)
            ) {
                $brand = Brand::withTrashed()->where('id', $this->brands_id)->first();
            }

            return $brand;
        }

        return null;
    }

    /**
     * @param mixed $value
     *
     * @return \Illuminate\Support\Carbon
     */
    protected function asDateTime($value)
    {
        $time = parent::asDateTime($value);

        return new \Illuminate\Support\Carbon($time->toDateTimeString(), $this->getTimezone());
    }
}
