<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Feature tests render Blade with @vite but CI never builds the assets,
        // so stub the Vite manifest lookup instead of shipping a build to CI.
        $this->withoutVite();
    }
}
