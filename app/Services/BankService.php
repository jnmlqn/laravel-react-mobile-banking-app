<?php

namespace App\Services;

use App\Repositories\BankRepository;

class BankService
{
    /**
     * @var BankRepository
     */
    private BankRepository $bankRepository;

    /**
     * @param  BankRepository  $bankRepository
     * 
     * @return void
     */
    public function __construct(BankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    /**
     * @return array
     */
    public function index(): array
    {
        return $this->bankRepository->index();
    }
}
