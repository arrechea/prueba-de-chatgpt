<?php
/**
 * Created by gafa.
 */
namespace App\Librerias;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class LibBase
{

    public static function GetAll(Array $request, $relativeToModel)
    {
        $perPage = $request['per_page'] ?: 15;
        if (isset($request['trash']) && $request['trash'] == 1) {
            $items = $relativeToModel::onlyTrashed()->paginate($perPage);
        } else {
            $items = $relativeToModel::paginate($perPage);
        }

        return $items;
    }

    public static function GetAllSearch(Array $request, $relativeToModel)
    {
        $perPage = $request['per_page'] ?: 15;
        $search = $request['search'] ?: "";
        $order = $request['order'] ?: "";

        $table_name = with(new $relativeToModel)->getTable();
        $columns = DB::getSchemaBuilder()->getColumnListing($table_name);
        $query = $relativeToModel::select();

        foreach ($columns as $column) {
            $query->orWhere($column, 'LIKE', '%' . $search . '%');
        }

        if ($order != "" && !empty($order[0]['col_name'])) {
            $query->orderBy($order[0]['col_name'], $order[0]['dir']);
        }

        if (isset($request['trash']) && $request['trash'] == 1) {
            $items = $relativeToModel::onlyTrashed()->paginate($perPage);
        } else {
            $items = $query->paginate($perPage);
        }
        return $items;
    }

    public static function GetOne($id, $relativeToModel)
    {
        $item = $relativeToModel::find($id);
        $validator = Validator::make([], []);
        if (!$item){
            $validator->errors()->add('not_found', "ID $id " . __('common.not_found'));
            throw new ValidationException($validator);
        }

        return $item;
    }

    public static function Delete($id, $relativeToModel)
    {
        $item = $relativeToModel::destroy($id);
        $validator = Validator::make([], []);
        if (!$item){
            $validator->errors()->add('not_found', "ID $id " . __('common.not_found'));
            throw new ValidationException($validator);
        }

        return $item;
    }

    public static function Restore($id, $relativeToModel)
    {
        $item = $relativeToModel::onlyTrashed()->find($id);
        $validator = Validator::make([], []);
        if (!$item){
            $validator->errors()->add('not_found', "ID $id " . __('common.not_found'));
            throw new ValidationException($validator);
        }

        $item->restore();

        return $item;
    }

    abstract public static function Create(Array $request, $relativeToModel);
    abstract public static function Update(Array $request, $relativeToModel, $id);
}
