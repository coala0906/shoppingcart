<?php

namespace App\Model\Shoppingcart;

use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        't_ID',
        'ID',
        'PID',
        'Amount',
        'Price',
        'created_at',
        'updated_date'
    ];
    public $primaryKey = 't_ID';
    /*public function getConnectionName()
        {
      return (config('app.env') == 'testing') ? 'MysqlTesting' : 'Shoppingcart';
         }*/
}


