<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Repositories;

use App\Models\Subscription;

/**
 * Description of SubscriptionsRepository
 *
 * @author Nourhan
 */
class SubscriptionsRepository extends BaseRepository
{
    /**
     * SubscriptionsRepository constructor.
     * @param Subscription $model
     */
    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }

    public function getCurrentSubscription($device){
        return $this->where('device_id', $device->id)
                ->orderBy('expiry_date', 'desc')->first();
    }
}
