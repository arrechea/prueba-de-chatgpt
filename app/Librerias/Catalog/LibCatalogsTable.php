<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 11/26/18
 * Time: 11:47 AM
 */

namespace App\Librerias\Catalog;

use App\Librerias\Catalog\Tables\Company\CatalogGroupsCatalogs;
use App\Models\Catalogs\CatalogField;
use App\Models\Catalogs\Catalogs;
use App\Models\Catalogs\CatalogsFieldsValues;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class LibCatalogsTable
{
    const UserProfile = 1;
    const Meetings = 2;
    const Staff = 3;
    const Services = 4;
    const Brand = 5;

    const Section_Register = 'register';
    const Section_Save = 'save';

    /**
     * @param Catalogs $catalog
     * @param string   $section
     * @param int      $companyId
     * @param int|null $brandId
     *
     * @return Collection
     * @internal param Catalogs $catalogo
     */
    static public function getGroupsInSection(Catalogs $catalog, string $section, int $companyId, int $brandId = null): Collection
    {
        $query = CatalogGroupsCatalogs::where('companies_id', $companyId)
            ->where('status', 'active')
            ->where('catalogs_id', $catalog->id)
            ->orderBy('order', 'asc');

        if (!is_null($brandId)) {
            $query->where('brands_id', $brandId);
        }

        if ($section !== LibCatalogsTable::Section_Save) {
            //Importan todas las secciones
            $query->whereHas('controls', function ($query) use ($section) {
                $query->where('section', $section);
            });
        }
        $query->with([
            'activeFields',
        ]);

        return $query->get();
    }

    /**
     * @param Model $model
     *
     * @return Catalogs|null
     */
    static public function getModelCatalog(Model $model): ?Catalogs
    {
        return Catalogs::where('table', $model->getTable())->first();
    }

    /**
     * @param Model      $model
     * @param string     $section
     * @param array      $values
     * @param array|null $files
     * @param int        $companyId
     * @param int|null   $brandId
     */
    static public function processSaveInModel(Model $model, string $section = null, array $values = null, array $files = null, int $companyId, int $brandId = null)
    {
        if (
            is_array($values)
            ||
            is_array($files)
            &&
            count($values) > 0
        ) {
            if (!is_array($values)) {
                $values = [];
            }
            if (!is_array($files)) {
                $files = [];
            }
            if (!$section) {
                $section = LibCatalogsTable::Section_Save;
            }
            $catalog = self::getModelCatalog($model);
            if ($catalog) {
                //Save process All Elements
                $groups = self::getGroupsInSection($catalog, $section, $companyId, $brandId);

                if ($groups->count() > 0) {
                    //Validate
                    self::validateSpecialText($groups, $values);
                    //Clean
                    self::cleanGroups($model, $groups, $values);
                    //Save
                    self::saveSpecialTexts($model, $groups, $values);
                    if (count($files) > 0) {
                        self::saveSpecialTexts($model, $groups, $files);
                    }
                }
            }
        }
    }

    /**
     * @param Model      $model
     * @param Collection $groups
     * @param array      $values
     */
    static private function cleanGroups(Model $model, Collection $groups, array $values = [])
    {
        foreach ($values as $groupId => $fields) {
            $groupId = (int)$groupId;
            //comprobamos existencia del grupo
            if (self::groupExistsInDB($groupId, $groups)) {

                //limpiamos campos del grupo
                self::clearGroupValuesInModel($model, $groupId);
            }
        }
    }

    /**
     * @param Model      $model
     * @param Collection $groups
     * @param array      $values
     *
     * @internal param bool $clear
     */
    static private function saveSpecialTexts(Model $model, Collection $groups, array $values = [])
    {
        if (is_array($values) && count($values) > 0) {
            $saveArray = [];

            foreach ($values as $groupId => $fields) {
                $groupId = (int)$groupId;
                //comprobamos existencia del grupo
                if (self::groupExistsInDB($groupId, $groups)) {

                    //recorremos campos checando seguridad y almacenamos
                    if (is_array($fields) && count($fields) > 0) {
                        foreach ($fields as $groupIndex => $groupsFields) {
                            if (is_array($groupsFields) && count($groupsFields) > 0) {
                                foreach ($groupsFields as $fieldId => $fieldValues) {
                                    //check field exists
                                    if (
                                        is_array($fieldValues)
                                        &&
                                        $fieldModel = self::getField($groupId, $fieldId, $groups)
                                    ) {
                                        foreach ($fieldValues as $fieldIndex => $value) {
                                            if (
                                                is_string($value)
                                                ||
                                                is_numeric($value)
                                                ||
                                                (
                                                    $value instanceof UploadedFile
                                                    &&
                                                    $fieldModel->isFile()
                                                )
                                            ) {
                                                if (
                                                    $value instanceof UploadedFile
                                                ) {
                                                    /**
                                                     * @var UploadedFile $value
                                                     */
                                                    $value = $fieldModel->UploadImage($value, "$groupId/$groupIndex/$fieldId/$fieldIndex/file");
                                                }

                                                $saveArray[] = [
                                                    'model_id'              => $model->id,
                                                    'table'                 => $model->getTable(),
                                                    'value'                 => $value,
                                                    'catalogs_groups_id'    => $groupId,
                                                    'catalogs_groups_index' => $groupIndex,
                                                    'catalogs_fields_id'    => $fieldId,
                                                    'catalogs_fields_index' => $fieldIndex,
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (count($saveArray) > 0) {
                //save
                DB::table('catalogs_fields_values')->insert($saveArray);
            }
        }
    }

    /**
     * @param int        $fieldId
     * @param Collection $groups
     *
     * @return bool
     */
    static private function fieldExistsInDB(int $groupId, int $fieldId, Collection $groups): bool
    {
        $field = self::getField($groupId, $fieldId, $groups);

        return $field ? true : false;
    }

    /**
     * @param int        $groupId
     * @param int        $fieldId
     * @param Collection $groups
     *
     * @return CatalogField|null
     */
    static private function getField(int $groupId, int $fieldId, Collection $groups): ?CatalogField
    {
        $response = null;

        $groups->each(function ($group) use ($fieldId, $groupId, &$response) {
            if (
                $group->id === $groupId
                &&
                !$response
            ) {

                $fieldsInGroup = $group->activeFields;
                if ($fieldsInGroup->count() > 0) {
                    $fieldsInGroup->each(function ($field) use ($fieldId, &$response) {
                        if ($field->id === $fieldId) {
                            $response = $field;
                        }
                    });
                }
            }
        });

        return $response;
    }

    /**
     * @param Model $model
     * @param int   $groupId
     */
    static private function clearGroupValuesInModel(Model $model, int $groupId)
    {
        CatalogsFieldsValues::where('model_id', $model->id)
            ->where('catalogs_groups_id', $groupId)
            ->delete();
        //todo delete images
    }

    /**
     * @param int        $groupId
     * @param Collection $groups
     *
     * @return bool
     */
    static private function groupExistsInDB(int $groupId, Collection $groups): bool
    {
        $cantidad = $groups->filter(function ($group) use ($groupId) {
            return $group->id === $groupId;
        })->count();

        return $cantidad > 0;
    }

    /**
     * @param Collection $groups
     * @param array      $values
     *
     * @throws ValidationException
     * @internal param CatalogField $field
     */
    static private function validateSpecialText(Collection $groups, array $values = [])
    {
        $validationArray = [];
        $errorsArray = [];

        //Generate validation array
        $groups->each(function ($group) use (&$validationArray, &$errorsArray) {
            $groupId = $group->id;
            $group->activeFields->each(function ($field) use ($groupId, &$validationArray, &$errorsArray) {
                $fieldId = $field->id;
                $validationField = is_null($field->validation) ? '' : $field->validation;
                $key = "$groupId.*.$fieldId.*";

                $validationArray[ $key ] = $validationField;
                self::createErrorArray($validationField, $key, $errorsArray, $field);
            });
        });

        $validator = Validator::make($values, $validationArray, $errorsArray);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private static function createErrorArray($validation, $name, &$errorsArray, $field)
    {
        $rules = explode('|', $validation);
        foreach ($rules as $rule) {
            $index = self::get_string_between($rule, '', ':');
            $errorsArray["$name.$index"] = __("validation.$index", ['attribute' => $field->name]);
        }
    }

    private static function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = $start !== '' ? strpos($string, $start) : 0;
        $ini += strlen($start);
        $pos = strpos($string, $end, $ini);
        $len = $pos !== false ? $pos - $ini : strlen($string);

        return trim(substr($string, $ini, $len));
    }
}
