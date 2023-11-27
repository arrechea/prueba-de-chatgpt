<?php

use App\Librerias\Permissions\Ability;
use App\Librerias\Permissions\LibListPermissions;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Database\Seeder;

class AbilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Access abilities
         */
        Ability::updateOrCreate([
            'name'        => 'access',
            'entity_type' => null,
        ], [
            'title' => 'roles.ability-access-gafafit',
        ]);

        Ability::updateOrCreate([
            'name'        => 'access',
            'entity_type' => Company::class,
        ], [
            'title' => 'roles.ability-access-company',
        ]);

        Ability::updateOrCreate([
            'name'        => 'access',
            'entity_type' => Brand::class,
        ], [
            'title' => 'roles.ability-access-brand',
        ]);

        Ability::updateOrCreate([
            'name'        => 'access',
            'entity_type' => Location::class,
        ], [
            'title' => 'roles.ability-access-location',
        ]);

        //---------Menus---------//
        //--->---------Nivel Gafafit---------//
        Ability::updateOrCreate([
            'name'        => 'menu.companies',
            'entity_type' => null,
        ], [
            'title'             => 'roles.menu-companies',
            'ability_groups_id' => 4,
        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.users',
            'entity_type' => null,
        ], [
            'title'             => 'roles.menu-users',
            'ability_groups_id' => 2,
        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.administrators',
            'entity_type' => null,
        ], [
            'title'             => 'roles.menu-administrators',
            'ability_groups_id' => 5,
        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.payments',
            'entity_type' => null,
        ], [
            'title'             => 'roles.menu-payments',
            'ability_groups_id' => 31,
        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.settings',
            'entity_type' => null,
        ], [
            'title'             => 'roles.menu-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_ROLES,
            'entity_type' => null,
        ], [
            'title'             => 'roles.menu-roles',
            'ability_groups_id' => 15,
        ]);


        //--->---------Nivel Company---------//
        Ability::updateOrCreate([
            'name'        => 'menu.brands',
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-brands',
            'ability_groups_id' => 16,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_COMPANIES,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-companies',
            'ability_groups_id' => 4,
        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.users',
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-users',
            'ability_groups_id' => 2,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_ADMINISTRATORS,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-administrators',
            'ability_groups_id' => 5,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_ROLES,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-roles',
            'ability_groups_id' => 15,
        ]);
//        Ability::updateOrCreate([
//            'name'        => 'menu.metrics',
//            'entity_type' => Company::class,
//        ], [
//            'title'             => 'roles.menu-metrics',
//            'ability_groups_id' => 7,
//        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.settings',
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.marketing',
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-marketing',
            'ability_groups_id' => 8,
        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.services',
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-services',
            'ability_groups_id' => 9,
        ]);


        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => 'menu.studies',
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-studies',
            'ability_groups_id' => 10,
        ]);

        Ability::updateOrCreate([
            'name'        => 'menu.users',
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-users',
            'ability_groups_id' => 2,
        ]);

//        Ability::updateOrCreate([
//            'name'        => 'menu.reservations',
//            'entity_type' => Brand::class,
//        ], [
//            'title'             => 'roles.menu-reservations',
//            'ability_groups_id' => 11,
//        ]);

        Ability::updateOrCreate([
            'name'        => 'menu.store',
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-store',
            'ability_groups_id' => 12,
        ]);


