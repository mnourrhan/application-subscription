<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Validators\PurchaseValidator;
use App\Jobs\HandlePurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class PurchaseController extends Controller
{

    public function __invoke(Request $request, PurchaseValidator $validator) {
        try {
            if ($validator->validate($request)->failed()) {
                return failureResponse(Response::HTTP_UNPROCESSABLE_ENTITY, __('Invalid Request!'), $validator->errors());
            }
            $device = auth()->user();
            $receipt = $request->get('receipt');

            //queuing purchase verifying to make the operation done in the background
            Queue::push(new HandlePurchaseRequest($device, $receipt));
            return successResponse(__('Purchase verification is under processing, you can check your subscription after a while'));
        }catch (\Exception $ex){
            Log::info($ex);
            return failureResponse(Response::HTTP_INTERNAL_SERVER_ERROR, __('Server error occurred. Please try again later!'));
        }
    }
}
