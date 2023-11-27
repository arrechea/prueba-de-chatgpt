<?php

namespace App\Librerias\Catalog;

use Illuminate\Database\Eloquent\Collection;

final class LibCatalogoRelationCustom extends LibCatalogoRelation
{
    private $options = [];
    /** @noinspection PhpMissingParentConstructorInspection */

    /**
     * LibCatalogoRelation constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = $options;
    }

    /**
     * @param $id integer Valor a buscar fuera
     * @return string
     */
    public function GetForeignName($id)
    {
        $opciones = $this->options;
        $foreignKey = $this->getForeignKey();
        $foreignName = $this->GetForeignNameColumn();

        foreach ($opciones as $opcion) {
            if ($opcion->$foreignKey == $id) {
                return $opcion->$foreignName;
            }
        }

        return 'desconocido';
    }

    /**
     * Devuelve el nombre de la columna a traer
     * @return string
     */
    public function GetForeignNameColumn()
    {
        return 'valor';
    }

    /**
     * Devuelve todas las posibles asignaciones que puede tener
     * @return Collection
     */
    public function GetAllPosibleRelationships()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getForeignKey()
    {
        return 'id';
    }
}
