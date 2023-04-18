<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponser;

    /**
     * @var UserService
     */
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * 
     * @return Response
     */
    public function store(Request $request): Response
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:2|max:255',
                'email' => 'required|unique:users|min:5|max:255',
                'password' => 'required|min:8|max:255',
            ]
        );

        if ($validator->fails()) {
            return $this->apiResponse(
                'Validation error',
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $validator->errors()->getMessages()
            );
        }

        try {
            $this->userService->register(
                $name,
                $email,
                $password
            );

            $token = auth()->attempt([
                'email' => $email,
                'password' => $password
            ]);

            if (!$token) {
                return $this->apiResponse(
                    'Internal server error',
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }

            return $this->apiResponse(
                'Registration successful',
                Response::HTTP_OK,
                [
                    'name' => auth()->user()->name,
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            );
        } catch (\Exception $e) {
            return $this->apiResponse(
                'Internal server error',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }
}
