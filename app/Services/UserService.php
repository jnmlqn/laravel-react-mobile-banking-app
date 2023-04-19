<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    /**
    * @var UserRepository
    */
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $id
     * 
     * @return bool
     */
    public function checkIfExists(string $id): bool
    {
        return $this->userRepository->checkIfExists($id);
    }

    /**
     * @param  string  $name
     * @param  string  $email
     * @param  string  $password
     * 
     * @return void
     * @throws \Exception
     */
    public function register(
        string $name,
        string $email,
        string $password
    ): void {
        $this->userRepository->register(
            $name,
            $email,
            $password
        );     
    }
}
