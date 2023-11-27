<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 14/05/2018
 * Time: 04:54 PM
 */

namespace App\Librerias\Calendars;

use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Brand\Brand;
use App\Models\Meeting\Meeting;
use App\Models\Room\Room;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class LibCalendar
{
    /**
     * Obtiene el listado de meetings dentro de un periodo con el servicio asociado y
     * el staff
     *
     * @param Request $request
     * @param int     $room
     * @param string  $start
     * @param string  $end
     *
     * @param bool    $populate
     * @param bool    $reducePopulation
     *
     * @return array
     * @internal param bool $populateForAPI
     *
     * @internal param bool $populateParentServices
     */
    public static function getMeetings(Request $request, int $room, string $start, string $end, $populate = true, $reducePopulation = false)
    {
        if ($room) {
            $query = Meeting::where('rooms_id', $room)
                ->whereBetween('start_date', [$start, $end])
                ->withCount('reservation')
                ->orderBy('start_date', 'asc');

            if ($populate) {
                $query->with('service', 'staff');
            }
            if ($reducePopulation) {
                $query->with([
                    'service.parentServiceRecursive' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'name',
                        ]);
                    },
                    'service'                        => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'name',
                        ]);
                    },
                    'staff'                          => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'lastname',
                            'slug',
                        ]);
                    },
                    'room'                           => function ($query) {
                        $query->select([
                            'id',
                            'name',
                        ]);
                    },
                    'location'                       => function ($query) {
                        $query->select([
                            'id',
                            'name',
                        ]);
                    },
                ]);
            }
            $meetings = $query->get();
            self::mapMeetings($meetings);

            return $meetings->values();
        }

        return [];
    }


    /**
     * Obtiene el listado de meetings dentro de un periodo con el servicio asociado y
     * el staff
     *
     * @param Request $request
     * @param int     $staff
     * @param string  $start
     * @param string  $end
     *
     * @return array
     */
    public static function getStaffMeetings(Request $request, int $staff, string $start, string $end)
    {
        if ($staff) {
            $meetings = Meeting::where('staff_id', $staff)
                ->whereBetween('start_date', [$start, $end])
                ->with('service', 'staff')
                ->withCount('reservation')
                ->orderBy('start_date', 'asc')
                ->get();
            self::mapMeetings($meetings);

            return $meetings->values();
        }

        return [];
    }

    public static function getStaffLocationMeetings(Request $request, int $location, int $staff, string $start, string $end)
    {
        if ($staff) {
            $meetings = Meeting::where('staff_id', $staff)
                ->where('locations_id', $location)
                ->whereBetween('start_date', [$start, $end])
                ->with('service', 'staff')
                ->withCount('reservation')
                ->orderBy('start_date', 'asc')
                ->get();


            self::mapMeetings($meetings);

            return $meetings->values();
        }

        return [];

    }


    /**
     * @param Request $request
     * @param int     $location
     * @param string  $start
     * @param string  $end
     *
     * @param bool    $onlyActiveRooms
     *
     * @param bool    $populate
     * @param bool    $reducePopulation
     *
     * @return array
     */
    public static function getLocationMeetings(Request $request, int $location, string $start, string $end, $onlyActiveRooms = false, $populate = true, $reducePopulation = false)
    {
        if ($location) {
            $query = Meeting::where('locations_id', $location)
                ->whereBetween('start_date', [$start, $end])
                ->withCount('reservation')
                ->orderBy('start_date', 'asc');
            if ($onlyActiveRooms) {
                $query->whereHas('room', function ($q) {
                    $q->where('status', 'active');
                    $q->whereNull('deleted_at');
                });
            }
            if ($populate && !$reducePopulation) {
                $query->with([
                    'service',
                    'staff',
                    'fields_values' => function ($q) {
                        $q->with('field');
                        $q->whereHas('field', function ($q) {
                            $q->where('hidden_in_list', false);
                        });
                    },
                ]);
            } else if ($reducePopulation) {
                $query->with([
                    'service.parentServiceRecursive' => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'name',
                        ]);
                    },
                    'service'                        => function ($query) {
                        $query->select([
                            'id',
                            'parent_id',
                            'name',
                        ]);
                    },
                    'staff'                          => function ($query) {
                        $query->select([
                            'id',
                            'name',
                            'lastname',
                            'slug',
                            'picture_web',
                            'picture_web_list',
                            'picture_web_over',
                            'picture_movil',
                            'picture_movil_list',
                        ]);
                    },
                    'fields_values'                  => function ($query) {
                        $query->select([
                            'catalogs_fields_id',
                            'value',
                            'model_id',
                        ]);
                        $query->whereHas('field', function ($q) {
                            $q->where('hidden_in_list', false);
                        });
                        $query->with([
                            'field' => function ($q) {
                                $q->select([
                                    'id',
                                    'name',
                                ]);
                            },
                        ]);
                    },
                    'room'                           => function ($query) {
                        $query->select([
                            'id',
                            'name',
                        ]);
                    },
                    'location'                       => function ($query) {
                        $query->select([
                            'id',
                            'name',
                        ]);
                    },
                ]);
            }

            $meetings = $query->get();
            self::mapMeetings($meetings);

            return array_map(function ($item) {
                unset($item['brand'], $item['brands_id']);

                return $item;
            }, $meetings->toArray());
        }

        return [];
    }

    /**
     * @param Collection $meetings
     *
     * @return static
     */
    public static function mapMeetings(Collection &$meetings)
    {
        $meetings = $meetings->map(function ($item) {
            $item->start = $item->start_date->copy()->toIso8601String();
            $item->end = $item->end_date->copy()->toIso8601String();
            $item->title = $item->staff->name;
            $item->type = $item->service->name;
            $item->available = $item->available();
            $item->passed = $item->isPast();
            $item->not_attendance_list = ($item->attendance->count() === 0) && ($item->isPast());

//            unset($item->brand, $item->brands_id);

            return $item;
        });
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     * @param int     $staff
     * @param string  $start
     * @param string  $end
     *
     * @return array
     */
    public static function getStaffBrandMeetings(Request $request, Brand $brand, int $staff, string $start, string $end)
    {
        if ($staff) {
            $meetings = Meeting::where('staff_id', $staff)
                ->where('brands_id', $brand->id)
                ->whereBetween('start_date', [$start, $end])
                ->with('service', 'staff', 'location', 'room')
                ->withCount('reservation')
                ->orderBy('start_date', 'asc')
                ->get();
            self::mapMeetings($meetings);

            return $meetings->values();
        }

        return [];
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     * @param int     $service
     * @param string  $start
     * @param string  $end
     *
     * @return array
     */
    public static function getServiceBrandMeetings(Request $request, Brand $brand, int $service, string $start, string $end)
    {
        if ($service) {
            $idsServicios = new \Illuminate\Support\Collection([$service]);

            $serviceModel = Service::find($service);
            if ($serviceModel) {
                $idsServicios = $serviceModel->getAllServicesChildrensIds();
            }


            $meetings = Meeting::whereIn('services_id', $idsServicios)
                ->where('brands_id', $brand->id)
                ->whereBetween('start_date', [$start, $end])
                ->with('service', 'staff', 'location', 'room')
                ->withCount('reservation')
                ->orderBy('start_date', 'asc')
                ->get();
            self::mapMeetings($meetings);

            return $meetings->values();
        }

        return [];
    }

    /**
     * Función para repetir todos los eventos de un periodo determinado (marcado con los atributos 'start'
     * y 'end'). Se toman estos eventos y se copian añadiéndole una semana.
     *
     * @param Request $request
     * @param Room    $room
     *
     * @return Collection
     */
    public static function repeatWeek(Request $request, Room $room)
    {
        $return_data = new Collection();

        //Aborta si no se tienen los datos necesarios
        if (!$request->has('start') || !$request->has('end')) {
            abort(400);
        }

        $start = $request->get('start');
        $end = $request->get('end');

        $start_date = (new Carbon($start))->startOfDay();
        $end_Date = (new Carbon($end))->endOfDay();

        $meetings = $room->meetings()
            ->where('start_date', '>=', $start_date)
            ->where('start_date', '<=', $end_Date)
            ->get();

        //Modifica la colección para añadir una semana a 'start_date' y 'end_date'
        $new_meetings = $meetings->map(function ($item) {
            $item->start_date = $item->start_date->addWeeks(1);
            $item->end_date = $item->end_date->addWeeks(1);
            $item->extra_fields = null;

            return $item;
        });

        foreach ($new_meetings as $meeting) {
            $new = $meeting->cloneMeeting();
            $return_data->push($new);
        }

        //regresa la colleción con los meetings
        return $return_data;
    }
}
