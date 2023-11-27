<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 04/05/2018
 * Time: 10:42 AM
 */

namespace App\Models\Room;


use App\Librerias\Catalog\Tables\Location\RoomRelationship;
use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends GafaFitModel
{
    use SoftDeletes, RoomRelationship;

    protected $table = 'rooms';

    protected $fillable = [
        'name',
        'companies_id',
        'brands_id',
        'locations_id',
    ];
}
