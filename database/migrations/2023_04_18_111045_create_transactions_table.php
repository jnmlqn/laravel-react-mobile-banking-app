<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['send', 'receive']);
            $table->enum('mode', ['bank', 'email']);
            $table->integer('bank_id')->nullable()->foreignId();
            $table->string('email', 255)->nullable();
            $table->float('amount', 8,2);
            $table->float('last_current_balance', 8,2);
            $table->string('description', 255);
            $table->integer('user_id')->foreignId();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
