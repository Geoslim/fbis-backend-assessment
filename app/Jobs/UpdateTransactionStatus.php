<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Services\CommissionService;
use App\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateTransactionStatus implements ShouldQueue
{
    use Queueable;

    private WalletService $walletService;
    private CommissionService $commissionService;

    /**
     * Create a new job instance.
     */
    public function __construct(public $transaction, public $partnerService)
    {
        $this->walletService = new WalletService();
        $this->commissionService = new CommissionService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = $this->partnerService->fetchTransactionStatus($this->transaction->reference);
            $wallet = $this->walletService->fetchWallet($this->transaction->user_id);

            Log::debug("Transaction re-query", [$this->transaction, $response]);

            if (
                ($this->transaction->status == Status::PENDING) &&
                (isset($response['response']['status']) && $response['response']['status'] == '200') ||
                (isset($response['response']['statusCode']) && $response['response']['statusCode'] == '0')
            ) {
                // calculate commission and update transaction accordingly
                $commission = $this->commissionService->calculateCommission($this->transaction->amount);
                $this->transaction->update([
                    'response' => $response,
                    'status' => Status::SUCCESS->value,
                    'commission' => $commission
                ]);

                $this->walletService->processCredit($wallet, $commission);
                // notify user of successful transaction and commission
            } else {
                // handle refund and mark transaction as failed'
                $this->transaction->update([
                    'response' => $response,
                    'status' => Status::FAILED->value
                ]);
                $this->walletService->processCredit($wallet, $this->transaction->amount);
                // notify user
            }

        } catch (\Exception $e) {
            Log::error('error fetching transaction status', [$e]);
        }
    }
}
