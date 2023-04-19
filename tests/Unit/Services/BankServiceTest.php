<?php

namespace Tests\Unit\Services;

use App\Services\BankService;
use App\Repositories\BankRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BankServiceTest extends TestCase
{
    use WithFaker;

    /**
     * @var  BankService
     */
    private BankService $bankService;

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
        $this->bankRepository = $this->mock(BankRepository::class);
        $this->bankService = new BankService($this->bankRepository);
    }

    /**
     * @return void
     */
    public function testIndex(): void
    {
        $banks = [
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
        ];

        $this->bankRepository
            ->shouldReceive('index')
            ->once()
            ->andReturn($banks);

        $actual = $this->bankService->index();
        $this->assertSame($banks, $actual);
        $this->assertCount(6, $actual);
    }
}
