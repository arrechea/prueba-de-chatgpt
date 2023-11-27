<?php

use App\Models\Catalogs\CatalogField;
use App\Models\Catalogs\CatalogGroup;
use App\Models\Catalogs\CatalogsFieldsOptions;
use Illuminate\Database\Seeder;

class ZudaCatalogsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Grupos
        $repeat_group = CatalogGroup::updateOrCreate([
            'name' => 'Grupo Prueba Repetible',
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'brands_id'    => null,
        ]);

        $non_repeat_group = CatalogGroup::updateOrCreate([
            'name' => 'Grupo Prueba No Repetible',
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'brands_id'    => null,
        ]);

        //Text Fields
        CatalogField::updateOrCreate([
            'name'               => 'Texto Prueba Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'text',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Texto Prueba Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'text',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Texto Prueba No Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'text',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Texto Prueba No Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'text',
        ]);

        //Number Fields
        CatalogField::updateOrCreate([
            'name'               => 'Número Prueba Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'number',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Número Prueba Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'number',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Número Prueba No Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'number',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Número Prueba No Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'number',
        ]);

        //Date Fields
        CatalogField::updateOrCreate([
            'name'               => 'Fecha Prueba Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'date',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Fecha Prueba Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'date',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Fecha Prueba No Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'date',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Fecha Prueba No Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'date',
        ]);

        //Textarea Fields
        CatalogField::updateOrCreate([
            'name'               => 'Área de Texto Prueba Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'textarea',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Área de Texto Prueba Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'textarea',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Área de Texto Prueba No Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'textarea',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Área de Texto Prueba No Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'textarea',
        ]);

        //File Fields
        CatalogField::updateOrCreate([
            'name'               => 'Archivo Prueba Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'file',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Archivo Prueba Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'file',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Archivo Prueba No Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'file',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Archivo Prueba No Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'file',
        ]);

        //Checkbox Fields
        CatalogField::updateOrCreate([
            'name'               => 'Checkbox Prueba Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'checkbox',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Checkbox Prueba Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'checkbox',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Checkbox Prueba No Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'checkbox',
        ]);
        CatalogField::updateOrCreate([
            'name'               => 'Checkbox Prueba No Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'checkbox',
        ]);

        //Select Fields
        $repeat_select_repeat_group = CatalogField::updateOrCreate([
            'name'               => 'Selector Prueba Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'select',
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $repeat_select_repeat_group->id,
            'value'               => 'a1',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $repeat_group->id,
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $repeat_select_repeat_group->id,
            'value'               => 'a2',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $repeat_group->id,
        ]);

        $repeat_select_non_repeat_group = CatalogField::updateOrCreate([
            'name'               => 'Selector Prueba Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'select',
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $repeat_select_non_repeat_group->id,
            'value'               => 'a1',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $non_repeat_group->id,
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $repeat_select_non_repeat_group->id,
            'value'               => 'a2',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $non_repeat_group->id,
        ]);

        $non_repeat_select_repeat_group = CatalogField::updateOrCreate([
            'name'               => 'Selector Prueba No Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'select',
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $non_repeat_select_repeat_group->id,
            'value'               => 'a1',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $repeat_group->id,
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $non_repeat_select_repeat_group->id,
            'value'               => 'a2',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $repeat_group->id,
        ]);

        $non_repeat_select_non_repeat_group = CatalogField::updateOrCreate([
            'name'               => 'Selector Prueba No Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'select',
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $non_repeat_select_non_repeat_group->id,
            'value'               => 'a1',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $non_repeat_group->id,
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $non_repeat_select_non_repeat_group->id,
            'value'               => 'a2',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $non_repeat_group->id,
        ]);

        //Radio Fields
        $repeat_select_repeat_group = CatalogField::updateOrCreate([
            'name'               => 'Radio Prueba Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'radio',
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $repeat_select_repeat_group->id,
            'value'               => 'a1',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $repeat_group->id,
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $repeat_select_repeat_group->id,
            'value'               => 'a2',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $repeat_group->id,
        ]);

        $repeat_select_non_repeat_group = CatalogField::updateOrCreate([
            'name'               => 'Radio Prueba Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => true,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'radio',
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $repeat_select_non_repeat_group->id,
            'value'               => 'a1',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $non_repeat_group->id,
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $repeat_select_non_repeat_group->id,
            'value'               => 'a2',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $non_repeat_group->id,
        ]);

        $non_repeat_select_repeat_group = CatalogField::updateOrCreate([
            'name'               => 'Radio Prueba No Repetible',
            'catalogs_groups_id' => $repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'radio',
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $non_repeat_select_repeat_group->id,
            'value'               => 'a1',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $repeat_group->id,
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $non_repeat_select_repeat_group->id,
            'value'               => 'a2',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $repeat_group->id,
        ]);

        $non_repeat_select_non_repeat_group = CatalogField::updateOrCreate([
            'name'               => 'Radio Prueba No Repetible',
            'catalogs_groups_id' => $non_repeat_group->id,
        ], [
            'can_repeat'   => false,
            'status'       => 'active',
            'catalogs_id'  => 1,
            'companies_id' => 3,
            'help_text'    => 'Texto de ayuda',
            'type'         => 'radio',
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $non_repeat_select_non_repeat_group->id,
            'value'               => 'a1',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $non_repeat_group->id,
        ]);
        CatalogsFieldsOptions::updateOrCreate([
            'catalogs_fields_id'  => $non_repeat_select_non_repeat_group->id,
            'value'               => 'a2',
            'catalogs_id'         => 1,
            'catalogs_groups_id' => $non_repeat_group->id,
        ]);
    }
}
