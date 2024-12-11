<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\VendingAirtimeRequest;
use App\Services\VendingService;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendingController extends Controller
{
    use JsonResponseTrait;

    public function __construct(public VendingService $vendingService)
    {
    }

    public function vendAirtime(VendingAirtimeRequest $request)
    {
        try {
            $user = $request->user();
            $data = $request->validated();
            $response = $this->vendingService->vendAirtime($data, $user);
            if (isset($response['error'])) {
                return $this->error($response['error']);
            }
            return $this->successResponse($response['response']);
        } catch (\Exception $e) {
            Log::error('airtime vending error:: ', [$e]);
            return $this->error('Unable to process airtime vending at this time. Kindly try again shortly.');
        }
    }
}
