<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Traits\ApiResponser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    use ApiResponser;

    /**
     * @var TransactionService
     */
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param  Request  $request
     * 
     * @return Response
     */
    public function transfer(Request $request): Response
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'type' => 'required',
                    'mode' => 'required',
                    'bank' => 'required_if:mode,bank',
                    'email' => 'required_if:mode,email',
                    'amount' => 'required|numeric|min:1',
                    'description' => 'required'
                ]
            );

            if ($validator->fails()) {
                return $this->apiResponse(
                    'Validation error',
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    $validator->errors()->getMessages()
                );
            }

            $userId = auth()->user()->id;
            $transaction = $this->transactionService->transfer(
                $userId,
                $request->toArray()
            );
        } catch (ModelNotFoundException $e) {
            return $this->apiResponse('User account not found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->apiResponse(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->apiResponse('Transaction completed', Response::HTTP_CREATED);
    }

    /**
     * @return Response
     */
    public function history(): Response
    {
        $userId = auth()->user()->id;
        $histories = $this->transactionService->history($userId);

        return $this->apiResponse('Transaction histories were successfully fetched', Response::HTTP_OK, $histories);
    }
}
