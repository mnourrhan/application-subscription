<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services;

use App\Repositories\SubscriptionsRepository;
use Carbon\Carbon;
use Illuminate\Http\Response;

/**
 * Description of CheckingSubscriptionStatusService
 *
 * @author Nourhan
 */
class CheckingSubscriptionStatusService
{
    /**
     * @var SubscriptionsRepository
     */
    protected $repository;

    /**
     * CheckingSubscriptionStatusService constructor.
     * @param SubscriptionsRepository $repository
     */
    public function __construct(SubscriptionsRepository $repository) {
        $this->repository = $repository;
    }


    public function execute()
    {
        $device = auth()->user();
        $currentSubscription = $this->repository->getCurrentSubscription($device);
        if($currentSubscription){
            if(Carbon::now()->greaterThanOrEqualTo(Carbon::parse($currentSubscription['expiry_date'])))
                return successResponse(__('The subscription is active!'), ['active' => true]);
            else
                return successResponse(__('The subscription is inactive!'), ['active' => false]);
        }
        return failureResponse(Response::HTTP_NOT_ACCEPTABLE, __('No subscriptions existing for this app!'));
    }
}
