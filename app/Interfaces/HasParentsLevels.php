<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 06/03/18
 * Time: 12:27
 */

namespace App\Interfaces;


use Illuminate\Support\Collection;

interface HasParentsLevels
{
    /**
     * Relation uses for permissions
     *
     * @return Collection
     */
    function parentsLevels(): Collection;
}
