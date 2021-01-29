<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Services;

use App\Enums\OperatingSystemsEnum;
use Carbon\Carbon;

/**
 * Description of MockingPurchaseRequestService
 *
 * @author Nourhan
 */
class MockingPurchaseRequestService
{

    /**
     * @param $request
     * @return array|false[]
     */
    public function execute($request, $os = 'android')
    {
        $receipt = $request->get('receipt');
        $last_char = substr($receipt, -1);
        if(is_numeric($last_char) && $last_char % 2 != 0){
            $random_number = rand(-20,20);
            $expiry_date = $expiry_date = Carbon::now()->addDays($random_number);
            if ($os == OperatingSystemsEnum::IOS)
                $expiry_date = $expiry_date->setTimezone('America/Belize')->format('Y-m-d H:i:s');
            else
                $expiry_date = $expiry_date->format('Y-m-d H:i:s');

            return [
                'status' => true,
                'expiry_date' => $expiry_date
            ];
        }
        return [
            'status' => false
        ];
    }
}
