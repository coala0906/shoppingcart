<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('products', function (Blueprint $table) {
            $table->bigIncrements('PID');
            $table->string('Name');
            $table->string('Brand');
            $table->integer('Price');
            $table->integer('Amount');
            $table->string('Information');
            $table->string('Image');
            $table->integer('Category');
            $table->string('Status',1);
            $table->timestamps();
        });
    }
}
