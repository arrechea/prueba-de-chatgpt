<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 6/4/2019
 * Time: 12:42
 */

namespace App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics;

use App\Librerias\Metrics\ExportMetrics\LibExportMetrics;
use App\Models\Combos\Combos;
use App\Models\Company\Company;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class activeUsersComboSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    private $company;
    private $combo;
    private $now;
    private $start;
    private $end;

    public function __construct(Company $company, Combos $combo, Carbon $now, $start, $end)
    {
        $this->company = $company;
        $this->combo = $combo;
        $this->now  = $now;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return \Illuminate\Database\Query\Builder|mixed
     */
    public function collection()
    {
        set_time_limit(0);
        $queryCombo = LibExportMetrics::activeUsers($this->company, $this->combo, $this->start, $this->end);

        return $queryCombo;
    }

    public function headings(): array
    {
        $availableForSaleText = 'Disponible a la venta';
        if ($this->combo->deleted_at !== null || $this->combo->status !== "active") {
            $availableForSaleText = 'NO disponible a la venta';
        }
        $startFormatted = Carbon::createFromFormat('Y-m-d', $this->start)->format('d \d\e M \d\e Y');
        $endFormatted = Carbon::createFromFormat('Y-m-d', $this->end)->format('d \d\e M \d\e Y');
        return [
            [$startFormatted, $endFormatted, $availableForSaleText],
            [
                'ID',
                'Nombre(s)',
                'Apellido(s)',
                'Correo',
                'Género',
                'Fecha de nac',
                'Edad',
                'Fecha de registro',
                'Hora de registro',
                'Precio regular',
                'Precio final',
                'Método de pago',
                'Fecha de compra',
                'Fecha de expiración'
            ]
        ];
    }


    /**
     * @return string
     */
    public function title(): string
    {
        $string= $this->combo->name;
        $string = (strlen($string) > 23) ? substr($string, 0, 20).'...' : $string;
        $string = $string . "(ID" . $this->combo->id . ")";
        $string = preg_replace("/[^a-zA-Z0-9()\s]/", "", $string);
        return $string;
    }
}
