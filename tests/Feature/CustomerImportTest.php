<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CustomerImportTest extends TestCase
{
    public function test_successful_import()
    {
        // Mock the randomuser.me API response
        Http::fake([
            'https://randomuser.me/api*' => Http::response([
                'results' => [
                    [
                        'gender' => 'male',
                        'name' => ['first' => 'John', 'last' => 'Doe'],
                        'email' => 'john.doe@example.com',
                        'login' => ['username' => 'johndoe', 'password' => 'secret'],
                        'location' => ['city' => 'Sydney', 'country' => 'Australia'],
                        'phone' => '1234567890',
                    ],
                ]
            ], 200)
        ]);

        // Run the import command
        $this->artisan('customers:import --limit=1 --chunk=1 --error=0 --nat=au')
            ->assertExitCode(0);
    }

    public function test_import_with_invalid_data()
    {
        // Simulate API with missing required fields
        Http::fake([
            'https://randomuser.me/api*' => Http::response([
                'results' => [
                    [
                        'gender' => 'male',
                        'email' => '', // Invalid email
                        'name' => [],  // Missing name data
                        'login' => ['username' => 'johndoe', 'password' => 'secret'],
                        'location' => ['city' => 'Sydney', 'country' => 'Australia'],
                        'phone' => '1234567890',
                    ],
                ]
            ], 200)
        ]);

        // Run the import command and expect success output (even if some records fail)
        $this->artisan('customers:import --limit=1 --chunk=1 --error=1')
             ->assertExitCode(0);
    }
}
