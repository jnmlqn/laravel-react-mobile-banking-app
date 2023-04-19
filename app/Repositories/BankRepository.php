<?php

namespace App\Repositories;

use App\Models\Bank;

class BankRepository
{
    /**
     * @var Bank
     */
    private Bank $bank;

    /**
     * @param  Bank  $bank
     * 
     * @return void
     */
    public function __construct(Bank $bank)
    {
        $this->bank = $bank;
    }

    /**
     * @return array
     */
    public function index(): array
    {
        return $this->bank->get()->toArray();
    }
}
