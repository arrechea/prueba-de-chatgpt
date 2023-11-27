<?php
/**
 * Created by IntelliJ IDEA.
 * User: carol
 * Date: 6/6/2019
 * Time: 11:25
 */

namespace App\Http\Controllers\Admin\Brand\Metrics\ExportMetrics;

use App\Models\Membership\Membership;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class activeUsersMemberships implements WithMultipleSheets
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

        $memberships = Membership::where([['companies_id', $this->company->id]])->withTrashed()->get();

        foreach ($memberships as $membership) {
            $sheets[] = new activeUsersMembershipSheet($this->company, $membership, $this->now, $this->start, $this->end);
        }

        return $sheets;
    }
}
