<?php

namespace Tests\Feature;

use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase, ArraySubsetAsserts;


    /** @test */
    public function google_purchase_done_successfully()
    {
        $data = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '1234568',
            'language' => 'english',
            'operating_system' => 'android'
        ];

        $deviceResponse = $this->json('POST', route('app.register'), $data);
        $token = $deviceResponse->json()['data']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json'
        ])->json('POST', route('app.purchase'), ['receipt' => '1231']);

        $response->assertStatus(200);
        $this->assertArraySubset(['message' => __('Purchase verified successfully')], $response->json());
        $this->assertDatabaseCount("subscriptions", 1);
    }


    /** @test */
    public function apple_purchase_done_successfully()
    {
        $data = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '1234568',
            'language' => 'english',
            'operating_system' => 'ios'
        ];

        $deviceResponse = $this->json('POST', route('app.register'), $data);
        $token = $deviceResponse->json()['data']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json'
        ])->json('POST', route('app.purchase'), ['receipt' => '1231']);

        $response->assertStatus(200);
        $this->assertArraySubset(['message' => __('Purchase verified successfully')], $response->json());
        $this->assertDatabaseCount("subscriptions", 1);
    }

    /** @test */
    public function apple_purchase_not_verified_receipt()
    {
        $data = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '1234568',
            'language' => 'english',
            'operating_system' => 'ios'
        ];

        $deviceResponse = $this->json('POST', route('app.register'), $data);
        $token = $deviceResponse->json()['data']['token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json'
        ])->json('POST', route('app.purchase'), ['receipt' => '1232']);

        $response->assertStatus(406);
        $this->assertArraySubset(['message' => __('The receipt you have sent is not verified!')], $response->json());
        $this->assertDatabaseCount("subscriptions", 0);
    }

    /** @test */
    public function apple_purchase_already_verified_receipt_doesnt_add_subscription_row()
    {
        $data = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '1234568',
            'language' => 'english',
            'operating_system' => 'ios'
        ];

        $deviceResponse = $this->json('POST', route('app.register'), $data);
        $token = $deviceResponse->json()['data']['token'];

        $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json'
        ])->json('POST', route('app.purchase'), ['receipt' => '1233']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json'
        ])->json('POST', route('app.purchase'), ['receipt' => '1233']);

        $response->assertStatus(200);
        $this->assertArraySubset(['message' => __('The sent receipt is already verified!')], $response->json());
        $this->assertDatabaseCount("subscriptions", 1);
    }
}
