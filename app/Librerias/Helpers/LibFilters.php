<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 17/05/18
 * Time: 16:12
 */

namespace App\Librerias\Helpers;


use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LibFilters
{
    /**
     * @var null|LibFilters
     */
    static $Instance = null;

    /**
     * @var Request
     */
    private $request;
    /**
     * @var Collection
     */
    private $filters;

    /**
     * LibFilters constructor.
     *
     * @param Request    $request
     * @param Collection $filters
     */
    private function __construct(Request $request, Collection $filters)
    {
        $this->request = $request;
        $this->filters = $filters;
    }

    /**
     * Refresh Singleton
     */
    static public function refreshInstance()
    {
        static::$Instance = null;
    }

    /**
     * @param Request|null $request
     *
     * @return LibFilters|null
     */
    static private function Instance(Request $request = null)
    {
        if (!$request) {
            $request = \request();
        }

        $filters = (new Collection((array)($request->get('filters', []))));

        return new LibFilters($request, $filters);
//
//        if (!static::$Instance) {
//            if (!$request) {
//                $request = \request();
//            }
//            $filters = new Collection((array)($request->get('filters', [])));
//            static::$Instance = new LibFilters($request, $filters);
//        }
//
//        return static::$Instance;
    }

    /**
     * @param string       $filterName
     * @param Request|null $request
     *
     * @param null         $default
     *
     * @return mixed
     */
    static public function getFilterValue(string $filterName, Request $request = null, $default = null)
    {
        $lib = self::Instance($request);
        $filters = $lib->getFiltersWithArrays();

        return $filters[ $filterName ] ?? $default;
    }

    /**
     * @return Collection
     */
    private function getFilters(): Collection
    {
        return $this->filters;
    }

    private function getFiltersWithArrays()
    {
        $filters = $this->filters;

        return $filters->groupBy('name')->mapWithKeys(function ($item, $key) {
            $new_key = rtrim($key, '[]');
            $is_array = strpos($key, '[]') !== false;
            $value = $is_array ? array_pluck($item, 'value') : ($item->first()['value'] ?? null);

            return [
                $new_key => $value,
            ];
        });
    }
}
