<?php

namespace App\Librerias\Chart;

use Illuminate\Support\Collection;

abstract class Graficas
{
    /**
     * @param string $ajaxUrl
     * @param array  $opciones
     *
     * @return array
     */
    public static function get_grafica(string $ajaxUrl, $opciones = array())
    {
        $opciones_default = array(
            'id'            => 'analitica_defecto',
            'titulo'        => 'Gráfica',
            'ajaxUrl'       => $ajaxUrl,
            'alto'          => '600',
            'colums'        => '',
            'filters'       => '',
            'rows'          => '',
            'tipo'          => 'PieChart',
            'visualization' => 'visualization',
            'clase'         => '',
            'options'       => '',
            'extra_charts'  => '["LineChart","PieChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Histogram","Table"]',
            'other_filters' => '',
        );
        $opciones_finales = array_intersect_key($opciones + $opciones_default, $opciones_default);
        Graficas::formatColumnsAndRows($opciones_finales);

        return $opciones_finales;
    }

    /**
     * @param ColumnCollection $columns
     * @param RowCollection    $rows
     *
     * @return array
     */
    public static function parseAjaxInfo(ColumnCollection $columns, RowCollection $rows): array
    {
        return [
            'cols' => $columns->getColumns(),
            'rows' => $rows->getRows(),
        ];
    }

    /**
     * Formatea las columnas y las filas
     *
     * @param bool $datos
     */
    private static function formatColumnsAndRows(&$datos = false)
    {
        if (!$datos) {
            $datos['colums'] = '';
            $datos['rows'] = '';
        };

        if (is_array($datos['colums'])) {
            $datos['colums'] = Graficas::formatColumns($datos['colums']);
        } else {
            $datos['colums'] = '';
        }
        if (is_array($datos['rows']) && count($datos['rows'])) {
            $datos['rows'] = Graficas::formatRows($datos['rows']);
        } else {
            $datos['rows'] = '';
        }
    }

    /**
     * Formatea las columnas
     * $columnas = array('formato','Nombre de la columna');
     *
     * @param array $columnas
     *
     * @return string
     */
    private static function formatColumns(array $columnas = array())
    {
        $format_string = '';
        foreach ($columnas as $columna) {
            if (is_array($columna)) {
                $format_string .= 'data.addColumn(\'' . $columna[0] . '\', \'' . $columna[1] . '\');';
            } else {
                $format_string .= 'data.addColumn(' . $columna . ');';
            }
        };

        return $format_string;
    }

    /**
     * Formatea las filas
     * $filas = array('valor_col1','valor_col2',...);
     *
     * @param array $filas
     *
     * @return string
     */
    private static function formatRows(array $filas = array())
    {
        $json_filas = json_encode($filas);
        $format_string = 'data.addRows(' . $json_filas . ')';

        return $format_string;
    }
}
