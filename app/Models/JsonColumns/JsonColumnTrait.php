<?php

namespace App\Models\JsonColumns;

use Illuminate\Support\Arr;

trait JsonColumnTrait
{
    /**
     * @param string $field
     *
     * @return bool
     */
    private function isJsonColumn(string $field)
    {
        $casts = $this->casts;

        return isset($casts[ $field ]) && $casts[ $field ] === 'array';
    }

    /**
     * @param string $dot_column
     *
     * @return array|\ArrayAccess|mixed|null
     */
    public function getDotValue(string $dot_column)
    {
        $keys = explode('.', $dot_column);
        $main_column = array_splice($keys, 0, 1)[0];
        if ($this->isJsonColumn($main_column)) {
            $dot_main = implode('.', $keys);
            $values = $this->$main_column;

            return Arr::get($values, $dot_main);
        }

        return null;
    }

    /**
     * @param string $dot_column
     * @param        $value
     * @param bool   $save
     *
     * @return void
     */
    public function setDotValue(string $dot_column, $value, bool $save = false)
    {
        $keys = explode('.', $dot_column);
        $main_column = array_splice($keys, 0, 1)[0];
        if ($this->isJsonColumn($main_column)) {
            $dot_main = implode('.', $keys);
            $values = $this->$main_column;

            Arr::set($values, $dot_main, $value);

            $this->$main_column = $values;
            if ($save) {
                $this->save();
            }
        }
    }

    /**
     * @param string $dot_column
     * @param bool   $save
     *
     * @return void
     */
    public function unsetDotValue(string $dot_column, bool $save = false)
    {
        $keys = explode('.', $dot_column);
        $main_column = array_splice($keys, 0, 1)[0];
        if ($this->isJsonColumn($main_column)) {
            $dot_main = implode('.', $keys);
            $values = $this->$main_column;

            Arr::forget($values, $dot_main);

            $this->$main_column = $values;
            if ($save) {
                $this->save();
            }
        }
    }

    /**
     * @param string $dot_column
     * @param        $value
     * @param bool   $get
     *
     * @return $this
     */
    public static function whereDotColumn(string $dot_column, $value, bool $get = false)
    {
        $keys = explode('.', $dot_column);
        $main_column = array_splice($keys, 0, 1)[0];
        $column_array = [];
        $class = self::class;
        $model = new $class();
        if ($model->isJsonColumn($main_column)) {
            $dot_main = implode('.', $keys);
            Arr::set($column_array, $dot_main, $value);

            $query = $model->whereJsonContains($main_column, $column_array);

            return $get ? $query->get() : $query;
        }

        return $model;
    }
}
