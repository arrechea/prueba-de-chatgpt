<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 6/12/2019
 * Time: 15:25
 */

namespace App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics;


use Carbon\Carbon;
use App\Models\Company\Company;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Librerias\Metrics\ExportMetrics\LibExportMetrics;

class UsersbyMonth implements FromCollection, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private $company;
    private $start;
    private $end;

    public function __construct(Company $company, $start, $end)
    {
        $this->company = $company;
        $this->start = $start;
        $this->end = $end;
    }


    public function collection()
    {
        set_time_limit(0);
        $users =  LibExportMetrics::allUsersByMonth($this->company, $this->start, $this->end);
        return $users;
    }


    public function headings(): array
    {
        $startFormatted = Carbon::createFromFormat('Y-m-d', $this->start)->format('d \d\e M \d\e Y');
        $endFormatted = Carbon::createFromFormat('Y-m-d', $this->end)->format('d \d\e M \d\e Y');
        return [
            [$startFormatted, $endFormatted],
            [
                'IDCliente',
                'Nombre(s)',
                'Apellido(s)',
                'Correo',
                'Teléfono',
                'Celular',
                'Género',
                'Fecha de nacimiento',
                'Edad',
                'Fecha de registro',
                'Hora de registro',
            ]
        ];
    }
} { }
