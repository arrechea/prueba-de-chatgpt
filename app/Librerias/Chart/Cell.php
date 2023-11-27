<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 18/07/18
 * Time: 10:29
 */

namespace App\Librerias\Chart;


class Cell
{
    private $value;
    /**
     * @var string
     */
    private $format;
    /**
     * @var null
     */
    private $properties;

    function __construct($value, string $format = null, $properties = null)
    {

        $this->value = $value;
        $this->format = $format;
        $this->properties = $properties;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * @return null
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
