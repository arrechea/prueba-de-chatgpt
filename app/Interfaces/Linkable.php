<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 27/02/18
 * Time: 16:27
 */

namespace App\Interfaces;


interface Linkable
{
    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string;
}
