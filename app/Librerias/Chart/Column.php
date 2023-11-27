<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 18/07/18
 * Time: 10:11
 */

namespace App\Librerias\Chart;


class Column
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $type;

    function __construct(string $id, string $label, string $type)
    {

        $this->id = $id;
        $this->label = $label;
        $this->setType($type);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    private function setType(string $type)
    {
        switch ($type) {
            case 'boolean':
            case 'number':
            case 'string':
            case 'date':
            case 'datetime':
            case 'timeofday':
                $this->type = $type;
                break;
            default:
                abort('Tipo de chart erroneo');
                break;
        }
    }
}
