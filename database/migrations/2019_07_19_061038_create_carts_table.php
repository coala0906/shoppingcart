<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('carts', function (Blueprint $table) {
            $table->bigIncrements('c_ID');
            $table->integer('c_PID');
            $table->integer('c_Amount');
            $table->timestamps();
        });
    }
}
