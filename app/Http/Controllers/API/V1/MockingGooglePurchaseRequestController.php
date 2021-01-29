<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\MockingPurchaseRequestService;
use Illuminate\Http\Request;

class MockingGooglePurchaseRequestController extends Controller
{

    public function __invoke(Request $request, MockingPurchaseRequestService $service) {
        return $service->execute($request);
    }
}