//        Ability::updateOrCreate([
//            'name'        => 'menu.instructors',
//            'entity_type' => Brand::class,
//        ], [
//            'title'             => 'roles.menu-instructors',
//            'ability_groups_id' => 13,
//        ]);

        Ability::updateOrCreate([
            'name'        => 'menu.marketing',
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-marketing',
            'ability_groups_id' => 8,
        ]);

        Ability::updateOrCreate([
            'name'        => 'menu.administration',
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-administration',
            'ability_groups_id' => 14,
        ]);
        Ability::updateOrCreate([
            'name'        => 'menu.discount',
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-discount',
            'ability_groups_id' => 34,
        ]);
        //--->---------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => 'menu.calendar',
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-calendar',
            'ability_groups_id' => 18,
        ]);

        Ability::updateOrCreate([
            'name'        => 'menu.rooms',
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-rooms',
            'ability_groups_id' => 17,
        ]);

        //-----------------LOCATION---------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::LOCATION_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-location',
            'ability_groups_id' => 14,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::LOCATION_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-location',
            'ability_groups_id' => 14,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::LOCATION_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-location',
            'ability_groups_id' => 14,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::LOCATION_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-location',
            'ability_groups_id' => 14,
        ]);

        //---------Companies---------//
        //--->---------Nivel Gafafit---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMPANY_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.view-companies',
            'ability_groups_id' => 4,
        ]);
        Ability::updateOrCreate([
            'name'        => 'companies.create',
            'entity_type' => null,
        ], [
            'title'             => 'roles.create-companies',
            'ability_groups_id' => 4,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMPANY_EDIT,
            'entity_type' => null,
        ], [
            'title'             => 'roles.edit-companies',
            'ability_groups_id' => 4,
        ]);

        Ability::updateOrCreate([
            'name'        => 'companies.delete',
            'entity_type' => null,
        ], [
            'title'             => 'roles.delete-companies',
            'ability_groups_id' => 4,
        ]);
        //--->---------Nivel Compañía---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMPANY_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.view-companies',
            'ability_groups_id' => 4,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMPANY_CREATE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.create-companies',
            'ability_groups_id' => 4,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMPANY_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.edit-companies',
            'ability_groups_id' => 4,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMPANY_DELETE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.delete-companies',
            'ability_groups_id' => 4,
        ]);

        //---------Users---------//
        //--->---------Nivel Gafafit---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.view-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_CREATE,
            'entity_type' => null,
        ], [
            'title'             => 'roles.create-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_EDIT,
            'entity_type' => null,
        ], [
            'title'             => 'roles.edit-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_DELETE,
            'entity_type' => null,
        ], [
            'title'             => 'roles.delete-users',
            'ability_groups_id' => 2,
        ]);

        //--->---------Nivel Company---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.view-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_CREATE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.create-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.edit-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_DELETE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.delete-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_VERIFY,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.verify-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_UNBLOCK,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.unblock-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_BLOCK,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.block-users',
            'ability_groups_id' => 2,
        ]);

        //--->---------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.delete-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_USERS,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-users',
            'ability_groups_id' => 2,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::SUBSCRIPTION_CANCEL,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.subscriptions-cancel',
            'ability_groups_id' => 2,
        ]);


        //---------Admins---------//
        //--->---------Nivel Gafafit---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.view-administrators',
            'ability_groups_id' => 5,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_CREATE,
            'entity_type' => null,
        ], [
            'title'             => 'roles.create-administrators',
            'ability_groups_id' => 5,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_EDIT,
            'entity_type' => null,
        ], [
            'title'             => 'roles.edit-administrators',
            'ability_groups_id' => 5,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_DELETE,
            'entity_type' => null,
        ], [
            'title'             => 'roles.delete-administrators',
            'ability_groups_id' => 5,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_ASIGN_ROLES,
            'entity_type' => null,
        ], [
            'title'             => 'roles.assign-administrators',
            'ability_groups_id' => 5,
        ]);
        //--->---------Nivel Company---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.view-administrators',
            'ability_groups_id' => 5,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_CREATE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.create-administrators',
            'ability_groups_id' => 5,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.edit-administrators',
            'ability_groups_id' => 5,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_DELETE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.delete-administrators',
            'ability_groups_id' => 5,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ADMIN_ASIGN_ROLES,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.assign-administrators',
            'ability_groups_id' => 5,
        ]);

        //---------Brands---------//
        //--->---------Nivel Company---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::BRANDS_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.view-brands',
            'ability_groups_id' => 16,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::BRANDS_CREATE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.create-brands',
            'ability_groups_id' => 16,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::BRANDS_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.edit-brands',
            'ability_groups_id' => 16,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::BRANDS_DELETE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.delete-brands',
            'ability_groups_id' => 16,
        ]);

        //---------Metrics---------//
        //--->---------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::METRICS_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-metrics',
            'ability_groups_id' => 7,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::METRICS_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-metrics',
            'ability_groups_id' => 7,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::METRICS_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-metrics',
            'ability_groups_id' => 7,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::METRICS_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.delete-metrics',
            'ability_groups_id' => 7,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::EXPORT_METRICS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.export-metrics',
            'ability_groups_id' => 7,
        ]);

        //---------Marketing---------//
        //--->---------Nivel Company---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MARKETING_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-marketing',
            'ability_groups_id' => 8,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MARKETING_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-marketing',
            'ability_groups_id' => 8,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MARKETING_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-marketing',
            'ability_groups_id' => 8,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MARKETING_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-marketing',
            'ability_groups_id' => 8,
        ]);

        //---------Services---------//
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SERVICES_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-services',
            'ability_groups_id' => 9,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SERVICES_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-services',
            'ability_groups_id' => 9,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SERVICES_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-services',
            'ability_groups_id' => 9,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SERVICES_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-services',
            'ability_groups_id' => 9,
        ]);

        //---------Services Special Texts---------//
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SERVICE_SPECIAL_TEXTS_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-services-special-text',
            'ability_groups_id' => 9,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SERVICE_SPECIAL_TEXTS_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-services-special-text',
            'ability_groups_id' => 9,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SERVICE_SPECIAL_TEXTS_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-services-special-text',
            'ability_groups_id' => 9,
        ]);

        //---------Staff---------//
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::STAFF_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-staff',
            'ability_groups_id' => 13,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::STAFF_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-staff',
            'ability_groups_id' => 13,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::STAFF_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-staff',
            'ability_groups_id' => 13,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::STAFF_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-staff',
            'ability_groups_id' => 13,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_STAFF,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-staff',
            'ability_groups_id' => 13,
        ]);

        //---------Staff Special Texts---------//
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::STAFF_SPECIAL_TEXTS_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-staff-special-text',
            'ability_groups_id' => 13,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::STAFF_SPECIAL_TEXTS_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-staff-special-text',
            'ability_groups_id' => 13,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::STAFF_SPECIAL_TEXTS_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-staff-special-text',
            'ability_groups_id' => 13,
        ]);

        //---------Roles---------//
        //--->---------Nivel Gafafit---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROLES_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.view-roles',
            'ability_groups_id' => 15,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROLES_CREATE,
            'entity_type' => null,
        ], [
            'title'             => 'roles.create-roles',
            'ability_groups_id' => 15,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROLES_EDIT,
            'entity_type' => null,
        ], [
            'title'             => 'roles.edit-roles',
            'ability_groups_id' => 15,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROLES_DELETE,
            'entity_type' => null,
        ], [
            'title'             => 'roles.delete-roles',
            'ability_groups_id' => 15,
        ]);
        //--->---------Nivel Company---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROLES_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.view-roles',
            'ability_groups_id' => 15,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROLES_CREATE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.create-roles',
            'ability_groups_id' => 15,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROLES_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.edit-roles',
            'ability_groups_id' => 15,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROLES_DELETE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.delete-roles',
            'ability_groups_id' => 15,
        ]);
        //---------Payment Types -------//
        //---------Nivel Gafafit-------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::PAYMENTS_EDIT,
            'entity_type' => null,
        ], [
            'title'             => 'roles.edit-payments',
            'ability_groups_id' => 31,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::PAYMENTS_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.view-payments',
            'ability_groups_id' => 31,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_PAYMENTS,
            'entity_type' => null,
        ], [
            'title'             => 'roles.menu-payments',
            'ability_groups_id' => 31,
        ]);

        //---------Settings---------//
        //--->---------Nivel Gafafit---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SETTINGS_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.view-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SETTINGS_EDIT,
            'entity_type' => null,
        ], [
            'title'             => 'roles.edit-settings',
            'ability_groups_id' => 6,
        ]);
        //--->---------Nivel Company---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SETTINGS_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.view-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SETTINGS_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.edit-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_SETTINGS,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-settings',
            'ability_groups_id' => 6,
        ]);
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SETTINGS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SETTINGS_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_SETTINGS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-settings',
            'ability_groups_id' => 6,
        ]);
        //--->---------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SETTINGS_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SETTINGS_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-settings',
            'ability_groups_id' => 6,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_SETTINGS,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-settings',
            'ability_groups_id' => 6,
        ]);

        //-----Marketing---------//
        //---------Offers---------//
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::OFFER_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-offers',
            'ability_groups_id' => 20,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::OFFER_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-offers',
            'ability_groups_id' => 20,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::OFFER_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-offers',
            'ability_groups_id' => 20,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::OFFER_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-offers',
            'ability_groups_id' => 20,
        ]);

        //-----Room---------//
        //--->---------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROOMS_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-rooms',
            'ability_groups_id' => 16,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROOMS_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-rooms',
            'ability_groups_id' => 16,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROOMS_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-rooms',
            'ability_groups_id' => 16,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ROOMS_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.delete-rooms',
            'ability_groups_id' => 16,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_ROOMS,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-rooms',
            'ability_groups_id' => 16,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAPS_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-maps',
            'ability_groups_id' => 33,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAPS_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-maps',
            'ability_groups_id' => 33,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAPS_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-maps',
            'ability_groups_id' => 33,
        ]);
        //-----Membership---------//
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MEMBERSHIP_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-memberships',
            'ability_groups_id' => 19,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MEMBERSHIP_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-memberships',
            'ability_groups_id' => 19,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MEMBERSHIP_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-memberships',
            'ability_groups_id' => 19,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MEMBERSHIP_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-memberships',
            'ability_groups_id' => 19,
        ]);

        //-----Reservation---------//
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_RESERVATIONS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-reservations',
            'ability_groups_id' => 11,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::RESERVATION_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-reservations',
            'ability_groups_id' => 11,
        ]);

        //-----Reservation---------//
        //--->---------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::RESERVATION_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-reservations',
            'ability_groups_id' => 11,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::RESERVATION_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-reservations',
            'ability_groups_id' => 11,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::RESERVATION_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-reservations',
            'ability_groups_id' => 11,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::RESERVATION_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.delete-reservations',
            'ability_groups_id' => 11,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_RESERVATIONS,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-reservations',
            'ability_groups_id' => 11,
        ]);
        //Permiso especial de cancelación (puede cancelar hasta que empiece el meeting)
        Ability::updateOrCreate([
            'name'        => LibListPermissions::RESERVATIONS_SPECIAL_CANCEL,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-meetings-special',
            'ability_groups_id' => 11,
        ]);

        //-----Calendar---------//
        //--->---------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CALENDAR_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-calendar',
            'ability_groups_id' => 18,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_CALENDAR,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-calendar',
            'ability_groups_id' => 18,
        ]);

        //-----Combos---------//
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMBOS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-combos',
            'ability_groups_id' => 21,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMBOS_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-combos',
            'ability_groups_id' => 21,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMBOS_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-combos',
            'ability_groups_id' => 21,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::COMBOS_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-combos',
            'ability_groups_id' => 21,
        ]);

        //-----Credits---------//
        //--->---------Nivel Company---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CREDITSCOMPANY_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.view-credits',
            'ability_groups_id' => 49,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CREDITSCOMPANY_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.edit-credits',
            'ability_groups_id' => 49,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CREDITSCOMPANY_CREATE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.create-credits',
            'ability_groups_id' => 49,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CREDITSCOMPANY_DELETE,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.delete-credits',
            'ability_groups_id' => 49,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_CREDITS,
            'entity_type' => Company::class,
        ], [
            'title'             => 'roles.menu-credits',
            'ability_groups_id' => 49,
        ]);
        //--->---------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CREDITS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-credits',
            'ability_groups_id' => 22,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CREDITS_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-credits',
            'ability_groups_id' => 22,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CREDITS_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-credits',
            'ability_groups_id' => 22,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CREDITS_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-credits',
            'ability_groups_id' => 22,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_CREDITS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-credits',
            'ability_groups_id' => 22,
        ]);

        //-----Meetings---------//
        //--->---------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MEETINGS_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-meetings',
            'ability_groups_id' => 23,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MEETINGS_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-meetings',
            'ability_groups_id' => 23,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MEETINGS_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-meetings',
            'ability_groups_id' => 23,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MEETINGS_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.delete-meetings',
            'ability_groups_id' => 23,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_MEETINGS,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-meetings',
            'ability_groups_id' => 23,
        ]);
