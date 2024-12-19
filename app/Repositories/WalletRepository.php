<?php
namespace App\Repositories;

use App\Models\Wallet;
use App\Models\User;
use App\Models\Transaction;
use JWTAuth;

class WalletRepository
{
    public function __construct(Wallet $wallet, Transaction $transact){
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->wallet = $wallet;
        $this->transact = $transact;
    }

    public function findByUserIdForUpdate(int $wallet_id)
    {
        return $this->user->wallet()->where('id', $wallet_id)->lockForUpdate()->firstOrFail();
    }

    public function updateRecipientBalance($wallet, int $userId, float $newBalance): bool
    {
        $recipient_wallet = $this->wallet->whereUserId($userId)->whereCurrencyType($wallet->currency_type)->first();

        $recipient_wallet->balance = $recipient_wallet->balance + $newBalance;
        return $recipient_wallet->save();
    }

    public function updateSenderBalance($wallet, float $newBalance): bool
    {
        $sender_wallet = $this->user->wallet()->whereId($wallet->id)->first();

        $sender_wallet->balance = $newBalance;
        return $sender_wallet->save();
    }

    public function createTransaction($wallet, int $userId, float $newBalance): bool
    {
        $recipient_wallet = $this->wallet->whereUserId($userId)->whereCurrencyType($wallet->currency_type)->first();

        $this->transact->sender_wallet_id = $wallet->id;
        $this->transact->reciepient_wallet_id = $recipient_wallet->id;
        $this->transact->amount = $newBalance;
        return $this->transact->save();
    }
}
