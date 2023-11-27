<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 12/02/2019
 * Time: 17:09
 */

namespace App\Models\Purchase;


use App\Models\GafaFitModel;

abstract class Purchasable extends GafaFitModel
{
    abstract public function creditsCollection();

    public function isSubscribable()
    {
        return $this->subscribable === 1 || $this->subscribable;
    }
}
