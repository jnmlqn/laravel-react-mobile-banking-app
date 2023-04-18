<?php

namespace App\Services;

use App\Repositories\TransactionRepository;

class TransactionService
{
    /**
     * @var TransactionRepository
     */
    private TransactionRepository $transactionRepository;

    /**
     * @param  TransactionRepository  $transactionRepository
     * 
     * @return void
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
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
        return $this->transactionRepository->transfer(
            $userId,
            $data
        );
    }

    /**
     * @param  int  $userId
     * 
     * @return array
     */
    public function history(int $userId): array
    {
        $histories = $this->transactionRepository->history($userId);

        return $histories->map(function ($history) {
            return [
                'type' => $history->type,
                'mode' => $history->mode,
                'provider' => ucfirst($history->bank?->provider),
                'bank' => $history->bank?->bank,
                'email' => $history->email,
                'amount' => $history->amount,
                'last_current_balance' => $history->last_current_balance,
                'description' => $history->description,
            ];
        })->toArray();
    }
}
