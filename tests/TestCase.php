<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use MakesHttpRequests;

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Set the base URL globally for all tests
        $this->baseUrl = 'http://localhost/tms/public';
    }
}