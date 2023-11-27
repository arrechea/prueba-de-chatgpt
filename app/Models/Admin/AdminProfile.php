<?php

namespace App\Models\Admin;

use App\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminProfile extends Model
{
    use AdminProfileTrait, SoftDeletes;
    protected $table = 'admin_profiles';


}
