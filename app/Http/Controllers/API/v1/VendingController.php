<?php

namespace App\Http\Controllers\API\v1;

use App\Exceptions\WalletException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\VendingAirtimeRequest;
use App\Http\Resources\TransactionResource;
use App\Services\VendingService;
use App\Traits\JsonResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class VendingController extends Controller
{
    use JsonResponseTrait;

    public function __construct(public VendingService $vendingService)
    {
    }

    /**
     * @param VendingAirtimeRequest $request
     * @return JsonResponse
     */
    public function vendAirtime(VendingAirtimeRequest $request)
    {
        try {
            $user = $request->user();
            $data = $request->validated();
            $response = $this->vendingService->vendAirtime($data, $user);
            if (isset($response['error'])) {
                return $this->error($response['error']);
            }
            Log::debug('airtime vending success response:: ', [$response]);
            return $this->successResponse(TransactionResource::make($response['transaction']));
        } catch (WalletException|InvalidArgumentException $e) {
            Log::error('airtime vending error:: ', [$e]);
            return $this->error($e->getMessage());
        } catch (Exception $e) {
            Log::error('airtime vending error:: ', [$e]);
            return $this->error('Unable to process airtime vending at this time. Kindly try again shortly.');
        }
    }
}
