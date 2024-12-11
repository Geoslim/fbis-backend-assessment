<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Services\CommissionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateTransactionStatus implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $transaction, public $partnerService)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = $this->partnerService->fetchTransactionStatus($this->transaction->reference);

            Log::debug("Transaction re-query", [$this->transaction, $response]);

            if (
                (isset($response['response']['status']) && $response['response']['status'] == '200') ||
                (isset($response['response']['statusCode']) && $response['response']['statusCode'] == '0')
            ) {
                $this->transaction->update([
                    'response' => $response,
                    'status' => Status::SUCCESS->value,
                    'commission' => (new CommissionService())->calculateCommission($this->transaction->amount)
                ]);
            } else {
                // handle refund and mark transaction as failed
            }

        } catch (\Exception $e) {
            Log::error('error fetching transaction status', [$e]);
        }
    }
}
