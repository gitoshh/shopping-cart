<?php

use Illuminate\Support\Facades\Artisan;

class BaseTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }

    public function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    public function testDatabase(): void
    {
        // Test that migrations have been run
        $this->seeInDatabase('users', ['email' => 'test@gmail.com']);
    }
}