//--------------METRICS------------------//
        //--------------Nivel location------------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_METRICS,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-metrics',
            'ability_groups_id' => 7,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::METRICS_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-metrics',
            'ability_groups_id' => 7,
        ]);
        //--------------Nivel Brand------------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_METRICS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-metrics',
            'ability_groups_id' => 7,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::METRICS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-metrics',
            'ability_groups_id' => 7,
        ]);
//-----------END METRICS----------------//

        //-----Mails Notificación---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::NOTIFICATION_EMAIL_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'notifications.view-sender-email',
            'ability_groups_id' => 24,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::NOTIFICATION_EMAIL_EDIT,
            'entity_type' => null,
        ], [
            'title'             => 'notifications.edit-sender-email',
            'ability_groups_id' => 24,
        ]);

        //-----Mails---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_MAILS,
            'entity_type' => Company::class,
        ], [
            'title'             => 'mails.menu',
            'ability_groups_id' => 25,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_MAILS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.menu',
            'ability_groups_id' => 25,
        ]);
        //------------->Confirmar reservación <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_RESERVATION_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.view-confirm-reservation',
            'ability_groups_id' => 28,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_RESERVATION_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.edit-confirm-reservation',
            'ability_groups_id' => 28,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_RESERVATION_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.menu-confirm-reservation',
            'ability_groups_id' => 28,
        ]);

        //------------->Confirmar compra <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_PURCHASE_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.view-confirm-purchase',
            'ability_groups_id' => 29,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_PURCHASE_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.edit-confirm-purchase',
            'ability_groups_id' => 29,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_PURCHASE_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.menu-confirm-purchase',
            'ability_groups_id' => 29,
        ]);

        //------------->Cancelar reservación <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_RESERVATION_CANCELLED_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.view-cancelled-reservation',
            'ability_groups_id' => 30,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_RESERVATION_CANCELLED_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.edit-cancelled-reservation',
            'ability_groups_id' => 30,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_RESERVATION_CANCELLED_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.menu-cancelled-reservation',
            'ability_groups_id' => 30,
        ]);

        //------------->Confirmar subscripción <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_SUBSCRIPTION_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.view-confirm-subscription',
            'ability_groups_id' => 46,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_SUBSCRIPTION_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.edit-confirm-subscription',
            'ability_groups_id' => 46,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_SUBSCRIPTION_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.menu-confirm-subscription',
            'ability_groups_id' => 46,
        ]);

        //------------->Error subscripción <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_ERROR_SUBSCRIPTION_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.view-error-subscription',
            'ability_groups_id' => 47,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_ERROR_SUBSCRIPTION_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.edit-error-subscription',
            'ability_groups_id' => 47,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_ERROR_SUBSCRIPTION_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.menu-error-subscription',
            'ability_groups_id' => 47,
        ]);

        //------------->Bienvenida <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_WELCOME_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'mails.view-welcome',
            'ability_groups_id' => 26,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_WELCOME_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'mails.edit-welcome',
            'ability_groups_id' => 26,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_WELCOME_MENU,
            'entity_type' => Company::class,
        ], [
            'title'             => 'mails.menu-welcome',
            'ability_groups_id' => 26,
        ]);

        //------------->Cambio de contraseña <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_RESET_PASSWORD_VIEW,
            'entity_type' => Company::class,
        ], [
            'title'             => 'mails.view-password-reset',
            'ability_groups_id' => 27,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_RESET_PASSWORD_EDIT,
            'entity_type' => Company::class,
        ], [
            'title'             => 'mails.edit-password-reset',
            'ability_groups_id' => 27,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_RESET_PASSWORD_MENU,
            'entity_type' => Company::class,
        ], [
            'title'             => 'mails.menu-password-reset',
            'ability_groups_id' => 27,
        ]);
        //------------->Confirmar waitlist <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_WAITLIST_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-confirm-waitlist',
            'ability_groups_id' => 41,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_WAITLIST_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-confirm-waitlist',
            'ability_groups_id' => 41,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_WAITLIST_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-confirm-waitlist',
            'ability_groups_id' => 41,
        ]);
        //------------->Confirmar invitación <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_INVITATION_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.view-confirm-invitation',
            'ability_groups_id' => 50,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CONFIRM_INVITATION_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'mails.edit-confirm-invitation',
            'ability_groups_id' => 50,
        ]);
        //------------->Cancelar waitlist <------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CANCEL_WAITLIST_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-cancel-waitlist',
            'ability_groups_id' => 40,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CANCEL_WAITLIST_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-cancel-waitlist',
            'ability_groups_id' => 40,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MAILS_CANCEL_WAITLIST_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-cancel-waitlist',
            'ability_groups_id' => 40,
        ]);

        //----------Purchases--------//
        //---------Nivel Location-------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_PURCHASE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-purchases',
            'ability_groups_id' => 32,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::PURCHASE_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-purchase',
            'ability_groups_id' => 32,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::PURCHASE_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-purchase',
            'ability_groups_id' => 32,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::PURCHASE_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-purchase',
            'ability_groups_id' => 32,
        ]);

        //-------Discount code--------//
        //-------Nivel Brand---------//


        Ability::updateOrCreate([
            'name'        => LibListPermissions::DISCOUNT_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.view-discount',
            'ability_groups_id' => 34,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::DISCOUNT_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.edit-discount',
            'ability_groups_id' => 34,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::DISCOUNT_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.create-discount',
            'ability_groups_id' => 34,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::DISCOUNT_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.delete-discount',
            'ability_groups_id' => 34,
        ]);

        /*
         * GIFTCARDS
         */
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GIFTCARD_ASSIGN,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.giftcard-assign',
            'ability_groups_id' => 35,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GIFTCARD_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.giftcard-view',
            'ability_groups_id' => 35,
        ]);
        //----------System Log--------//
        //---------Nivel Gafafit-------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_SYSTEM_LOG,
            'entity_type' => null,
        ], [
            'title'             => 'roles.menu-system-log',
            'ability_groups_id' => 36,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::SYSTEM_LOG_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.view-system-log',
            'ability_groups_id' => 36,
        ]);

        //---------catalogs-----------//
        //--------Nivel Brand---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_CATALOGS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-catalogs',
            'ability_groups_id' => 39,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CATALOGS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.catalogs-view',
            'ability_groups_id' => 39,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CATALOGS_RESERVATIONS_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-catalogs-reservations',
            'ability_groups_id' => 39,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CATALOGS_RESERVATIONS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.catalogs-view-reservations',
            'ability_groups_id' => 39,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CATALOGS_PURCHASES_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-catalogs-purchases',
            'ability_groups_id' => 39,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CATALOGS_PURCHASES_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.catalogs-view-purchases',
            'ability_groups_id' => 39,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CATALOGS_SUBSCRIPTIONS_MENU,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-catalogs-subscriptions',
            'ability_groups_id' => 39,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::CATALOGS_SUBSCRIPTIONS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.catalogs-view-subscriptions',
            'ability_groups_id' => 39,
        ]);


        //-------Waitlist--------//
        //-------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::WAITLIST_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-waitlist',
            'ability_groups_id' => 37,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::WAITLIST_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-waitlist',
            'ability_groups_id' => 37,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::WAITLIST_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-waitlist',
            'ability_groups_id' => 37,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::WAITLIST_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.delete-waitlist',
            'ability_groups_id' => 37,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_WAITLIST,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-waitlist',
            'ability_groups_id' => 37,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::WAITLIST_MOVE_TO_OVERBOOKING,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.move-to-overbooking-waitlist',
            'ability_groups_id' => 37,
        ]);

        //-------Overbooking--------//
        //-------Nivel Location---------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::OVERBOOKING_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.view-overbooking',
            'ability_groups_id' => 38,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::OVERBOOKING_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.edit-overbooking',
            'ability_groups_id' => 38,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::OVERBOOKING_CREATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.create-overbooking',
            'ability_groups_id' => 38,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::OVERBOOKING_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.delete-overbooking',
            'ability_groups_id' => 38,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_OVERBOOKING,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-overbooking',
            'ability_groups_id' => 38,
        ]);

        //----------Special Texts------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::SPECIAL_TEXT_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.special-text-view',
            'ability_groups_id' => 42,
        ]);

        //----------Attendance List------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::ATTENDANCE_LIST_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.attendance-edit',
            'ability_groups_id' => 11,
        ]);


        //----------User Credits------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_CREDITS_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.user_credits-edit',
            'ability_groups_id' => 43,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_CREDITS_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.user_credits-delete',
            'ability_groups_id' => 43,
        ]);

        //----------User Memberships------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_MEMBERSHIP_DELETE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.user_memberships-delete',
            'ability_groups_id' => 44,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::USER_MEMBERSHIP_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.user_memberships-edit',
            'ability_groups_id' => 44,
        ]);

        //----------Products------------//
        Ability::updateOrCreate([
            'name'        => LibListPermissions::PRODUCTS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.products-view',
            'ability_groups_id' => 48,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::PRODUCTS_CREATE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.products-create',
            'ability_groups_id' => 48,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::PRODUCTS_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.products-edit',
            'ability_groups_id' => 48,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::PRODUCTS_DELETE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.products-delete',
            'ability_groups_id' => 48,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_PRODUCTS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-products',
            'ability_groups_id' => 48,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::PRODUCTS_SALES,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.products-sales',
            'ability_groups_id' => 48,
        ]);
