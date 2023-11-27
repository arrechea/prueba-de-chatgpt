<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 6/4/2019
 * Time: 10:49
 */

namespace App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics;

use App\Models\Combos\Combos;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class activeUsersCombos implements WithMultipleSheets
{
    use Exportable;

    protected $now;
    protected $company;
    protected $start;
    protected $end;

    public function __construct($now, $company, $start, $end)
    {
        $this->now = $now;
        $this->company = $company;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        //sacar compañia y marca.

        $combos = Combos::where([['companies_id', $this->company->id]])->withTrashed()->get();

        foreach ($combos as $combo) {
            $sheets[] = new activeUsersComboSheet($this->company, $combo, $this->now, $this->start, $this->end);
        }

        return $sheets;
    }
}
