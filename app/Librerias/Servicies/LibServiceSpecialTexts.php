<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 24/05/2018
 * Time: 12:31 PM
 */

namespace App\Librerias\Servicies;


use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Service;
use App\Models\Service\ServiceSpecialText;

class LibServiceSpecialTexts
{
    /**
     * Establece el orden de los textos especiales. Se le pasa algún
     * texto, se recorren todos los textos con orden mayor o igual
     * al texto pasado y luego reestablece el orden comenzando desde
     * el '0'.
     *
     * @param ServiceSpecialText $text
     */
    public static function setOrderInTable(ServiceSpecialText $text)
    {
        $prev = $text->otherTexts;
        $prev->map(function ($item, $key) use ($text) {
            if ($item->id !== $text->id && $item->order >= $text->order) {
                $item->order++;
            }

            return $item;
        });
        $sorted = $prev->sortBy('order');

        $i = 0;
        foreach ($sorted as $v) {
            $v->order = $i;
            $v->save();
            $i++;
        }
    }


    public static function addUrlAttributes(ServiceSpecialText &$new, Service $service, Company $company, Brand $brand)
    {
        $new->edit_url = route('admin.company.brand.services.texts.save', [
            'company' => $company,
            'brand'   => $brand,
            'service' => $service,
            'text'    => $new,
        ]);
        $new->delete_url = route('admin.company.brand.services.texts.delete', [
            'company' => $company,
            'brand'   => $brand,
            'service' => $service,
            'text'    => $new,
        ]);
        $new->modal_url = route('admin.company.brand.services.texts.edit', [
            'company' => $company,
            'brand'   => $brand,
            'service' => $service,
            'text'    => $new,
        ]);
        $new->name = $new->title;

        return $new;
    }
}
