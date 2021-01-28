<?php

namespace Tests\Feature;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceRegisterTest extends TestCase
{
    use RefreshDatabase, ArraySubsetAsserts;


    /** @test */
    public function app_can_successfully_register()
    {
        $data = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '1234568',
            'language' => 'english',
            'operating_system' => 'ios'
        ];

        $response = $this->json('POST', route('app.register'), $data);
        $response->assertStatus(200);
        $this->assertArraySubset(['message' => __('Registered successfully')], $response->json());
        $this->assertArrayHasKey('data', $response->json());
        $response->assertSee('token');
        $this->assertDatabaseHas("devices", $data);
    }


    /** @test */
    public function same_app_register_twice()
    {
        $data = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '1234568',
            'language' => 'english',
            'operating_system' => 'ios'
        ];

        //register with the same app_id multiple times
        $this->json('POST', route('app.register'), $data);
        $response = $this->json('POST', route('app.register'), $data);
        $response->assertStatus(200);
        $this->assertDatabaseCount('devices', 1);
        $this->assertArraySubset(['message' => __('Registered successfully')], $response->json());
        $this->assertArrayHasKey('data', $response->json());
    }

    /** @test */
    public function app_register_with_invalid_data()
    {
        $data = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '',
            'language' => 'english',
            'operating_system' => 'ios'
        ];

        //register with empty app_id
        $response = $this->json('POST', route('app.register'), $data);

        $response->assertStatus(422);
        $this->assertArraySubset(['message' => __('The given data was invalid.')], $response->json());
        $response->assertJsonValidationErrors(["app_id"]);

    }


    /** @test */
    public function different_apps_register_same_device_different_tokens()
    {
        $app1 = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '1234568',
            'language' => 'english',
            'operating_system' => 'ios'
        ];

        //register with the same app_id multiple times
        $response1 = $this->json('POST', route('app.register'), $app1);

        $app2 = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '41236547',
            'language' => 'english',
            'operating_system' => 'ios'
        ];

        $response2 = $this->json('POST', route('app.register'), $app2);
        $this->assertNotEquals($response1['data']['token'], $response2['data']['token']);
    }

}
