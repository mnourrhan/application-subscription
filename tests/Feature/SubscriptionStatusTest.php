<?php

namespace Tests\Feature;

use App\Models\Device;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SubscriptionStatusTest extends TestCase
{
    use RefreshDatabase, ArraySubsetAsserts;


    /** @test */
    public function subscription_status_checked_successfully()
    {
        $data = [
            'uid' => '5213-5423-hj5uj-5jhg',
            'app_id' => '1234568',
            'language' => 'english',
            'operating_system' => 'android'
        ];

        $device = factory(Device::class)->create($data);
        $deviceResponse = $this->json('POST', route('app.register'), $data);
        $token = $deviceResponse->json()['data']['token'];

        $subscription = factory(Subscription::class)
            ->create([
                    'device_id' => $device->id,
                    'receipt' => '123',
                    'expiry_date' => Carbon::now()->addDay()
            ]);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            'Accept' => 'application/json'
        ])->json('GET', route('app.check.subscription'));

        $response->assertStatus(200);
        $response->assertSee('active');
    }


    /** @test */
    public function no_subscription_found()
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
        ])->json('GET', route('app.check.subscription'));

        $response->assertStatus(406);
        $this->assertArraySubset(['message' => __('No subscriptions existing for this app!')], $response->json());
    }

    /** @test */
    public function not_authorized_device_cant_check_status()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('GET', route('app.check.subscription'));

        $response->assertStatus(401);
        $this->assertArraySubset(['message' => 'Unauthenticated.'], $response->json());
    }
}
