<?php

namespace App\Librerias\Catalog;

use App\Librerias\Helpers\LibFilters;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Validator;

class CatalogFacade
{
    /**
     * Listado de respuestas de una pregunta
     *
     * @param Request  $request
     * @param          $tableName
     *
     * @param null|int $paginate
     *
     * @param null|int $page
     *
     * @return LibCatalogoResponse
     */
    public static function index(Request $request, $tableName, $paginate = null, $page = null): LibCatalogoResponse
    {

        //Parametros para el nombre
        $modelName = LibCatalogoModel::findModelByTableName($tableName);

        if (!$modelName) {
            abort(404);
        }
        /**
         * @var LibCatalogoModel $model
         */
        $model = new $modelName;

        $datos = $model::GetAllRespuestas($tableName, $paginate, $page);

        $respuesta = new LibCatalogoResponse();
        $respuesta->setModel($model);
        $respuesta->setRespuestas($datos);

        //respuesta
        return $respuesta;
    }

    /**
     * @param Request $request
     * @param         $tableName
     *
     * @return array
     */
    public static function dataTableIndex(Request $request, $tableName)
    {
        $busqueda = false;
        $pagination = null;
        $page = null;
        /*
         * Search
         */
        if ($request->has('search')) {
            //Con buscador
            $busqueda = $request->get('search', ['value' => '']);
            $criterio = LibFilters::getFilterValue('criterio', $request, '');

            $request->criterio = isset($busqueda['value']) ? $busqueda['value'] : $criterio;
            if ($request->criterio !== '') {
                $busqueda = true;
            }
        }
        /*
         * Pagination
         */
        if ($request->has('length')) {
            $pagination = $request->get('length', null);
        }
        /*
         * Page
         */
        if ($request->has('start')) {
            $page = $request->get('start', null);
            if ($page) {
                $page = (int)$page === 0 ? 1 : ((int)$page / (int)$pagination) + 1;
            }
        }
//        dd($page, $pagination);
        /*
         * Init
         */
        if ($busqueda === true) {
            $indice = static::search($request, $tableName, $pagination, $page);
        } else {
            //Indice
            $indice = static::index($request, $tableName, $pagination, $page);
        }

        if (!$indice) {
            abort(404);
        }
        $info = $indice->getRespuestas();

        $return = [
            "data"                 => [],
            "iTotalRecords"        => $info->perPage(),
            "iTotalDisplayRecords" => $info->total(),
            'footer'               => (new $tableName())->GetFooterHtml(),
        ];
        /*
         * Formating
         */
        foreach ($info as $respuesta) {
            $valoresRespuesta = $respuesta->Valores();
            $formated = [];
            if ($valoresRespuesta) {
                $formated[] = "#{$respuesta->id}";
                foreach ($valoresRespuesta as $valor) {
                    if (!$valor->isHiddenInList()) {
                        if ($valor->getType() === 'file') {
                            if (!empty($valor->GetValueName())) {
                                $formated[] = '<img src="' . $valor->GetValueName() . '" alt="" height="50px">';
                            } else {
                                $formated[] = '';
                            }
                        } else if ($valor->getType() === 'password') {
                            //silent
                        } else {
                            $formated[] = $valor->GetValueName();
                        }
                    }
                }
                $return['data'][] = $formated;
            }
        };

        return $return;
    }

    /**
     * Hola
     *
     * @param Request  $request
     * @param          $tableName
     *
     * @param null|int $paginate
     *
     * @param null|int $page
     *
     * @return LibCatalogoResponse
     */
    public static function search(Request $request, $tableName, $paginate = null, $page = null): LibCatalogoResponse
    {
        //Parametros para el nombre
        $modelName = LibCatalogoModel::findModelByTableName($tableName);
        if (!$modelName) {
            abort(404);
        }
        /**
         * @var LibCatalogoModel $model
         */
        $model = new $modelName;

        $criterio = $request->criterio;
        $datos = $model::GetSearchRespuestas($tableName, $criterio, $paginate, $page);

        $respuesta = new LibCatalogoResponse();
        $respuesta->setModel($model);
        $respuesta->setRespuestas($datos);

        //respuesta
        return $respuesta;
    }

    /**
     * Salva respuestas
     *
     * @param Request    $request
     * @param            $tableName
     *
     * @param null|Model $modelToEdit Si se recibe entonces editaremos dicho modelo
     *
     * @return LibCatalogoModel
     * @throws ValidationException
     */
    public static function save(Request $request, $tableName, $modelToEdit = null): LibCatalogoModel
    {

        //Encontramos modelo
        $modelName = LibCatalogoModel::findModelByTableName($tableName);
        if (!$modelName) {
            abort(403);
        }
        /**
         * @var LibCatalogoModel $model
         */
        $model = null;
        if (!is_null($modelToEdit)) {
            //edicion forzada
            $model = $modelToEdit;
        } else if ($request->has('id')) {
            //edicion
            $model = $modelName::find($request->get('id'));
        } else {
            //nuevo
            $model = new $modelName;
        }
        //Validamos
        $validaciones = $model->Validaciones($request);
        if ($validaciones->fails()) {
            throw new ValidationException($validaciones);
        }
        //Almacenamos
        $model->Salvar($request);

        //Regresamos a catalogos
        return $model;
    }

    /**
     * @param Request $request
     * @param         $tableName
     *
     * @return LibCatalogoModel
     * @throws ValidationException
     */
    public static function delete(Request $request, $tableName): LibCatalogoModel
    {
        //Encontramos modelo
        $modelName = LibCatalogoModel::findModelByTableName($tableName);
        if (!$modelName) {
            abort(403);
        }
        $modelId = $request->get('id');
        /**
         * @var LibCatalogoModel $model
         */
        $model = $modelName::find($modelId);
        $tableName = $model->GetTable();

        //Validamos
        $validaciones = Validator::make([
            'id' => $modelId,
        ], [
            'id' => "required|exists:$tableName,id",
        ]);

        $validaciones->after(function ($validator) use ($model) {
            if (!$model) {
                $validator->errors()->add('id', __('catalog.error.id'));
            }
        });


        if ($validaciones->fails()) {
            throw new ValidationException($validaciones);
        }
        //Almacenamos
        $model->delete();

        //Regresamos a catalogos
        return $model;
    }

    /**
     * @param Request $request
     * @param         $tableName
     *
     * @return LibCatalogoModel
     * @throws ValidationException
     */
    public static function restore(Request $request, $tableName): LibCatalogoModel
    {
        //Encontramos modelo
        $modelName = LibCatalogoModel::findModelByTableName($tableName);
        if (!$modelName) {
            abort(403);
        }
        $modelId = $request->get('id');
        /**
         * @var LibCatalogoModel $model
         */
        $model = $modelName::withTrashed()->find($modelId);

        //Validamos
        $validaciones = Validator::make([
            'id' => $modelId,
        ], [
            'id' => "required|exists:$tableName,id",
        ]);

        $validaciones->after(function ($validator) use ($model) {
            if (!$model) {
                $validator->errors()->add('id', __('catalog.error.id'));
            }
        });


        if ($validaciones->fails()) {
            throw new ValidationException($validaciones);
        }
        //Almacenamos
        $model->restore();

        //Regresamos a catalogos
        return $model;
    }
}
