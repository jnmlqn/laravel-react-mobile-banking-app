<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('johndoe')
        ]);

        UserAccount::create([
            'balance' => 100000,
            'user_id' => $user->id
        ]);

        Bank::insert([
            [
                'provider' => 'pesonet',
                'bank' => 'Bank of the Philippine Island'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'PSBank'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'Maya'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'Gcash'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'Maybank'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'RCBC'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'Chinabank Savings'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'Philippine National Bank'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'CIMB'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'ING'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'Metrobank'
            ],
            [
                'provider' => 'pesonet',
                'bank' => 'BDO'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'Bank of the Philippine Island'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'PSBank'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'Maya'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'Gcash'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'Maybank'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'RCBC'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'Chinabank Savings'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'Philippine National Bank'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'CIMB'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'ING'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'Metrobank'
            ],
            [
                'provider' => 'instapay',
                'bank' => 'BDO'
            ],
        ]);
    }
}
