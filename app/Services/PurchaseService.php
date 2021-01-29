<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services;

use App\Enums\OperatingSystemsEnum;
use App\Repositories\SubscriptionsRepository;
use Carbon\Carbon;
use Illuminate\Http\Response;

/**
 * Description of PurchaseService
 *
 * @author Nourhan
 */
class PurchaseService
{
    /**
     * @var GuzzleHttpClient
     */
    protected $guzzleHttpClient;

    /**
     * @var SubscriptionsRepository
     */
    protected $repository;

    /**
     * PurchaseService constructor.
     * @param GuzzleHttpClient $guzzleHttpClient
     */
    public function __construct(GuzzleHttpClient $guzzleHttpClient,
                                SubscriptionsRepository $repository) {
        $this->guzzleHttpClient = $guzzleHttpClient;
        $this->repository = $repository;
    }


    public function execute($request)
    {
        $device = auth()->user();
        $receipt = $request->get('receipt');
        $isSubscriptionExist = $this->isSubscriptionExist($device, $receipt);
        if($isSubscriptionExist)
            return successResponse(__('The sent receipt is already verified!'));

        $result = $this->handleVerifyingPurchaseRequest($receipt, $device);

        if($result['status']){
            $data = $result['response'];
            if($data['status']){  //verified
                $this->createSubscription($data, $receipt, $device);
                return successResponse(__('Purchase verified successfully'));
            }
            // not verified
            return failureResponse(Response::HTTP_NOT_ACCEPTABLE, __('The receipt you have sent is not verified!'));
        }
        return failureResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Server error occurred. Please try again later!'));

    }

    private function handleVerifyingPurchaseRequest($receipt, $device){
        $username = config('mocking-APIs.GOOGLE_PURCHASE_USERNAME');
        $password = config('mocking-APIs.GOOGLE_PURCHASE_PASSWORD');
        $route = route('verify.google.app');

        if($device['operating_system'] == OperatingSystemsEnum::IOS){
            $username = config('mocking-APIs.APPLE_PURCHASE_USERNAME');
            $password = config('mocking-APIs.APPLE_PURCHASE_PASSWORD');
            $route = route('verify.apple.app');
        }

        return  $this->guzzleHttpClient->get(
            [
                'Content-Type' => 'application/json',
                'username' => $username,
                'password' => $password
            ], $route,
            [
                'query' => [
                    'receipt' => $receipt
                ],
            ]
        );
    }

    private function createSubscription($data, $receipt, $device){
        $expiry_date = $data['expiry_date'];
        // apple sent the date -6 UTC, so we need to convert it to UTC
        if($device['operating_system'] == OperatingSystemsEnum::IOS)
            $expiry_date = Carbon::parse($data['expiry_date'])->addHours(6);

        $attributes = ['receipt' => $receipt, 'device_id' => $device->id];
        $data = ['device_id' => $device->id, 'expiry_date' => $expiry_date, 'receipt' => $receipt ];
        $this->repository->updateOrCreate($attributes, $data);
    }

    private function isSubscriptionExist($device, $receipt){
        $subscription = $this->repository->where('device_id', $device->id)->where('receipt', $receipt)->first();
        if($subscription)
            return true;
        return false;
    }
}
