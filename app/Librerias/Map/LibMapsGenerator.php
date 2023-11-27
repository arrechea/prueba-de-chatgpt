<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 28/08/18
 * Time: 11:20
 */

namespace App\Librerias\Map;


use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Maps\Maps;
use App\Models\Maps\MapsObject;
use App\Models\Maps\MapsPosition;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

abstract class LibMapsGenerator
{
    /**
     * @param Location $location
     */
    static public function getPositionsMapsByLocation(Location $location)
    {
        return MapsPosition::where('status', 'active')
            ->where(function ($query) use ($location) {
                //location Credits
                $query->where('locations_id', $location->id);
                //brand credits
                $query->orWhere(function ($query) use ($location) {
                    $query->whereNull('locations_id');
                    $query->where('brands_id', $location->brands_id);
                });
                //company credits
                $query->orWhere(function ($query) use ($location) {
                    $query->whereNull('locations_id');
                    $query->whereNull('brands_id');
                    $query->where('companies_id', $location->companies_id);
                });
                //gafafit credits
                $query->orWhere(function ($query) use ($location) {
                    $query->whereNull('locations_id');
                    $query->whereNull('brands_id');
                    $query->whereNull('companies_id');
                });
            })
            ->get();
    }

    /**
     * @param Maps $maps
     *
     * @return Collection
     */
    static public function parseInitialMap(Maps $maps): Collection
    {
        $maps->load([
            'objects',
            'objects.positions',
        ]);

        $parsedMap = [];
        $columns = $maps->columns;
        $rows = $maps->rows;

        //Generate Map
        for ($i = 1; $rows >= $i; $i++) {
            $parsedMap[] = array_fill(0, $columns, null);
        }

        //Fill with objects
        if ($maps->objects->count() > 0) {
            $maps->objects->each(function ($object) use (&$parsedMap) {
                $row = $object->position_row;
                $column = $object->position_column;

                $parsedMap[ $row ][ $column ] = $object->positions;
            });
        }

        return new Collection($parsedMap);
    }

    /**
     * @param Brand $brand
     */
    static public function getPositionsMapsByBrand(Brand $brand)
    {
        dd('Sin hacer');
    }

    /**
     * @param Company $company
     */
    static public function getPositionsMapsByCompany(Company $company)
    {
        dd('Sin hacer');
    }

    /**
     *
     */
    static public function getPositionsMapsGafaFitLevel()
    {
        dd('Sin hacer');
    }

    /**
     * @param string            $name
     * @param Collection        $rows
     * @param Location          $location
     * @param bool              $active
     * @param UploadedFile|null $background
     *
     * @param null              $backgroundString
     * @param Maps|null         $map
     *
     * @return Maps
     */
    static public function generateMap(string $name, Collection $rows, Location $location, bool $active = false, UploadedFile $background = null, $backgroundString = null, Maps $map = null): Maps
    {
        //Prepare Map
        $newMap = $map ?? new Maps();
        $newMap->name = $name;
        $newMap->status = $active ? 'active' : 'inactive';
        $newMap->locations_id = $location->id;
        $newMap->brands_id = $location->brands_id;
        $newMap->companies_id = $location->companies_id;
        $newMap->capacity = 0;
        $newMap->save();

        //Save Image
        if ($background) {
            $backgroundUrl = $newMap->UploadImage($background);
            $newMap->image_background = $backgroundUrl;
            $newMap->save();
        } else if ($backgroundString) {
            $newMap->image_background = $backgroundString;
            $newMap->save();
        }

        //Include positions
        if ($rows->count() > 0) {
            $numberOfRows = $rows->count();
            $numberOfColumns = 0;

            $capacity = 0;
            $positionNumber = 1;
            $cachedPositions = [];

            //Loop to rows
            $rows->each(function ($cells, $rowIndex) use ($newMap, &$numberOfColumns, &$cachedPositions, &$positionNumber, &$capacity) {
                $cells = new Collection($cells);
                $numberOfColumns = $cells->count() > $numberOfColumns ? $cells->count() : $numberOfColumns;

                $cells->each(function ($cell, $columnIndex) use ($rowIndex, $newMap, &$cachedPositions, &$positionNumber, &$capacity,$cells) {
                    if($columnIndex<0){
                        dd($columnIndex,$cell,$cells);
                    }
                    if ($cell) {
                        $positionId = $cell['id'] ?? null;
                        if ($positionId !== null) {
                            if (isset($cachedPositions[ $positionId ])) {
                                $position = $cachedPositions[ $positionId ];
                            } else {
                                $position = MapsPosition::find($positionId);
                            }
                            if ($position) {
                                //Solo almacenamos lugares con datos

                                $mapObject = new MapsObject();
                                $mapObject->maps_id = $newMap->id;
                                $mapObject->maps_positions_id = $position->id;
                                $mapObject->position_column = $columnIndex;
                                $mapObject->position_row = $rowIndex;
                                if ($position->isPublic()) {
                                    $mapObject->position_number = $positionNumber;
                                    $mapObject->position_text = $cell['text'] ?? $positionNumber;
                                    $positionNumber++;
                                    $capacity++;
                                }
                                $mapObject->width = $position->width;
                                $mapObject->height = $position->height;
                                $mapObject->locations_id = $newMap->locations_id;
                                $mapObject->brands_id = $newMap->brands_id;
                                $mapObject->companies_id = $newMap->companies_id;
                                $mapObject->save();

                            }
                        }
                    }
                });
            });
            $newMap->rows = $numberOfRows;
            $newMap->columns = $numberOfColumns;
            $newMap->capacity = $capacity;
            $newMap->save();
        }

        return $newMap;
    }

    /**
     * @param Maps $maps
     *
     * @return array
     */
    static public function getInitialMap(Maps $maps): array
    {
        $map = [];
        if ($maps->reservations()->count() === 0) {
            $row = array_fill(0, $maps->columns, null);
            for ($i = 0; $i < $maps->rows; $i++) {
                $map[] = $row;
            }
            $objects = $maps->objects;
            foreach ($objects as $object) {
                $position = $object->positions;
                $position->text = $object->position_text;
                $map[ $object->position_row ][ $object->position_column ] = $position;
            }
        }

        return $map;
    }
}
