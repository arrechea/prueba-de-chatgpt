<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 27/03/2018
 * Time: 03:44 PM
 */
namespace App\Librerias\Helpers;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Collection;


class EmptyRelation extends HasOne
{
    public function getResults()
    {
        return Collection::make();
    }

    public function get($columns = ['*'])
    {
        return Collection::make();
    }
}
