<?php
namespace App\Http\Controllers\Shoppingcart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Shoppingcart\members as members;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OperatorInterface;
use DB;
use Hash;

class Member extends Authenticatable
{
    protected $table = 'members';
    protected $fillable = [
        'account', 'password', 'name', 'permission', 'email', 'locked', 'api_token', 'phone', 'created_at', 'updated_at'
    ];

    /**
     * 取得會員列表
     * @return json
     */
    public function getList()
    {
        $aMemberList = [];
        $aMemberList = members::orderBy('created_at','desc')->get()->toArray();
        return response()->json(['result' => true, 'data' => $aMemberList]);

    }

    /**
     * 取單一會員資訊
     * @param  integer  $id
     * @return json
     */
    public function getMember($id) {
        $aMember = [];
        $aMember = members::where('id', $id)->get()->toArray();
        if (Empty($aMember)) {
            return response()->json(['result' => false, 'message' => 'NO DATA FOUND']);
        }
        return response()->json(['result' => true, 'data' => $aMember]);
    }

    /**
     * 新增會員資料
     * @param  Request $Request
     * @return json
     */
    public function createMember(Request $Request)
    {
        $sAccount = $Request->input('account');
        $sPassword = $Request->input('password');
        $sName = $Request->input('name');
        $sPermission = $Request->input('permission');
        $sEmail = $Request->input('email');
        $fLocked = $Request->input('locked');
        $sPhone = $Request->input('phone');
        if (is_null($fLocked)){
            $fLocked = 0;
        }
        if (is_null($sPermission)){
            $sPermission = "0";
        }
        if (!members::where('account', $sAccount)->get()->isEmpty()) {
            return response()->json(['result' => false, 'message' => '此帳號已被使用']);
        }
        $aMember = [
            'account'=>$sAccount,
            'password' => Hash::make($sPassword),
            'name'=>$sName,
            'permission'=>$sPermission,
            'email'=>$sEmail,
            'locked'=>$fLocked,
            'phone'=>$sPhone,
            'created_at' =>  date("Y-m-d H:i:s")
        ];
        members::insert($aMember);
        return response()->json(['result' => true, 'data' => $aMember]);
    }

    /**
     * 修改會員資料
     * @param  integer $_iMid
     * @param Request $Request
     * @return json
     */
    public function putMember($_iMid, Request $Request)
    {
        $Member = members::where('id', $_iMid)->first();
        if (is_null($Member)) {
            return response()->json(['result' => false,'message' => '查無此帳號']);
        }
        $sAccount = $Request->input('account');
        $sPassword = $Request->input('password');
        $sname = $Request->input('name');
        $sPermission = $Request->input('permission');
        $sEmail = $Request->input('email');
        $fLocked = $Request->input('locked');
        $sPhone = $Request->input('phone');
        if (is_null($fLocked)) {
            $fLocked = 0;
        }
        if (is_null($sPermission)) {
            $sPermission = "0";
        }
        if (!members::where('account', $sAccount)->get()->isEmpty() && $Member['account'] !== $sAccount) {
            return response()->json(['result' => false, 'message' => '此帳號已被使用']);
        }
        if (!is_null($sPassword)) {
            $aMember = [
            'account'=>$sAccount,
            'password' => Hash::make($sPassword),
            'name'=>$sname,
            'permission'=>$sPermission,
            'email'=>$sEmail,
            'locked'=>$fLocked,
            'phone'=>$sPhone,
            ];
        } else {
            $aMember = [
            'account'=>$sAccount,
            'name'=>$sname,
            'permission'=>$sPermission,
            'email'=>$sEmail,
            'locked'=>$fLocked,
            'phone'=>$sPhone
            ];
        }
        $sResult = members::where('id', $_iMid)->update($aMember);
        return response()->json(['result' => true, 'data' => $sResult]);
    }
}

