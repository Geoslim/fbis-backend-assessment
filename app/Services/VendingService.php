<?php

namespace App\Services;

use App\Enums\Status;
use App\Enums\VendingPartners;
use App\Exceptions\WalletException;
use App\Jobs\UpdateTransactionStatus;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Partners\Bap;
use App\Services\Partners\Shaggo;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;

class VendingService
{
    private Bap|Shaggo $vendingServicePartner;

    private string $partner;

    public function __construct(
        public WalletService $walletService,
        public TransactionService $transactionService,
        public CommissionService $commissionService
    ) {
        $this->partner = config('partner.default');
        $this->vendingServicePartner = match ($this->partner) {
            VendingPartners::BAP->value => new Bap(),
            VendingPartners::SHAGGO->value => new Shaggo(),
            default => throw new \InvalidArgumentException()
        };
    }

    /**
     * @param array $data
     * @param Authenticatable|User $user
     * @return array
     * @throws WalletException
     */
    public function vendAirtime(array $data, Authenticatable|User $user): array
    {
        try {
            DB::beginTransaction();

            $wallet = $this->walletService->fetchWallet($user->id);
            $this->walletService->processDebit($wallet, $data['amount']);

            $transactionData = $this->prepareTransactionData($data);

            $transaction = $this->transactionService
               ->recordTransaction($wallet->id, $user->id, $transactionData, Status::PENDING->value);

            DB::commit();

            $response = $this->vendingServicePartner->vendAirtime($transactionData);

            $transaction->update(['response' => $response]);

            UpdateTransactionStatus::dispatch($transaction, $this->vendingServicePartner)->delay(10);
            return $this->handleTransactionResponse($response, $transaction);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareTransactionData(array $data): array
    {
        return [
            'reference' => $this->transactionService->generateReference(),
            'partner' => $this->partner,
            'type' => 'airtime vending',
            'description' => 'airtime topup of NGN' . $data['amount'] . ' to ' . $data['recipient'],
            'amount' => $data['amount'],
            'network' => $data['network'],
            'recipient' => $data['recipient']
        ];
    }

    /**
     * @param array $response
     * @param Transaction $transaction
     * @return array
     */
    private function handleTransactionResponse(array $response, Transaction $transaction): array
    {
        if (isset($response['error'])) {
            return $response;
        }

        $response['transaction'] = $transaction->refresh();
        return $response;
    }
}
