<?php

namespace App\Librerias\Catalog;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LibCatalogoRelation
{
    protected $foreignKey = 'id';
    protected $foreignName = 'nombre';
    protected $orderby = 'orden';
    protected $order = 'asc';
    /**
     * @var string modelName
     */
    protected $model = '';
    /**
     * @var Model Modelo como tal
     */
    private $element;

    /**
     * LibCatalogoRelation constructor.
     *
     * @param        $className
     * @param string $foreignKey  Sirve para identificar el id en la otra tabla
     * @param string $foreignName Sirve para saber la columna del nombre
     * @param string $orderby
     * @param string $order
     */
    public function __construct($className, $foreignKey = 'id', $foreignName = 'nombre', $orderby = 'nombre', $order = 'asc')
    {
        if (is_array($className)) {
            $this->model = $className[0];
            $this->element = $className[1];
        } else {
            $this->model = $className;
        }
        $this->foreignKey = $foreignKey;
        $this->foreignName = $foreignName;
        $this->orderby = $orderby;
        $this->order = $order;
    }

    /**
     * @param $id integer Valor a buscar fuera
     *
     * @return string
     */
    public function GetForeignName($id)
    {
        if ($id) {

            $model = $this->model;
            $foreignName = $this->foreignName;

            if ($this->element) {
                $externo = $this->element;
            } else {
                if (config('debug') === true) {
                    var_dump($this->model, $id);//se puso esto para mejorar los catalogos
                }
                $externo = $model::find($id);
            }

            return $externo ? $externo->$foreignName : '---';
        }

        return '';
    }

    /**
     * Devuelve el nombre de la columna a traer
     *
     * @return string
     */
    public function GetForeignNameColumn()
    {
        return $this->foreignName;
    }

    /**
     * Devuelve todas las posibles asignaciones que puede tener
     *
     * @return Collection
     */
    public function GetAllPosibleRelationships()
    {
        $model = $this->model;

        $externo = $model::orderby($this->orderby, $this->order)->get();

        return $externo;
    }

    /**
     * @return string
     */
    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }
}
