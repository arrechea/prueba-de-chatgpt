<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 5/15/2019
 * Time: 13:48
 */

namespace App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics;


use App\Librerias\Metrics\ExportMetrics\LibExportMetrics;
use App\Models\Company\Company;
use App\Models\User\UserProfile;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
class AllUsersController implements FromCollection, WithHeadings, ShouldAutoSize
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
        $users =  LibExportMetrics::allUsers($this->company, $this->start, $this->end);
       return $users;
    }


    public function headings(): array
    {
        $startFormatted = Carbon::createFromFormat('Y-m-d', $this->start)->format('d \d\e M \d\e Y');
        $endFormatted = Carbon::createFromFormat('Y-m-d', $this->end)->format('d \d\e M \d\e Y');
        return [
            [$startFormatted, $endFormatted],
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
                'Paquetes',
                'Membresías'
            ]
        ];
    }

}
