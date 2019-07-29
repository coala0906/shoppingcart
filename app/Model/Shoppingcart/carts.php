<?php
namespace App\Model\Shoppingcart;

use Illuminate\Database\Eloquent\Model;

class carts extends Model
{
    protected $table = 'carts';
    protected $fillable = [
        'c_ID',
        'c_PID',
        'c_Amount'
    ];
    public $primaryKey = 'id';
    /*public function getConnectionName()
        {
      return (config('app.env') == 'testing') ? 'MysqlTesting' : 'Shoppingcart';
         }*/
}


