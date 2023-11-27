<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 12/03/18
 * Time: 13:07
 */

namespace App\Librerias\Catalog;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class LibCatalogoResponse
{
    /**
     * @var LibCatalogoModel
     */
    private $model;
    /**
     * @var string
     */
    private $tableName;
    /**
     * @var string
     */
    private $title;
    /**
     * @var LibValoresCatalogo[]
     */
    private $values;
    /**
     * @var Collection|array|LengthAwarePaginator
     */
    private $respuestas;

    /**
     * @return LibCatalogoModel
     */
    public function getModel(): LibCatalogoModel
    {
        return $this->model;
    }

    /**
     * @param LibCatalogoModel $model
     */
    public function setModel(LibCatalogoModel $model)
    {
        $this->model = $model;

        $this->setTitle($model->GetName());
        $this->setValues($model->Valores());
        $this->setTableName($model->getTable());
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName(string $tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    private function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return LibValoresCatalogo[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    private function setValues(array $values)
    {
        $this->values = $values;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getRespuestas(): LengthAwarePaginator
    {
        return $this->respuestas;
    }

    /**
     * @param LengthAwarePaginator $respuestas
     */
    public function setRespuestas(LengthAwarePaginator $respuestas)
    {
        $this->respuestas = $respuestas;
    }
}
