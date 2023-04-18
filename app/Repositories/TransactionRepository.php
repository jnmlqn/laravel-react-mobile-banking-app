<?php

namespace App\Repositories;

use App\Events\LogActivityEvent;
use App\Models\Transaction;
use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Collection;

class TransactionRepository
{
    /**
     * @var string
     */
    private const AUDIT_TRAIL_TYPE = 'transaction';

    /**
     * @var string
     */
    private const AUDIT_TRAIL_TRANSFER = 'money transfer';

    /**
     * @var Transaction
     */
    private Transaction $transaction;

    /**
     * @var UserAccount
     */
    private UserAccount $userAccount;

    /**
     * @param  Transaction  $transaction
     * @param  UserAccount  $userAccount
     * 
     * @return void
     */
    public function __construct(
        Transaction $transaction,
        UserAccount $userAccount
    ) {
        $this->transaction = $transaction;
        $this->userAccount = $userAccount;
    }

    /**
     * @param  int   $userId
     * @param  array $data
     * 
     * @return bool
     */
    public function transfer(
        int $userId,
        array $data
    ): bool {
        $userAccount = $this->userAccount
            ->where('user_id', $userId)
            ->firstOrFail();
        $oldData = clone $userAccount;

        if (floatval($data['amount']) > floatval($userAccount->balance)) {
            throw new \Exception('Insufficient balance', 1);
        }

        $newBalance = floatval($userAccount->balance) - floatval($data['amount']);

        Transaction::create([
            'type' => $data['type'],
            'mode' => $data['mode'],
            'bank_id' => $data['bank'] ?? null,
            'email' => $data['email'] ?? null,
            'amount' => $data['amount'],
            'last_current_balance' => $newBalance,
            'description' => $data['description'],
            'user_id' => $userId,
        ]);

        $userAccount->balance = $newBalance;
        $userAccount->save();

        event(
            (new LogActivityEvent)
            ->setType(self::AUDIT_TRAIL_TYPE)
            ->setDescription(self::AUDIT_TRAIL_TRANSFER)
            ->setOldData(json_encode($oldData->toArray()))
            ->setNewData(json_encode($userAccount->toArray()))
            ->setUserId($userId)
        );

        return true;
    }

    /**
     * @param  int  $userId
     * 
     * @return Collection
     */
    public function history(int $userId): Collection
    {
        $histories = $this->transaction
            ->where('user_id', $userId)
            ->get();

        return $histories;
    }
}
