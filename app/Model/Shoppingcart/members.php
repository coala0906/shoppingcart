<?php

namespace App\Model\Shoppingcart;

use Illuminate\Database\Eloquent\Model;

class members extends Model
{
    protected $table = 'members';
    protected $fillable = [
        'id',
        'account',
        'password',
        'name',
        'permission',
        'phone',
        'email',
        'locked',
        'created_at',
        'updated_at',
        'remember_token'
    ];
    public $primaryKey = 'id';
    /*public function getConnectionName()
        {
      return (config('app.env') == 'testing') ? 'MysqlTesting' : 'Shoppingcart';
         }*/
}


