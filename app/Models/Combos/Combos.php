<?php

namespace App\Models\Combos;

use App\Interfaces\IsProduct;
use App\Models\Purchase\Purchasable;
use App\Models\User\UseUserCategory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combos extends Purchasable implements IsProduct
{
    use SoftDeletes, combosRelationship, UseUserCategory;

    protected $casts = [
        'hide_in_home' => 'boolean',
    ];

    /**
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.brand.marketing.combos.index', [
            'company' => $this->company,
            'brand'   => $this->brand,
            'Combos'  => $this->id,
        ]);
    }
}
