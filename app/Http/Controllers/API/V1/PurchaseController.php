<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Validators\PurchaseValidator;
use App\Services\PurchaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurchaseController extends Controller
{

    public function __invoke(Request $request, PurchaseValidator $validator,
                             PurchaseService $service) {
        if ($validator->validate($request)->failed()) {
            return failureResponse(Response::HTTP_UNPROCESSABLE_ENTITY, __('Invalid Request!'), $validator->errors());
        }
        return $service->execute($request);
    }
}
