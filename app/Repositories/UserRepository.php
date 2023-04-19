<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @param string $id
     * 
     * @return bool
     */
    public function checkIfExists(string $id): bool
    {
        $user = User::find($id);

        return !is_null($user);
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
    	User::create([
    		'name' => $name,
    		'email' => $email,
    		'email_verified_at' => date('Y-m-d H:i:s'),
    		'password' => bcrypt($password)
    	]);  
    }
}
