<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 18/07/18
 * Time: 10:10
 */

namespace App\Librerias\Chart;


class Row
{
    /**
     * Cells
     *
     * @var array
     */
    public $c = [];


    /**
     * @param Cell $cell
     */
    public function addCell(Cell $cell)
    {
        $this->c[] = [
            'v' => $cell->getValue(),
            'f' => $cell->getFormat(),
            'p' => $cell->getProperties(),
        ];
    }

    /**
     * @return array
     */
    public function getCells(): array
    {
        return $this->c;
    }
}
