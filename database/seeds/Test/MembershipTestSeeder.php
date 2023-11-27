<?php

use \App\Models\Membership\Membership;
use \App\Models\Membership\MembershipCredits;
use Illuminate\Database\Seeder;

class MembershipTestSeeder extends Seeder
{

    public function run()
    {
        Membership::updateOrCreate([
            'name'              => 'Plan Mensual',
            'companies_id'      => 1,
            'brands_id'         => 1,
            'price'             => 2430,
            'status'            => 'active',
            'description'       => 'Horario Fijo',
            'short_description' => 'Limitado',
            'expiration_days'   => 30,

        ]);

        Membership::updateOrCreate([
            'name'              => 'Plan Semestral',
            'companies_id'      => 1,
            'brands_id'         => 1,
            'price'             => 17010,
            'status'            => 'active',
            'description'       => 'Horario Fijo',
            'short_description' => 'Limitado',
            'expiration_days'   => 60,

        ]);
        Membership::updateOrCreate([
            'name'              => 'Plan anual',
            'companies_id'      => 1,
            'brands_id'         => 1,
            'price'             => 28620,
            'status'            => 'active',
            'description'       => 'Horario Fijo',
            'short_description' => 'Limitado',
            'expiration_days'   => 365,

        ]);

        Membership::updateOrCreate([
            'name'              => 'Plan Mensual',
            'companies_id'      => 1,
            'brands_id'         => 1,
            'price'             => 3420,
            'status'            => 'active',
            'description'       => 'Horario Libre',
            'short_description' => 'Ilimitado',
            'expiration_days'   => 30,

        ]);

        Membership::updateOrCreate([
            'name'              => 'Plan Semestral',
            'companies_id'      => 1,
            'brands_id'         => 1,
            'price'             => 19990,
            'status'            => 'active',
            'description'       => 'Horario Libre',
            'short_description' => 'Ilimitado',
            'expiration_days'   => 60,

        ]);

        Membership::updateOrCreate([
            'name'              => 'Plan Anual',
            'companies_id'      => 1,
            'brands_id'         => 1,
            'price'             => 34200,
            'status'            => 'active',
            'description'       => 'Horario Libre',
            'short_description' => 'Ilimitado',
            'expiration_days'   => 365,

        ]);


        MembershipCredits::updateOrCreate([
            'memberships_id' => 1,
            'credits_id'     => 1,
        ]);


        MembershipCredits::updateOrCreate([
            'memberships_id' => 2,
            'credits_id'     => 1,
        ]);

        MembershipCredits::updateOrCreate([
            'memberships_id' => 3,
            'credits_id'     => 3,
        ]);

        MembershipCredits::updateOrCreate([
            'memberships_id' => 4,
            'credits_id'     => 3,
        ]);

        MembershipCredits::updateOrCreate([
            'memberships_id' => 5,
            'credits_id'     => 1,
        ]);

        MembershipCredits::updateOrCreate([
            'memberships_id' => 6,
            'credits_id'     => 2,
        ]);

    }
}
