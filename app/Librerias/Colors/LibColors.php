<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 03/08/2018
 * Time: 16:45
 */

namespace App\Librerias\Colors;


use App\Models\Company\Company;

class LibColors
{
    public $color_black = 'black';
    public $color_main = '#323232';
    public $color_secondary = '#626262';
    public $color_secondary2 = '#929292';
    public $color_secondary3 = '#C2C2C2';
    public $color_light = '#f5f5f5';
    public $color_menutop = '#323232';
    public $color_menuleft = '#323232';
    public $color_menuleft_secondary = '#323232';
    public $color_menuleft_selected = '#E0E0E0';
    public $color_alert = '#ba000d';


    /**
     * @var Company
     */
    private static $companyColors;

    /**
     * LibColors constructor.
     *
     * @param Company|null $company
     */
    private function __construct(Company $company = null)
    {
        if ($company) {
            LibColors::$companyColors = $company->companyColor;
        }
    }

    /**
     * @param              $color
     * @param Company|null $company
     *
     * @return mixed
     */
    static public function get($color, Company $company = null)
    {
        $colors = new LibColors($company);
        if (
            LibColors::$companyColors
            &&
            isset(LibColors::$companyColors->$color)
            &&
            LibColors::$companyColors->$color !== ''
        ) {
            return LibColors::$companyColors->$color;
        }

        return $colors->$color;
    }

    /**
     * @param array $colors
     */
    static public function getColorsInArray(&$colors)
    {
        $colors = [
            'black_color'              => LibColors::get('color_black', (isset($company) ? $company : null)),
            'main_color'               => LibColors::get('color_main', (isset($company) ? $company : null))
            // 'secondary_color'          => LibColors::get('color_secondary', (isset($company) ? $company : null)),
            // 'secondary2_color'         => LibColors::get('color_secondary2', (isset($company) ? $company : null)),
            // 'secondary3_color'         => LibColors::get('color_secondary3', (isset($company) ? $company : null)),
            // 'light_color'              => LibColors::get('color_light', (isset($company) ? $company : null)),
            // 'menutop_color'            => LibColors::get('color_menutop', (isset($company) ? $company : null)),
            // 'menuleft_color'           => LibColors::get('color_menuleft', (isset($company) ? $company : null)),
            // 'menuleft_secondary_color' => LibColors::get('color_menuleft_secondary', (isset($company) ? $company : null)),
            // 'menuleft_selected_color'  => LibColors::get('color_menuleft_selected', (isset($company) ? $company : null)),
            // 'alert_color'              => LibColors::get('color_alert', (isset($company) ? $company : null)),
        ];
    }
}
