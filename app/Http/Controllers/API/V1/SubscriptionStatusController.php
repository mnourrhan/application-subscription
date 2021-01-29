<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\CheckingSubscriptionStatusService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SubscriptionStatusController extends Controller
{

    public function __invoke(Request $request, CheckingSubscriptionStatusService $service) {
        try {
            return $service->execute();
        }catch (\Exception $ex){
            Log::info($ex);
            return failureResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Server error occurred. Please try again later!'));
        }
    }
}
