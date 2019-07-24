<?php
namespace App\Http\Controllers\Shoppingcart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Shoppingcart\members as members;
use Auth;
use Exception;
use Hash;
use Session;

/**
 * 購物車後臺 Session
 */
class Session_new extends Controller
{
    /**
     * 登入
     * @param  Request $Request
     * @return json
     */
    public function login(Request $Request)
    {
        $username = $Request->input('account');
        $password = $Request->input('password');
        $oMember = members::where('account', $username)->first();
        if($oMember['locked'] === '1'){
            return response()->json(['result' => false, 'message' => '該會員已停權']);
        }
        if(hash::check($password,$oMember['password'])){
            Session::put('useraccount', $oMember['account']);
            Session::put('permission', $oMember['permission']);
            Session::put('username', $oMember['name']);
            Session::put('id', $oMember['id']);
            return response()->json(['result' => true, 'message' => $username]);
        } else {
            return response()->json(['result' => false, 'message' => '帳號或密碼錯誤']);
        }
    }

    /**
     * 檢查使用者登入並回傳使用者名稱
     * @return json
     */
    public function getUser()
    {
        $sUsername = Session::get('useraccount');
        $sPermission = Session::get('permission');
        $sName = Session::get('username');
        $sId =  Session::get('id');
        if (empty($sUsername)) {
            return response()->json(['result' => false, 'message' => 'no user data']);
        }
        $aUser = ['account' => $sUsername, 'permission' => $sPermission, 'name' => $sName, 'id' => $sId];
        return response()->json(['result' => true, 'data' => $aUser]);
    }

    /**
     * 登出
     * @return json
     */
    public function logout()
    {
        Session::flush();
        return response()->json(['result' => true]);
    }
}

