<?php

use App\Librerias\Permissions\AbilityGroup;
use Illuminate\Database\Seeder;

class AbilityGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AbilityGroup::updateOrCreate([
            'id'   => 1,
            'name' => 'roles.general-group',
        ], [
            'order' => 0,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 2,
            'name' => 'roles.user-group',
        ], [
            'order' => 1,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 3,
            'name' => 'roles.menu-group',
        ], [
            'order' => 2,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 4,
            'name' => 'roles.company-group',
        ], [
            'order' => 3,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 5,
            'name' => 'roles.administrators-group',
        ], [
            'order' => 4,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 6,
            'name' => 'roles.settings-group',
        ], [
            'order' => 5,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 7,
            'name' => 'roles.metrics-group',
        ], [
            'order' => 6,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 8,
            'name' => 'roles.marketing-group',
        ], [
            'order' => 7,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 9,
            'name' => 'roles.services-group',
        ], [
            'order' => 8,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 10,
            'name' => 'roles.studies-group',
        ], [
            'order' => 9,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 11,
            'name' => 'roles.reservations-group',
        ], [
            'order' => 10,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 12,
            'name' => 'roles.store-group',
        ], [
            'order' => 11,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 13,
            'name' => 'roles.instructors-group',
        ], [
            'order' => 12,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 14,
            'name' => 'roles.administration-group',
        ], [
            'order' => 13,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 15,
            'name' => 'roles.roles-group',
        ], [
            'order' => 14,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 16,
            'name' => 'roles.brands-group',
        ], [
            'order' => 15,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 17,
            'name' => 'roles.rooms-group',
        ], [
            'order' => 16,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 18,
            'name' => 'roles.calendar-group',
        ], [
            'order' => 17,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 19,
            'name' => 'roles.membership-group',
        ], [
            'order' => 18,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 20,
            'name' => 'roles.offers-group',
        ], [
            'order' => 19,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 21,
            'name' => 'roles.combos-group',
        ], [
            'order' => 20,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 22,
            'name' => 'roles.credits-group',
        ], [
            'order' => 21,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 23,
            'name' => 'roles.meetings-group',
        ], [
            'order' => 22,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 24,
            'name' => 'roles.notifications-sender-email-group',
        ], [
            'order' => 23,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 25,
            'name' => 'roles.mails',
        ], [
            'order' => 24,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 26,
            'name' => 'roles.mails-welcome',
        ], [
            'order' => 25,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 27,
            'name' => 'roles.mails-reset-password',
        ], [
            'order' => 26,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 28,
            'name' => 'roles.mails-confirm-reservation',
        ], [
            'order' => 27,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 29,
            'name' => 'roles.mails-confirm-purchase',
        ], [
            'order' => 28,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 30,
            'name' => 'roles.mails-cancel-reservation',
        ], [
            'order' => 29,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 31,
            'name' => 'roles.payment-types-group',
        ], [
            'order' => 30,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 32,
            'name' => 'roles.purchases-group',
        ], [
            'order' => 31,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 33,
            'name' => 'roles.maps-group',
        ], [
            'order' => 32,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 34,
            'name' => 'roles.discount-group',
        ], [
            'order' => 33,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 35,
            'name' => 'roles.giftcard-group',
        ], [
            'order' => 34,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 36,
            'name' => 'roles.system-log',
        ], [
            'order' => 35,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 37,
            'name' => 'roles.waitlist',
        ], [
            'order' => 36,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 38,
            'name' => 'roles.overbooking',
        ], [
            'order' => 37,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 41,
            'name' => 'roles.mails-confirm-waitlist',
        ], [
            'order' => 38,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 40,
            'name' => 'roles.mails-cancel-waitlist',
        ], [
            'order' => 39,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 39,
            'name' => 'roles.catalogs',
        ], [
            'order' => 40,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 42,
            'name' => 'roles.special-texts',
        ], [
            'order' => 41,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 50,
            'name' => 'roles.mails-confirm-invitation',
        ], [
            'order' => 42,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 43,
            'name' => 'roles.user_credits',
        ], [
            'order' => 42,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 44,
            'name' => 'roles.user_memberships',
        ], [
            'order' => 43,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 48,
            'name' => 'roles.products',
        ], [
            'order' => 47,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 45,
            'name' => 'roles.subscriptions',
        ], [
            'order' => 44,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 46,
            'name' => 'roles.mails-confirm-subscription',
        ], [
            'order' => 45,
        ]);
        AbilityGroup::updateOrCreate([
            'id'   => 47,
            'name' => 'roles.mails-error-subscription',
        ], [
            'order' => 46,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 49,
            'name' => 'roles.credits-company-group',
        ], [
            'order' => 48,
        ]);

        AbilityGroup::updateOrCreate([
            'id'   => 51,
            'name' => 'roles.gympass-group',
        ], [
            'order' => 50,
        ]);
    }
}
