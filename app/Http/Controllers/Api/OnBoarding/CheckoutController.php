<?php

namespace App\Http\Controllers\Api\OnBoarding;

use App\Models\Leads;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Plan;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function plans(): JsonResponse
    {
        Stripe::setApiKey(config('stripe.secret'));

        $plans = Plan::all(['product' => config('stripe.product')]);

        /*
        $data = [
            'object'   => 'list',
            'data'     => [
                (object) [
                    'id'                => 'plan_FwKLnAOgQ7YACO',
                    'object'            => 'plan',
                    'active'            => true,
                    'aggregate_usage'   => null,
                    'amount'            => 400000,
                    'amount_decimal'    => '400000',
                    'billing_scheme'    => 'per_unit',
                    'created'           => 1570337360,
                    'currency'          => 'mxn',
                    'interval'          => 'month',
                    'interval_count'    => 1,
                    'livemode'          => false,
                    'metadata'          => (object) [
                        'details' => 'varios tipos de clase,2 ubicaciones',
                    ],
                    'nickname'          => 'Premium',
                    'product'           => 'prod_FvYSY4cqD4kbr8',
                    'tiers'             => null,
                    'tiers_mode'        => null,
                    'transform_usage'   => null,
                    'trial_period_days' => 7,
                    'usage_type'        => 'licensed',
                ],
                (object) [
                    'id'                => 'plan_FwKK93aUUs44ox',
                    'object'            => 'plan',
                    'active'            => true,
                    'aggregate_usage'   => null,
                    'amount'            => 210000,
                    'amount_decimal'    => '210000',
                    'billing_scheme'    => 'per_unit',
                    'created'           => 1570337334,
                    'currency'          => 'mxn',
                    'interval'          => 'month',
                    'interval_count'    => 1,
                    'livemode'          => false,
                    'metadata'          => (object) [
                        'details' => 'varios tipos de clase,1 ubicación',
                    ],
                    'nickname'          => 'Senior',
                    'product'           => 'prod_FvYSY4cqD4kbr8',
                    'tiers'             => null,
                    'tiers_mode'        => null,
                    'transform_usage'   => null,
                    'trial_period_days' => 7,
                    'usage_type'        => 'licensed',
                ],
                (object) [
                    'id'                => 'plan_FwKKUpRczP2ckC',
                    'object'            => 'plan',
                    'active'            => true,
                    'aggregate_usage'   => null,
                    'amount'            => 160000,
                    'amount_decimal'    => '160000',
                    'billing_scheme'    => 'per_unit',
                    'created'           => 1570337308,
                    'currency'          => 'mxn',
                    'interval'          => 'month',
                    'interval_count'    => 1,
                    'livemode'          => false,
                    'metadata'          => (object) [
                        'details' => '1 tipo de clase,1 ubicación',
                    ],
                    'nickname'          => 'Junior',
                    'product'           => 'prod_FvYSY4cqD4kbr8',
                    'tiers'             => null,
                    'tiers_mode'        => null,
                    'transform_usage'   => null,
                    'trial_period_days' => 7,
                    'usage_type'        => 'licensed',

                ],
            ],
            'has_more' => false,
            'url'      => '/v1/plans',
        ];

        $plans = json_decode(json_encode($data), true);
        */

        return response()->json($plans['data']);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscription(Request $request): ?JsonResponse
    {
        $leads = Leads::where('username', $request->name)->first();

        if (!isset($leads)) {
            return abort(403, 'Usuario no registrado!');
        }

        // User's password was checked in the previous step, that's why skipping here!!!
        return response()->json([
            'token'   => $leads->createToken('Personal Access Token')->accessToken,
            'message' => 'success',
        ]);
    }
}
