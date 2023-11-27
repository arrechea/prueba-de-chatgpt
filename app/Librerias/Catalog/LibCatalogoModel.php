<?php

namespace App\Librerias\Catalog;

use App\Interfaces\Linkable;
use App\Librerias\Catalog\Tables\Api\CatalogApiCombo;
use App\Librerias\Catalog\Tables\Api\CatalogApiMembership;
use App\Librerias\Catalog\Tables\Brand\CatalogCombo;
use App\Librerias\Catalog\Tables\Brand\CatalogService;
use App\Librerias\Catalog\Tables\Brand\CatalogServiceSpecialText;
use App\Librerias\Catalog\Tables\Brand\CatalogStaffSpecialText;
use App\Librerias\Catalog\Tables\Brand\Mails\CatalogReservationCancel;
use App\Librerias\Catalog\Tables\Brand\Mails\CatalogReservationConfirm;
use App\Librerias\Catalog\Tables\Company\CatalogUserCategory;
use App\Librerias\Catalog\Tables\Company\CatalogUserProfile;
use App\Librerias\Catalog\Tables\GafaFit\CatalogAdmin;
use App\Librerias\Catalog\Tables\GafaFit\CatalogAdminProfile;
use App\Librerias\Catalog\Tables\GafaFit\CatalogBrand;
use App\Librerias\Catalog\Tables\GafaFit\CatalogCompany;
use App\Librerias\Catalog\Tables\GafaFit\CatalogLocation;
use App\Librerias\Catalog\Tables\GafaFit\CatalogRol;
use App\Librerias\Catalog\Tables\GafaFit\CatalogUser;
use App\Librerias\Catalog\Tables\Location\CatalogGympassCheckin;
use App\Librerias\Catalog\Tables\Location\CatalogMeeting;
use App\Librerias\Catalog\Tables\Location\CatalogWaitlist;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogNewUsers;
use App\Librerias\Catalog\Tables\Location\Reservations\CatalogReservations;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\HasSpecialTexts;
use App\Models\Catalogs\IsMetable;
use App\Models\GafaFitModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

abstract class LibCatalogoModel extends GafaFitModel implements Linkable
{
    protected $connection = 'mysql';

