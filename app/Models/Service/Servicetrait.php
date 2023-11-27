<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 18/05/18
 * Time: 11:24
 */

namespace App\Models\Service;


use App\Models\Location\Location;
use App\Models\Service;
use App\Models\Brand\Brand;
use App\Models\Credit\Credit;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use App\Models\Credit\CreditsServices;
use App\Models\Catalogs\CatalogsFieldsValues;

trait Servicetrait
{
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status === "active";
    }

    /**
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function childServices()
    {
        return $this->hasMany(Service::class, 'parent_id', 'id');
    }

    /**
     * @return mixed
     */
    public function childServicesRecursive()
    {
        return $this->childServices()->whereNull('deleted_at')->with('childServicesRecursive');
    }

    /**
     * @return mixed
     */
    public function parentService()
    {
        return $this->belongsTo(Service::class, 'parent_id', 'id')->withTrashed();
    }

    /**
     * @return mixed
     */
    public function parentServiceRecursive()
    {
        return $this->parentService()->with('parentServiceRecursive');
    }

    /**
     * @return mixed
     */
    public function specialTexts()
    {
        return $this->hasMany(ServiceSpecialText::class, 'services_id', 'id')->orderBy('order');
    }

    /**
     * @return Collection
     */
    public function getAllServicesChildrensIds()
    {
        /**
         * @var Service $this
         */
        return self::getAllServicesChildrensIdsByService($this);
    }

    /**
     * @param Service         $service
     * @param Collection|null $respuesta
     *
     * @return Collection
     */
    static public function getAllServicesChildrensIdsByService(Service $service, Collection &$respuesta = null)
    {
        if (!$respuesta) {
            $respuesta = new Collection();
        }

        $respuesta->prepend($service->id);
        $childrens = $service->childServicesRecursive;
        if ($childrens->count() > 0) {
            $childrens->each(function ($children) use (&$respuesta) {
                $respuesta->merge(self::getAllServicesChildrensIdsByService($children, $respuesta));
            });
        }

        return $respuesta;
    }

    /**
     * Get $this->id and all ids that can be buyed with same credit
     */
    public function getAllServicesIdsNested()
    {
        /**
         * @var Service $this
         */
        return self::getAllServicesIdsNestedByService($this);
    }

    /**
     * @param Service         $service
     *
     * @param Collection|null $respuesta
     *
     * @return Collection
     */
    static public function getAllServicesIdsNestedByService(Service $service, Collection &$respuesta = null)
    {
        if (!$respuesta) {
            $respuesta = new Collection();
        }

        $respuesta->prepend($service->id);
        $parent = $service->parentServiceRecursive;
        if ($parent) {
            $respuesta->merge(self::getAllServicesIdsNestedByService($parent, $respuesta));
        }

        return $respuesta;
    }

    /**
     * @return mixed
     */
    public function credits()
    {
        return $this->belongsToMany(Credit::class, 'credits_services', 'services_id', 'credits_id')->withPivot('credits');
    }

    /**
     * Creditos necesarios
     */
    public function neccesaryCredits()
    {
        $servicesNested = self::getAllServicesIdsNested();

        return Credit::select(
            '*'
        )
            ->where('status', 'active')
            ->whereHas('services', function ($query) use ($servicesNested) {
                $query->whereIn('services.id', $servicesNested);
            })
            ->with([
                'services' => function ($query) use ($servicesNested) {
                    $query->whereIn('services.id', $servicesNested);
                },
            ])
            ->get();
    }

    /**
     * @param Credit $credit
     *
     * @return int
     */
    public function neccesaryCreditsByCreditType(Credit $credit): int
    {
        $servicesNested = self::getAllServicesIdsNested();

        $creditsServices = CreditsServices::select([
            'credits',
        ])
            ->where('credits_id', $credit->id)
            ->whereIn('services_id', $servicesNested)
            ->orderBy('credits', 'asc')
            ->first();

        return $creditsServices->credits;
    }

    public function fields_values()
    {
        return $this->hasMany(CatalogsFieldsValues::class, 'model_id')->whereHas('group.catalog', function ($q) {
            $q->where('table', 'services');
        });
    }

    /**
     * @param Location|null $location
     *
     * @return bool
     */
    public function isGympassActive(Location $location = null): bool
    {
        $active = $this->company->isGympassActive() &&
            $this->getDotValue('extra_fields.gympass.active') == 1;

        return !!$location ?
            ($active && $this->getDotValue("extra_fields.gympass.info.{$location->id}.active") == 1) :
            $active;
    }

    /**
     * @param Location $location
     *
     * @return bool
     */
    public function isGympassBookable(Location $location): bool
    {
        return $this->isActive() &&
            $this->isGympassActive($location) &&
            $this->getDotValue("extra_fields.gympass.info.{$location->id}.bookable") == 1;
    }

    /**
     * @param Location $location
     *
     * @return bool
     */
    public function isGympassVisible(Location $location): bool
    {
        return $this->isActive() &&
            $this->isGympassActive($location) &&
            $this->getDotValue("extra_fields.gympass.info.{$location->id}.visible") == 1;
    }

    /**
     * @param Location $location
     *
     * @return int
     */
    public function getGympassClassId(Location $location): int
    {
        $location_id = $location->id;

        return (int)$this->getDotValue("extra_fields.gympass.info.$location_id.class_id");
    }

    /**
     * @param Location $location
     * @param int      $class_id
     * @param bool     $save
     *
     * @return void
     */
    public function setGympassClassId(Location $location, int $class_id, bool $save = false)
    {
        $location_id = $location->id;

        $this->setDotValue("extra_fields.gympass.info.$location_id.class_id", $class_id, $save);
    }

    /**
     * @param Location $location
     * @param string   $slug
     *
     * @return array|\ArrayAccess|mixed|null
     */
    public function getGympassInfo(Location $location, string $slug)
    {
        $location_id = $location->id;

        return $this->getDotValue("extra_fields.gympass.info.$location_id.$slug");
    }

    /**
     * @param Location $location
     * @param string   $slug
     * @param          $value
     * @param bool     $save
     *
     * @return void
     */
    public function setGympassInfo(Location $location, string $slug, $value, bool $save = false)
    {
        $location_id = $location->id;

        $this->setDotValue("extra_fields.gympass.info.$location_id.$slug", $value, $save);
    }

    /**
     * @param Location $location
     *
     * @return int
     */
    public function getGympassProductId(Location $location): int
    {
        return (int)$this->getGympassInfo($location, 'product_id');
    }
}