//        Ability::updateOrCreate([
//            'name' => LibListPermissions::SPECIAL_TEXT_EDIT,
//            'entity_type' => null,
//        ],[
//            'title' => 'roles.special-text-view',
//            'ability_groups_id' => 42
//        ]);
//        Ability::updateOrCreate([
//            'name' => LibListPermissions::SPECIAL_TEXT_CREATE,
//            'entity_type' => null,
//        ],[
//            'title' => 'roles.special-text-view',
//            'ability_groups_id' => 42
//        ]);
//        Ability::updateOrCreate([
//            'name' => LibListPermissions::SPECIAL_TEXT_DELETE,
//            'entity_type' => null,
//        ],[
//            'title' => 'roles.special-text-view',
//            'ability_groups_id' => 42
//        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_BUSINESS_INTELLIGENCE,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.menu-business-intelligence',
            'ability_groups_id' => 7,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::MENU_BUSINESS_INTELLIGENCE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.menu-business-intelligence',
            'ability_groups_id' => 7,
        ]);

        Ability::updateOrCreate([
            'name'        => LibListPermissions::BUSINESS_INTELLIGENCE_RESERVATIONS,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.business-intelligence-reservations',
            'ability_groups_id' => 7,
        ]);

        //----------Gympass------------//
        //Settings
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_SETTINGS_VIEW,
            'entity_type' => null,
        ], [
            'title'             => 'roles.gympass-settings-view',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_SETTINGS_EDIT,
            'entity_type' => null,
        ], [
            'title'             => 'roles.gympass-settings-edit',
            'ability_groups_id' => 51,
        ]);
        //Class
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_CLASS_VIEW,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.gympass-class-view',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_CLASS_EDIT,
            'entity_type' => Brand::class,
        ], [
            'title'             => 'roles.gympass-class-edit',
            'ability_groups_id' => 51,
        ]);
        //Slot
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_SLOT_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-slot-view',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_SLOT_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-slot-edit',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_SLOT_REGENERATE,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-slot-regenerate',
            'ability_groups_id' => 51,
        ]);
        //Booking
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_BOOKING_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-booking-view',
            'ability_groups_id' => 51,
        ]);
        //User
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_USER_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-user-view',
            'ability_groups_id' => 51,
        ]);
        //Check-in
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_CHECKIN_VALIDATE_MENU,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-checkin-validate-menu',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_CHECKIN_VALIDATE_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-checkin-validate-view',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_CHECKIN_VALIDATE_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-checkin-validate-edit',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_CHECKIN_ADMIN_MENU,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-checkin-admin-menu',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_CHECKIN_ADMIN_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-checkin-admin-view',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_CHECKIN_ADMIN_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-checkin-admin-edit',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_LOCATION_VIEW,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-location-view',
            'ability_groups_id' => 51,
        ]);
        Ability::updateOrCreate([
            'name'        => LibListPermissions::GYMPASS_LOCATION_EDIT,
            'entity_type' => Location::class,
        ], [
            'title'             => 'roles.gympass-location-edit',
            'ability_groups_id' => 51,
        ]);
    }
}
