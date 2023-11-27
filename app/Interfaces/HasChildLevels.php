<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 06/03/18
 * Time: 12:27
 */

namespace App\Interfaces;


use Illuminate\Support\Collection;

interface HasChildLevels
{
    /**
     * Relation uses for permissions
     *
     * @return Collection
     */
    function childsLevels(): Collection;
}
