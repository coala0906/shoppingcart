<?php

namespace App\Model\Shoppingcart;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    //
    protected $table = 'products';
        protected $fillable = [
            'PID',
            'Name',
            'Brand',
            'Price',
            'Amount',
            'Information',
            'Image',
            'Category',
            'Status',
            'created_at',
            'updated_at'
        ];
        public $primaryKey = 'PID';
       /*public function getConnectionName()
        {
      return (config('app.env') == 'testing') ? 'MysqlTesting' : 'Shoppingcart';
         }*/
}      

