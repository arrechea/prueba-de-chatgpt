<?php

namespace App\Models\User;

use App\Models\Company\Company;
use App\Models\GafaFitModel;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCategory extends GafaFitModel
{
    use UserCategoriesTrait, SoftDeletes;

    protected $guarded = [];
}
