<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\MockingPurchaseRequestService;
use Illuminate\Http\Request;

class MockingApplePurchaseRequestController extends Controller
{

    /**
     * @param Request $request
     * @param MockingPurchaseRequestService $service
     * @return array|false[]
     */
    public function __invoke(Request $request, MockingPurchaseRequestService $service) {
        return $service->execute($request, 'ios');
    }
}
