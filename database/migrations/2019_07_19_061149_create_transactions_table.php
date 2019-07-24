<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('transactions', function (Blueprint $table) {
            $table->bigIncrements('t_ID');
            $table->string('ID');
            $table->string('PID');
            $table->integer('Price');
            $table->integer('Amount');
            $table->timestamps();
        });
    }
}
