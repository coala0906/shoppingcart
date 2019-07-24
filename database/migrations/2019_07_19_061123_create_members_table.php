<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account');
            $table->string('password');
            $table->string('name');
            $table->string('permission',1);
            $table->string('phone');
            $table->string('email');
            $table->string('locked',1);
            $table->timestamps();
        });
    }
}
