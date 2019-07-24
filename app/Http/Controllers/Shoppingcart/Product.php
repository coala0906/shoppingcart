<?php
namespace App\Http\Controllers\Shoppingcart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Shoppingcart\products as products;
use OperatorInterface;
use DB;
use Illuminate\Support\Facades\Storage;

class Product extends Controller
{
    protected $table = 'products';

    /**
     * 取得產品列表
     * @return json
     */
    public function getList($_sPage)
    {
        $aProductList = [];
        $num = 0;
        $oProductList = products::orderBy('created_at','desc');
        if ($_sPage !== 'undefined'){
            $iCount = $oProductList->get()->count();
            $aProductList = $oProductList
            ->skip(($_sPage -1) * 10)
            ->take(10)
            ->get()
            ->toArray();
        } else {
            $aProductList = $oProductList
            ->get()
            ->toArray();
            $iCount = 0;
        }
        while (isset($aProductList[$num]['Price'])) {
            $aProductList[$num]['Price'] = number_format($aProductList[$num]['Price']);
            $num  =  $num + 1;
        }
        return response()->json(['result' => true, 'data' => $aProductList, 'total' => $iCount]);
       
    }

    /**
     * 取單一產品資訊
     * @param  integer  $id
     * @return json
     */
    public function getProduct($_sId)
    {
        $oProduct = products::where('PID', $_sId)->get();
        if ($oProduct->isEmpty()) {
            return response()->json(['result' => false, 'message' => 'NO DATA FOUND']);
        }
        return response()->json(['result' => true, 'data' => $oProduct->toArray()]);
    }

    /**
     * 新增產品資料
     * @param  Request $Request
     * @return json
     */
    public function createProduct(Request $Request)
    {
        $sName = $Request->input('name');
        $sBrand = $Request->input('brand');
        $sPrice = $Request->input('price');
        $sAmount = $Request->input('amount');
        $sInformation = $Request->input('information');
        $sCategory = $Request->input('category');
        $fStatus = $Request->input('status');
        if ($Request->hasFile('file')) {
            $file = $Request->file('file');
            $url_path = 'storage/uploads/cover';
            $rule = ['jpg', 'png', 'gif','jpeg'];
            $clientName = $file->getClientOriginalName();
            $tmpName = $file->getFileName();
            $realPath = $file->getRealPath();
            $entension = $file->getClientOriginalExtension();
            if (!in_array($entension, $rule)) {
                return response()->json(['result' => false, 'message' => '圖片格式錯誤']);
            }
            $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
            $path = $file->move($url_path, $newName);
            $namePath = 'http://rd-vue-eric.rd6.vir888.com/hex/shoppingcart/'.$url_path . '/' . $newName;
        } else {
            $namePath = 'http://rd-vue-eric.rd6.vir888.com/hex/shoppingcart/storage/uploads/cover/404.png';
        }
        $aProduct = [
        'Name'=>$sName,
        'Brand'=>$sBrand,
        'Price'=>$sPrice,
        'Amount'=>$sAmount,
        'Information'=>$sInformation,
        'Category'=>$sCategory,
        'Status'=>$fStatus,
        'Image'=>$namePath,
        'created_at' =>  date("Y-m-d H:i:s")
        ];
        products::insert($aProduct);
        return response()->json(['result' => true]);
    }

    /**
     * 修改產品資料
     * @param  integer $_iPid
     * @param Request $Request
     * @return json
     */
    public function putProduct($_iPid, Request $Request)
    {
        $Product = products::where('PID', $_iPid)->first();
        if (empty($Product)) {
            return response()->json(['result' => false]);
        }
        $sName = $Request->input('name');
        $sBrand = $Request->input('brand');
        $sPrice = $Request->input('price');
        $sAmount = $Request->input('amount');
        $sInformation = $Request->input('information');
        $sCategory = $Request->input('category');
        $fStatus = $Request->input('status');
        if ($Request->hasFile('file')) {
            $file = $Request->file('file');
            $url_path = 'storage/uploads/cover';
            $rule = ['jpg', 'png', 'gif','jpeg'];
            $clientName = $file->getClientOriginalName();
            $tmpName = $file->getFileName();
            $realPath = $file->getRealPath();
            $entension = $file->getClientOriginalExtension();
            if (!in_array($entension, $rule)) {
                return response()->json(['result' => false, 'message' => '圖片格式錯誤']);
            }
            $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
            $path = $file->move($url_path, $newName);
            $namePath = 'http://rd-vue-eric.rd6.vir888.com/hex/shoppingcart/'.$url_path . '/' . $newName;
        }
        if (!isset($namePath)) {
            $aPutData = [
            'Name'=>$sName,
            'Brand'=>$sBrand,
            'Price'=>$sPrice,
            'Amount'=>$sAmount,
            'Information'=>$sInformation,
            'Category'=>$sCategory,
            'Status'=>$fStatus
            ];
        } else {
            $aPutData = [
            'Name'=>$sName,
            'Brand'=>$sBrand,
            'Price'=>$sPrice,
            'Amount'=>$sAmount,
            'Information'=>$sInformation,
            'Category'=>$sCategory,
            'Status'=>$fStatus,
            'Image'=>$namePath
            ];
        }
        $sResult = products::where('PID', $_iPid)->update($aPutData);
        return response()->json(['result' => true, 'data' => $sResult]);
    }
}

