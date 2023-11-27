<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 26/09/2018
 * Time: 12:54 PM
 */

namespace App\Librerias\Catalog\Tables\GafaFit\SystemLog;


use App\Admin;
use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogSystemLog extends LibCatalogoModel
{
    use SystemLogTrait;

    protected $table = 'system_logs';

    public function GetName()
    {
        return 'System Log';
    }

    public function link(): string
    {
        return '';
    }

    public function Valores(Request $request = null)
    {
        $log = $this;

        $button = new LibValoresCatalogo($this, 'Boton', '');

        $button->setGetValueNameFilter(function () use ($log) {
            return VistasGafaFit::view('admin.gafafit.system-log.button', [
                'id' => $log->id,
            ])->render();
        });

        return [
            new LibValoresCatalogo($this, 'Admin', 'name'),
            new LibValoresCatalogo($this, 'Admin ID', 'admin_id'),
            new LibValoresCatalogo($this, 'Controller', 'controller'),
            new LibValoresCatalogo($this, 'Created', 'created_at'),
            $button,
        ];
    }

    protected static function filtrarQueries(&$query)
    {
        $companies_id = LibFilters::getFilterValue('companies_id');
        $admins_id = LibFilters::getFilterValue('admins_id');
        if ($companies_id) {
            $query->where('parameters->company->id', (int)$companies_id);
        }
        if ($admins_id) {
            $query->where('admins_id', (int)$admins_id);
        }

        $admin = Admin::selectRaw('id admin_id,name');
        $query->leftJoin(DB::raw('(' . $admin->toSql() . ') ad'), 'ad.admin_id', 'system_logs.admins_id');
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->where('controller', 'like', "%{$criterio}%");
//            $query->orWhere('name', 'like', "%{$criterio}%");
        };
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.gafafit.system-log.filters');
    }
}
