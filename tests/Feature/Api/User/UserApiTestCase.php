<?php

namespace Tests\Feature\Api\User;

use Tests\TestCase;
use App\Http\Middleware\AuthCompany;

abstract class UserApiTestCase extends TestCase
{
    /**
     * No AuthCompany middleware
     */
    protected function setUp()
    {
        parent::setUp();

        $this->withoutMiddleware([
            AuthCompany::class,
        ]);
    }
}
