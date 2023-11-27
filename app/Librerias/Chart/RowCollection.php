<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 18/07/18
 * Time: 10:07
 */

namespace App\Librerias\Chart;


class RowCollection
{
    /**
     * @var Row[]
     */
    private $rows = [];

    /**
     * @param Row $row
     */
    public function addRow(Row $row)
    {
        $this->rows[] = $row;
    }

    /**
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }
}
