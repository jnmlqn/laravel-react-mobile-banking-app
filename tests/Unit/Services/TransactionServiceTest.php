<?php

namespace Tests\Unit\Services;

use App\Services\TransactionService;
use App\Repositories\TransactionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use WithFaker;

    /**
     * @var  TransactionService
     */
    private TransactionService $transactionService;

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
        $this->transactionRepository = $this->mock(TransactionRepository::class);
        $this->transactionService = new TransactionService($this->transactionRepository);
    }

    /**
     * @return void
     */
    public function testTransferToUser(): void
    {
        $userId = $this->faker->randomDigitNotNull();
        $data = [
            'type' => 'send',
            'mode' => 'email',
            'email' => $this->faker->email(),
            'amount' => $this->faker()->randomDigitNotNull(),
        ];

        $this->transactionRepository
            ->shouldReceive('transfer')
            ->once()
            ->with($userId, $data)
            ->andReturn(true);

        $actual = $this->transactionService->transfer($userId, $data);
        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    public function testTransferToBank(): void
    {
        $userId = $this->faker->randomDigitNotNull();
        $data = [
            'type' => 'send',
            'mode' => 'bank',
            'bank' => $this->faker->randomDigitNotNull(),
            'amount' => $this->faker()->randomDigitNotNull(),
        ];

        $this->transactionRepository
            ->shouldReceive('transfer')
            ->once()
            ->with($userId, $data)
            ->andReturn(true);

        $actual = $this->transactionService->transfer($userId, $data);
        $this->assertTrue($actual);
    }

    /**
     * @return void
     */
    public function testHistory(): void
    {
        $userId = $this->faker->randomDigitNotNull();
        $histories = new Collection([
            (object) [
                'id' => $this->faker->randomDigitNotNull(),
                'type' => 'send',
                'mode' => 'bank',
                'bank' => (object) [
                    'provider' => 'instapay',
                    'bank' => 'BDO'
                ],
                'email' => $this->faker->email(),
                'amount' => $this->faker->randomDigitNotNull(),
                'last_current_balance' => $this->faker->randomDigitNotNull(),
                'description' => 'Send money to bank',
            ],
            (object) [
                'id' => $this->faker->randomDigitNotNull(),
                'type' => 'send',
                'mode' => 'bank',
                'bank' => (object) [
                    'provider' => 'instapay',
                    'bank' => 'BDO'
                ],
                'email' => $this->faker->email(),
                'amount' => $this->faker->randomDigitNotNull(),
                'last_current_balance' => $this->faker->randomDigitNotNull(),
                'description' => 'Send money to bank',
            ],
            (object) [
                'id' => $this->faker->randomDigitNotNull(),
                'type' => 'send',
                'mode' => 'bank',
                'bank' => (object) [
                    'provider' => 'instapay',
                    'bank' => 'BDO'
                ],
                'email' => $this->faker->email(),
                'amount' => $this->faker->randomDigitNotNull(),
                'last_current_balance' => $this->faker->randomDigitNotNull(),
                'description' => 'Send money to bank',
            ]
        ]);

        $expected = $histories->map(function ($history) {
            return [
                'id' => $history->id,
                'type' => $history->type,
                'mode' => $history->mode,
                'provider' => ucfirst($history->bank?->provider),
                'bank' => $history->bank?->bank,
                'email' => $history->email,
                'amount' => number_format(floatval($history->amount), 2),
                'last_current_balance' => number_format(floatval($history->last_current_balance), 2),
                'description' => $history->description,
            ];
        })->toArray();

        $this->transactionRepository
            ->shouldReceive('history')
            ->once()
            ->with($userId)
            ->andReturn($histories);

        $actual = $this->transactionService->history($userId);
        $this->assertSame($expected, $actual);
        $this->assertCount(3, $actual);
    }
}
