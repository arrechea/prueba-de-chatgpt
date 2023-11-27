<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 6/6/2019
 * Time: 11:25
 */

namespace App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics;


use App\Librerias\Metrics\ExportMetrics\LibExportMetrics;
use App\Models\Company\Company;
use App\Models\Membership\Membership;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class activeUsersMembershipSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{

    private $company;
    private $membership;
    private $now;
    private $start;
    private $end;

    public function __construct(Company $company, Membership $membership, Carbon $now, $start, $end)
    {
        $this->company = $company;
        $this->membership = $membership;
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
        $querymembership = LibExportMetrics::activeUsersMembership($this->company, $this->membership,  $this->start,   $this->end);

        return $querymembership;
    }

    public function headings(): array
    {
        $availableForSaleText = 'Disponible a la venta';
        if ($this->membership->deleted_at !== null || $this->membership->status !== "active") {
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
        $string = $this->membership->name;
        $string = (strlen($string) > 23) ? substr($string, 0, 20) . '...' : $string;
        $string =  $string . "(ID" . $this->membership->id . ")";
        $string = preg_replace("/[^a-zA-Z0-9()\s]/", "", $string);
        return  $string;
    }
}
