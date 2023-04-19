<?php

namespace Tests\Unit\Services;

use Event;
use App\Events\LogActivityEvent;
use App\Models\Bank;
use App\Models\Transaction;
use App\Models\UserAccount;
use App\Repositories\TransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionRepositoryTest extends TestCase
{
    use WithFaker;

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
     * @var Bank
     */
    private Bank $bank;

    /**
     * @var  TransactionRepository
     */
    private TransactionRepository $transactionRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->transaction = $this->mock(Transaction::class);
        $this->userAccount = $this->mock(UserAccount::class);
        $this->bank = $this->mock(Bank::class);
        $this->transactionRepository = new TransactionRepository(
            $this->transaction,
            $this->userAccount,
            $this->bank
        );
    }

    /**
     * @return void
     */
    public function testTransfer(): void
    {
        $userId = $this->faker->randomDigitNotNull();
        $data = [
            'type' => 'send',
            'mode' => 'email',
            'email' => $this->faker->email(),
            'amount' => $this->faker()->randomDigitNotNull(),
        ];

        $userAccount = $this->mock(UserAccount::class);
        $userAccount
            ->shouldReceive('getAttribute')
            ->times(3)
            ->with('balance')
            ->andReturn(100000);

        $this->userAccount
            ->shouldReceive('where')
            ->once()
            ->with('user_id', $userId)
            ->andReturnSelf()
            ->shouldReceive('firstOrFail')
            ->once()
            ->andReturn($userAccount);

        $description = 'Send money to user - ' . $data['email'];
        $newBalance = floatval($userAccount->balance) - floatval($data['amount']);

        $transaction = new Transaction([
            'type' => $data['type'],
            'mode' => $data['mode'],
            'email' => $data['email'],
            'amount' => $data['amount'],
            'last_current_balance' => $newBalance,
            'description' => $description ,
            'user_id' => $userId,
        ]);

        $this->transaction
            ->shouldReceive('create')
            ->once()
            ->with([
                'type' => $data['type'],
                'mode' => $data['mode'],
                'bank_id' => $data['bank'] ?? null,
                'email' => $data['email'] ?? null,
                'amount' => $data['amount'],
                'last_current_balance' => $newBalance,
                'description' => $description ,
                'user_id' => $userId,
            ])
            ->andReturn($transaction);

        $userAccount
            ->shouldReceive('setAttribute')
            ->once()
            ->with('balance', $newBalance)
            ->andReturnSelf()
            ->shouldReceive('save')
            ->once()
            ->andReturn(true)
            ->shouldReceive('toArray')
            ->twice()
            ->andReturn(true);

        Event::fake();

        $actual = $this->transactionRepository->transfer($userId, $data);
        Event::assertDispatched(LogActivityEvent::class);
        $this->assertTrue($actual);
    }
}
