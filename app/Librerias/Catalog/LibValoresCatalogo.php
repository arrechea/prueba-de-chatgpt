<?php

namespace App\Librerias\Catalog;

use Closure;

final class LibValoresCatalogo
{
    private $tag = '';
    private $columna = '';
    /**
     * @var LibCatalogoModel
     */
    private $model = null;

    private $type = null;
    private $required = true;
    private $hiddenInList = false;
    private $validator = null;
    private $other = null;
    private $valueName = '';
    private $forzeNullable = false;
    private $notOrdenable = false;

    /**
     * @var Closure
     */
    private $GetValueNameFilter;
    /**
     * @var Closure|null
     */
    private $extraSave;

    /**
     * LibValoresCatalogo constructor.
     *
     * @param LibCatalogoModel $model
     * @param string           $tag     Nombre que vamos a usar para el usuario
     * @param string           $columna Nombre de la columna de la db que jalara el dato
     * @param array            $options
     * @param Closure|null     $extraSave
     */
    public function __construct(LibCatalogoModel $model, $tag, $columna, $options = [], Closure $extraSave = null)
    {
        $defaultOptions = [
            'type'          => 'text',
            'required'      => true,
            'validator'     => 'required|max:100',
            'other'         => null,//relacion con otro modelo
            'hiddenInList'  => false,
            'forzeNullable' => false,
            'notOrdenable'  => false,
        ];
        $defaultOptions = array_merge($defaultOptions, $options);

        //Seteo de opciones
        $this->type = $defaultOptions['type'];
        $this->setRequired($defaultOptions['required']);
        $this->setHiddenInList($defaultOptions['hiddenInList']);
        $this->setForzeNullable($defaultOptions['forzeNullable']);
        $this->setNotOrdenable($defaultOptions['notOrdenable']);
        $this->validator = $defaultOptions['validator'];
        if ($defaultOptions['other']) {
            $this->setOther($defaultOptions['other']);
        }
        //Datos principales
        $this->setTag($tag);
        $this->columna = $columna;
        $this->model = $model;
        //Value name por defecto
        $this->DefaultValueName();

        $this->setGetValueNameFilter(function ($LibValoresCatalogo, $value) {
            return $value;
        });
        $this->extraSave = $extraSave;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    public function isDotColumn()
    {
        $column = $this->getColumna();
        $col_array = explode('.', $column);

        return (count($col_array) > 1);
    }

    /**
     * @return string
     */
    public function GetValue()
    {
        $columna = $this->columna;
        $col_array = explode('.', $columna);
        if (count($col_array) > 1) {
            $column = $col_array[0];
            $value = $this->model->$column;
            unset($col_array[0]);
            $dot_key = implode('.', $col_array);
            if (is_array($value)) {
                return array_get($value, $dot_key);
            }
        }

        return $this->model->$columna;
    }

    /**
     * Predefine el value name
     *
     */
    private function DefaultValueName()
    {
        $valor = $this->GetValue();
        if ($this->other instanceof LibCatalogoRelation) {
            //El value name es distinto
            $valor = $this->other->GetForeignName($valor);
        }

        $this->setValueName(($valor ?? ''));
    }

    /**
     * @param LibCatalogoRelation $other
     */
    private function setOther(LibCatalogoRelation $other)
    {
        $this->other = $other;
    }

    /**
     * @return string
     */
    public function getColumna()
    {
        return $this->columna;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Consulta si es requerido o no
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return null
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @return null
     */
    public function other()
    {
        return $this->other;
    }

    /**
     * @param bool $required
     */
    private function setRequired($required)
    {
        if (is_bool($required)) {
            $this->required = $required;
        }
    }

    /**
     * @param $valueName
     */
    public function setValueName($valueName)
    {
        $this->valueName = $valueName;
    }

    /**
     * @return LibCatalogoModel
     */
    public function getModel(): LibCatalogoModel
    {
        return $this->model;
    }

    /**
     * @param Closure $GetValueNameFilter
     *
     * @return $this
     */
    public function setGetValueNameFilter(Closure $GetValueNameFilter)
    {
        $this->GetValueNameFilter = $GetValueNameFilter;

        return $this;
    }

    /**
     * @return string
     */
    public function GetValueName()
    {
        $filter = $this->getGetValueNameFilter();

        return !empty($filter) ? $filter($this, $this->valueName) : $this->valueName;
    }

    /**
     * @return Closure
     */
    public function getGetValueNameFilter()
    {
        return $this->GetValueNameFilter;
    }

    /**
     * @param string $tag
     */
    public function setTag(string $tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return Closure|null
     */
    private function getExtraSave()
    {
        return $this->extraSave;
    }

    /**
     * Extra en salvado
     */
    public function runExtraSave()
    {
        $closure = $this->getExtraSave();
        if ($closure) {
            $closure();
        }
    }

    /**
     * @return bool
     */
    public function isHiddenInList()
    {
        return $this->hiddenInList;
    }

    /**
     * @param bool $hiddenInList
     */
    public function setHiddenInList($hiddenInList)
    {
        $this->hiddenInList = $hiddenInList;
    }

    /**
     * @return bool
     */
    public function isForzeNullable(): bool
    {
        return $this->forzeNullable;
    }

    /**
     * @param bool $forzeNullable
     */
    public function setForzeNullable(bool $forzeNullable)
    {
        $this->forzeNullable = $forzeNullable;
    }

    /**
     * @return bool
     */
    public function isNotOrdenable(): bool
    {
        return $this->notOrdenable;
    }

    /**
     * @param bool $notOrdenable
     */
    public function setNotOrdenable(bool $notOrdenable)
    {
        $this->notOrdenable = $notOrdenable;
    }
}
