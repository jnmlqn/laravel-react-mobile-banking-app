<?php

namespace Tests\Unit\Services;

use App\Models\Bank;
use App\Repositories\BankRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BankRepositoryTest extends TestCase
{
    use WithFaker;

    /**
     * @var  Bank
     */
    private Bank $bank;

    /**
     * @var  BankRepository
     */
    private BankRepository $bankRepository;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->bank = $this->mock(Bank::class);
        $this->bankRepository = new BankRepository($this->bank);
    }

    /**
     * @return void
     */
    public function testIndex(): void
    {
        $banks = new Collection([
            [
                'id' => $this->faker->randomDigitNotNull(),
                'provider' => 'pesonet',
                'bank' => 'BDO'
            ],
            [
                'id' => $this->faker->randomDigitNotNull(),
                'provider' => 'pesonet',
                'bank' => 'BPI'
            ],
            [
                'id' => $this->faker->randomDigitNotNull(),
                'provider' => 'pesonet',
                'bank' => 'Maya'
            ],
            [
                'id' => $this->faker->randomDigitNotNull(),
                'provider' => 'pesonet',
                'bank' => 'GCash'
            ],
            [
                'id' => $this->faker->randomDigitNotNull(),
                'provider' => 'pesonet',
                'bank' => 'RCBC'
            ],
            [
                'id' => $this->faker->randomDigitNotNull(),
                'provider' => 'pesonet',
                'bank' => 'Maybank'
            ],
        ]);

        $this->bank
            ->shouldReceive('get')
            ->once()
            ->andReturn($banks);

        $actual = $this->bankRepository->index();
        $this->assertSame($banks->toArray(), $actual);
        $this->assertCount(6, $actual);
    }
}
