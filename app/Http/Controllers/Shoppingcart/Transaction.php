<?php
namespace App\Http\Controllers\Shoppingcart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Shoppingcart\transactions as transactions;
use App\Model\Shoppingcart\carts as carts;
use App\Model\Shoppingcart\products as products;
use OperatorInterface;
use Exception;
use DB;
use Session;

class Transaction extends Controller
{
    protected $table = 'transactions';

    /**
     * 取得購買紀錄
     * @param  Request $Request
     * @return json
     */
    public function getList(Request $Request) {
        $num = 0;
        $aTransactionList = [];
        $sId = Session::get('id');
        $sPermission = $Request->input('permission');
        $aTransactionList = transactions::join('products','transactions.PID','products.PID')
        ->select('transactions.*','products.Name','products.Brand','products.Information','products.Image')
        ->where('ID','=',$sId)
        ->orderBy('transactions.created_at','desc')
        ->get()
        ->toArray();
        while(isset($aTransactionList[$num]['Price'])){
            $aTransactionList[$num]['Price'] = number_format($aTransactionList[$num]['Price']);
            $num  =  $num + 1;
        }
        return response()->json(['result' => true, 'data' => $aTransactionList]);
    }

    /**
     * 取得訂單資訊
     * @return json
     */
    public function getOrderList($_sPage) {
        $oOrderList = transactions::join('members','transactions.ID','members.id')
        ->select('transactions.*','members.account')
        ->groupBy('Order')
        ->orderBy('transactions.created_at','desc');
        $iCount = $oOrderList->get()->count();
        $aOrderList = $oOrderList
        ->skip(($_sPage -1) * 10)
        ->take(10)
        ->get()
        ->toArray();
        return response()->json(['result' => true, 'data' => $aOrderList,'total' => $iCount]);
    }

    /**
     * 取得訂單明細
     * @param  Request $Request
     * @return json
     */
    public function getOrderDetail(Request $Request) {
        $sOrdernum = $Request->input('order');
        $aOrderList = transactions::join('products','transactions.PID','products.PID')
        ->select('transactions.*','products.Name','products.Brand','products.Image')
        ->where('Order','=',$sOrdernum)
        ->orderBy('transactions.created_at','desc')
        ->get()
        ->toArray();
        return response()->json(['result' => true, 'data' => $aOrderList]);
    }

    /**
     * 修改訂單狀態
     * @param  Request $Request
     * @return json
     */
    public function putOrderStatus(Request $Request) {
        $sOrdernum = $Request->input('order');
        $sStatus = $Request->input('status');
        transactions::where('Order', $sOrdernum)->update(['Status' => $sStatus]);
        return response()->json(['result' => true,'message' => $sOrdernum]);
    }

    /**
     * 新增交易資料
     * @return json
     */
    public function createTransaction()
    {
        $num = 0;
        $sUserid = Session::get('id');
        $iOrdernum = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        DB::beginTransaction();
        try {
            $aCart = carts::join('products','c_PID','=','PID')
            ->where('c_ID','=',$sUserid)
            ->orderBy('carts.created_at','desc')
            ->groupBy('c_PID')
            ->selectRaw('*, sum(c_Amount) as c_Amount')
            ->get()
            ->toArray();
        if(empty($aCart)){
            throw new Exception('購物車內無商品');
        }
        while(isset($aCart[$num])){
            if($aCart[$num]['Status']=='0'){
                throw new Exception('請先移除購物車內已下架的商品');
            }
            $aAmount = products::where('PID','=',$aCart[$num]['PID'])->get()->toArray();
            if($aAmount[0]['Amount'] < $aCart[$num]['c_Amount']){
                throw new Exception($aCart[$num]['Name'].'的商品庫存不足');
            } else {
                $onhand = $aAmount[0]['Amount'] - $aCart[$num]['c_Amount'];
                $aProduct = ['Amount' => $onhand];
                products::where('PID', $aCart[$num]['PID'])->update($aProduct);
                $aTransaction = [
                'ID' => $sUserid,
                'PID' => $aCart[$num]['PID'],
                'Amount' => $aCart[$num]['c_Amount'],
                'Price' => $aCart[$num]['Price'],
                'Order' => $iOrdernum,
                'created_at' =>  date("Y-m-d H:i:s")
                ];
                transactions::insert($aTransaction);
            }
            $num = $num + 1;
      }
        carts::where('c_ID','=',$sUserid)->delete();
        DB::commit();
    } catch (Exception $e) {
        DB::rollback();
        return response()->json(['result' => false, 'message' => $e->getMessage()]);
    }
        return response()->json(['result' => true]);
    }

}

