<?php

namespace App\Http\Controllers;

use App\Events\LogActivityEvent;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponser;

    /**
     * @var string
     */
    private const AUDIT_TRAIL_TYPE = 'authentication';

    /**
     * @var string
     */
    private const AUDIT_TRAIL_LOGIN = 'login';

    /**
     * @var string
     */
    private const AUDIT_TRAIL_LOGOUT = 'logout';

    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->userService = $userService;
    }

    /**
     * @param  Request  $request
     * 
     * @return  Response
     */
    public function login(Request $request): Response
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->apiResponse(
                'Validation error',
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $validator->errors()->getMessages()
            );
        }

        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);

        if (!$token) {
            return $this->apiResponse(
                'Unauthorized',
                Response::HTTP_UNAUTHORIZED
            );
        }

        event(
            (new LogActivityEvent)
            ->setType(self::AUDIT_TRAIL_TYPE)
            ->setDescription(self::AUDIT_TRAIL_LOGIN)
            ->setOldData(null)
            ->setNewData(null)
            ->setUserId(auth()->user()->id)
        );

        return $this->respondWithToken($token);
    }

    /**
     * @return  Response
     */
    public function me(): Response
    {
        $user = auth()->user();
        $isUserExists = $this->userService->checkIfExists($user->id);

        if (!$isUserExists) {
            return $this->apiResponse(
                'Unauthorized',
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $this->apiResponse(
            'User data fetched Successfully',
            Response::HTTP_OK,
            array_merge($user->toArray(), [
                'balance' => $user->balance
            ])
        );
    }

    /**
     * @return  Response
     */
    public function logout(): Response
    {
        $userId = auth()->user()->id;
        auth()->logout();

        event(
            (new LogActivityEvent)
            ->setType(self::AUDIT_TRAIL_TYPE)
            ->setDescription(self::AUDIT_TRAIL_LOGOUT)
            ->setOldData(null)
            ->setNewData(null)
            ->setUserId($userId)
        );

        return $this->apiResponse('Successfully logged out');
    }

    /**
     * @return  Response
     */
    public function refresh(): Response
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * @param  string $token
     *
     * @return  Response
     */
    protected function respondWithToken(string $token): Response
    {
        return $this->apiResponse(
            'Authorization successful',
            Response::HTTP_OK,
            [
                'name' => auth()->user()->name,
                'balance' => auth()->user()->balance,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        );
    }
}