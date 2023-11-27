<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 27/06/2018
 * Time: 04:13 PM
 */

namespace App\Models\Purchase;


use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailsPurchase extends GafaFitModel
{
    use SoftDeletes;
    protected $table = 'mails_purchases';
}
