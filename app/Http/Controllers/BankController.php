<?php

namespace App\Http\Controllers;

use App\Services\BankService;
use App\Traits\ApiResponser;
use Symfony\Component\HttpFoundation\Response;

class BankController extends Controller
{
    use ApiResponser;

    /**
     * @var BankService
     */
    private BankService $bankService;

    /**
     * @param  BankService  $bankService
     */
    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    public function index(): Response
    {
        $banks = $this->bankService->index();

        return $this->apiResponse(
            'Banks were successfully fetched',
            Response::HTTP_OK,
            $banks
        );
    }
}
