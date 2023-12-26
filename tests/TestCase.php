<?php

namespace Tests;

use Database\Seeders\PopulatePlans;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use CreatesApplication;
    protected $seeder = PopulatePlans::class;
}
