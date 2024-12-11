<?php

namespace App\Services;

use App\Enums\Status;
use App\Enums\VendingPartners;
use App\Exceptions\WalletException;
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
        $this->partner = config('partner.default_vending_partner');
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

           $wallet = $this->walletService->walletQuery($user->id)->first();
           $this->walletService->processDebit($wallet, $data['amount']);

            $data['reference'] = $this->transactionService->generateReference();
            $data['partner'] = $this->partner;
            $data['type'] = 'airtime vending';
            $data['description'] = 'airtime topup of NGN' . $data['amount'] . ' to' . $data['recipient'];

           $transaction = $this->transactionService
               ->recordTransaction($wallet->id, $user->id, $data, Status::PENDING->value);

           DB::commit();

            $response = $this->vendingServicePartner->vendAirtime($data, $user);

            if (isset($response['error'])) {
                return $response;
            }

            $transaction->update([
                'response' => $response['response'],
                'status' => Status::SUCCESS->value,
                'commission' => $this->commissionService->calculateCommission($data['amount'])
            ]);

            $response['transaction'] = $transaction->refresh();

            return $response;
        } catch (\Exception $e) {
            DB::rollback();
           throw $e;
        }

    }
}