    protected $json = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (is_array($this->json)) {
            $casts = [];
            foreach ($this->json as $column) {
                $casts[ $column ] = 'array';
            }
            $this->casts = $casts;
        }
    }

    /**
     * Para que funcione correctamente en el sistema debe de estar dado de alta aqui
     *
     * @var array
     */
    public static $tables = [
        CatalogCompany::class,
        CatalogUser::class,
        CatalogUserCategory::class,
        CatalogAdmin::class,
        CatalogBrand::class,
        CatalogLocation::class,
        CatalogCombo::class,
        CatalogRol::class,
        CatalogAdminProfile::class,
        CatalogUserProfile::class,
        CatalogService::class,
        \App\Librerias\Catalog\Tables\Brand\CatalogLocation::class,
        CatalogMeeting::class,
        CatalogServiceSpecialText::class,
        CatalogApiCombo::class,
        CatalogApiMembership::class,
        CatalogReservations::class,
        CatalogStaffSpecialText::class,
        CatalogReservationCancel::class,
        CatalogReservationConfirm::class,
        CatalogNewUsers::class,
        \App\Librerias\Catalog\Tables\Company\CatalogRol::class,
        CatalogWaitlist::class,
        CatalogGympassCheckin::class,
    ];

    /**
     * @return string
     */
    abstract public function GetName();

    /**
     * Devuelve el nombre de la tabla
     *
     * @return string
     */
    final public function GetTable()
    {
        return $this->table;
    }

    /**
     * Esto nos va a servir para poder impedir errores de DB
     *
     * @return string
     */
    static protected function QueryToOrderBy()
    {
        return 'id';
    }

    /**
     * Link para el datatable
     *
     * @return string
     */
    static public function DataTableLink(): string
    {
        return '';
    }

    /**
     * Como va a ser el orden
     *
     * @return string
     */
    static protected function QueryToOrderByOrder()
    {
        return 'desc';
    }


    /**
     * Define la columna en la que haremos la búsqueda
     *
     * @return string
     */
    static protected function ColumnToSearch()
    {
        return 'name';
    }

    /**
     * Devuelve los valores a procesar
     * Esto nos va a servir para declarar todas las valicaciones, etiquetas y opciones de salvado
     *
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]
     */
    public function Valores(Request $request = null)
    {
        return [
            new LibValoresCatalogo($this, 'Nombre', 'name', [
                'validator' => 'required|string|max:100',
            ]),
        ];
    }

    /**
     * Devuelve todas las clases de las tablas
     *
     * @param $clases
     *
     * @return LibCatalogoModel[]
     */
    final static public function GetAllTables($clases)
    {
        $tablas = array_map(function ($clase) {
            return new $clase;
        }, $clases);
        //Ordenar alfabeticamente
        usort($tablas, static::class . '::OrdenarTablas');

        return $tablas;
    }


    /**
     * @param $a LibCatalogoModel
     * @param $b LibCatalogoModel
     *
     * @return mixed
     */
    static public function OrdenarTablas($a, $b)
    {
        return strcmp($a->GetName(), $b->GetName());
    }

    /**
     * Busca el modelo por el nombre de la tabla
     *
     * @param $tableName
     *
     * @return LibCatalogoModel|null
     */
    final static public function findModelByTableName($tableName)
    {
        if (class_exists($tableName)) {
            return $tableName;
        }
        $clases = static::$tables;
        foreach ($clases as $class) {
            $model = new $class;

            if ($model->table === $tableName) {
                return $class;
            }
        }

        return null;
    }

    /**
     * Devuelve la colleccion de la db
     *
     * @param          $tableName
     *
     * @param null|int $paginate
     *
     * @param null|int $page
     *
     * @return Collection|null
     */
    final static public function GetAllRespuestas($tableName, $paginate = null, $page = null)
    {
        $criterio = null;

        if (request()->has('criterio')) {
            //Busqueda
            $criterio = request()->get('criterio');

            return self::GetSearchRespuestas($tableName, $criterio);
        }

        $model = static::findModelByTableName($tableName);
        if (!$model) return null;

        //Indice
        $query = $model::whereNotNull('id');

        if (
            \request()->has('only_actives')
            &&
            (\request()->get('only_actives', null) === 'true')
        ) {
            $query->where('status', 'active');
        }

        //Filtramos la query
        static::filtrarQueries($query);
        static::GenerarlFilters($model, $query);
        static::filtrarDataTable($model, $query);

        return $query->paginate($paginate, ['*'], 'page', $page)->appends(Input::except('page'));
    }

    /**
     * Devuelve las respuestas segun una busqueda
     *
     * @param          $tableName
     *
     * @param          $criterio
     * @param null|int $paginate
     *
     * @param null|int $page
     *
     * @return null
     */
    final static public function GetSearchRespuestas($tableName, $criterio, $paginate = null, $page = null)
    {
        $model = static::findModelByTableName($tableName);
        if (!$model) {
            return null;
        }
        $columnasABuscar = $model::ColumnToSearch();

        $query = $model::whereNotNull('id');

        if (!is_string($columnasABuscar)) {
            $columnasABuscar($query, $criterio);
        } else {
            $query->where($columnasABuscar, 'like', "%{$criterio}%");
        }

        //Filtramos la query
        static::filtrarQueries($query);
        static::GenerarlFilters($model, $query);
        static::filtrarDataTable($model, $query);

        return $query->paginate($paginate, ['*'], 'page', $page)->appends(Input::except('page'));
    }

    /**
     * @param $query
     */
    final static private function GenerarlFilters($model, &$query)
    {
        $modelStatus = LibFilters::getFilterValue('model_status');
//        $modelDeleted = LibFilters::getFilterValue('model_deleted');
        if ($modelStatus) {
            if ($modelStatus !== 'all') {
                $query->where('status', $modelStatus);
            }
        }
    }

    /**
     * Filtros Datatable
     *
     * @param $query
     */
    final static private function filtrarDataTable($model, &$query)
    {
        $request = request();

        /*********************************
         *  ORDER
         *********************************/
        if ($request->has('order')) {
            /**
             * @var LibCatalogoModel $instace
             */
            $instace = new $model();
            $valores = $instace->Valores();
            $order = $request->get('order');
            $orderBy = isset($order[0]['column']) ? (int)($order[0]['column']) : 0;
            $order = isset($order[0]['dir']) ? $order[0]['dir'] : 'asc';

            if (isset($valores[ $orderBy - 1 ])) {
                $valor = $orderBy === 0 ? "id" : $valores[ ($orderBy - 1) ]->getColumna();

                $query->orderBy($valor, $order);
            } else {
                //Order with 0 o no isset
                $orderBy = $model::QueryToOrderBy();
                //Indice
                $query->orderby($orderBy, $order);
            }
        } else {
            //Order WITHOUT DATATABLE
            $orderBy = $model::QueryToOrderBy();
            $order = $model::QueryToOrderByOrder();
            //Indice
            $query->orderby($orderBy, $order);
        }
    }

    /**
     * Funcion para filtrar queries del sistema
     *
     * @param      $query
     */
    static protected function filtrarQueries(&$query)
    {
        //Sirve para filtrar queries
    }

    /**
     * Devuelve los parametros para las validaciones
     *
     * @param Request $request
     *
     * @return Validator
     */
    final public function Validaciones(Request $request)
    {
        /**
         * @var array $inputs Tiene en si como indices las referencias y como valores los valores a comprobar
         */
        $inputs = [];
        /**
         * @var array $validaciones Tiene en si como indices las referencias y como valor la comprobacion
         */
        $validaciones = [];

        $valores = $this->Valores();

        if (count($valores)) {
            foreach ($valores as $valor) {
                /*
                 * Completamos los inputs
                 * Buscamos valor en request
                 */
                $columna = $valor->getColumna();
                $valorRequest = $request->has($columna) ? $request->input($columna) : null;

                $inputs[ $columna ] = $valorRequest;
                $validaciones[ $columna ] = $valor->getValidator();
            }
        }
        $validator = Validator::make($inputs, $validaciones);
        $validator->after(function ($validator) {
            $this->extenderValidacion($validator);
        });

        return $validator;
    }

    /**
     * Funcion para extender las validaciones en el momento de salvar
     *
     * @param $validator
     */
    protected function extenderValidacion(&$validator)
    {
        //
    }

    /**
     * Es la funcion de almacenamiento
     *
     * @param Request $request
     */
    final public function Salvar(Request $request)
    {
        $valoresCacheados = [];
        $valores = $this->Valores($request);
        if (count($valores)) {
            foreach ($valores as $valor) {
                $columna = $valor->getColumna();
                //Corremos salvados extra, Ej: relaciones especiales, etc...
                $valor->runExtraSave();

                if (empty($columna)) {
                    //Si no hay una columna con datos continuamos
                    continue;
                }

                if ($valor->isDotColumn()) {
                    $col_array = explode('.', $columna);
                    $main_column = array_pull($col_array, 0);
                    $request_array = $request->input($main_column, []);
                    if (in_array($main_column, $this->json)) {
                        $value_array = $this->$main_column;
                        $dot_key = implode('.', $col_array);
                        $request_value = array_get($request_array, $dot_key);
                        array_set($value_array, $dot_key, $request_value);
                        $this->$main_column = $value_array;
                    }

                } else {
                    if ($valor->getType() === 'file') {
                        if ($request->hasFile($columna)) {
                            //Imagenes las guardamos en cache para luego
                            $valoresCacheados[] = $valor;
                        } else if ($request->has('_delete-' . $columna) && $request->input('_delete-' . $columna) === 'on') {
                            $this->$columna = null;
                        }
                    } else if ($valor->getType() === 'password') {
                        if ($request->has($columna) && $request->input($columna, '') !== '' && $request->input($columna, '') !== null) {
                            //Solo actualizamos si tiene un valor
                            $this->$columna = $request->has($columna) ? $request->input($columna) : '';
                        }
                    } else if ($valor->isForzeNullable()) {
                        $this->$columna = $request->has($columna) ? $request->input($columna) : null;
                    } else {
                        //Cualquier otro valor
                        if ($request->has($columna)) {
                            $this->$columna = $request->has($columna) ? $request->input($columna) : null;
                        }
                    }
                }
            }
            $this->save();
            /*
             * Postprocesados Ej:Elementos que requieren del ID
             */
            if (count($valoresCacheados)) {
                foreach ($valoresCacheados as $valor) {
                    $columna = $valor->getColumna();
                    if ($valor->getType() === 'file' && $request->hasFile($columna)) {
                        //Si es archivo vamos a guardar la imagen
                        $file = $request->file($columna);

                        $this->$columna = $this->UploadImage($file, $columna);
                    }
                }
                $this->save();
            }
        }
        $this->runLastSave();

        if ($this instanceof HasSpecialTexts) {
            $this->saveSpecialTexts($request);
        }
    }

    /**
     * @param Request $request
     */
    private function saveSpecialTexts(Request $request)
    {
        LibCatalogsTable::processSaveInModel(
            $this,
            null,
            $request->get('custom_fields', []),
            $request->file('custom_fields', []),
            $this->companies_id
        );
    }

    /**
     * Ultima funcion de salvado
     */
    public function runLastSave()
    {

    }

    final public function GetDataTableFilters()
    {
        $generalHtml = VistasGafaFit::view('admin.catalog.table-generalFilters', [
            'hasStatus'  => Schema::hasColumn($this->table, 'status') && $this->GetAllowStatusSelector(),
            'searchable' => $this->GetSearchable(),
//            'hasDeleted' => Schema::hasColumn($this->table, 'deleted_at'),
        ]);
        $specificHtml = $this->GetHtmlExtraEnHeaderIndex();

        return "$generalHtml $specificHtml";
    }

    final public function GetDataTableFiltersDashboard()
    {
        $generalHtml = VistasGafaFit::view('admin.catalog.table-generalFilters-dashboard', [
            'hasStatus'  => Schema::hasColumn($this->table, 'status') && $this->GetAllowStatusSelector(),
            'searchable' => $this->GetSearchable(),
//            'hasDeleted' => Schema::hasColumn($this->table, 'deleted_at'),
        ]);
        $specificHtml = $this->GetHtmlExtraEnHeaderIndex();

        return "$generalHtml $specificHtml";
    }

    /**
     * Meter una vista en el espacio de cabecera
     *
     * @return string
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return '';
    }

    /**
     * Trae el id del elemento que va a funcionar como filtro.
     * Si no tiene uno, se van a crear los filtros basados en la
     * función GetHtmlExtraEnHeaderIndex
     *
     * @return string
     */
    public function GetOtherFilters()
    {
        return '';
    }

    /**
     * Trae la vista que va a anexarse en el footer.
     *
     * @return string
     */
    public function GetFooterHtml()
    {
        return '';
    }

    /**
     * @param string $column
     *
     * @return int|string
     */
    public function GetKeyByVisibleColumnName(string $column)
    {
        $values = array_values(array_where($this->Valores(), function ($value, $key) {
            return $value->isHiddenInList() === false;
        }));

        foreach ($values as $k => $val) {
            if ($val->getColumna() === $column && !$val->isHiddenInList() && !$val->isNotOrdenable()) {
                return $k + 1;
            }
        }

        return 0;
    }

    /**
     * @return array
     */
    public function GetDefaultOrderArrayForDataTables()
    {
        $key = $this->GetKeyByVisibleColumnName($this->QueryToOrderBy());
        $order = $this->QueryToOrderByOrder();

        return [[$key, $order]];
    }

    /**
     * Regresa un booleano que indica si la tabla debe tener búsqueda
     *
     * @return bool
     */
    public function GetSearchable()
    {
        return true;
    }

    /**
     * Indica si se debe de poner un selector de estatus en caso de que el modelo tenga
     * una columna 'status'
     *
     * @return bool
     */
    public function GetAllowStatusSelector()
    {
        return true;
    }
}
