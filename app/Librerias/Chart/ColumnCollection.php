<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 18/07/18
 * Time: 10:07
 */

namespace App\Librerias\Chart;


class ColumnCollection
{
    /**
     * @var Column[]
     */
    private $columns = [];

    public function addColumn(Column $column)
    {
        $this->columns[] = [
            'id'    => $column->getId(),
            'label' => $column->getLabel(),
            'type'  => $column->getType(),
        ];
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
