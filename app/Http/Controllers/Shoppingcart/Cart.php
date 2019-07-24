mespace App\Http\Controllers\Shoppingcart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Shoppingcart\carts as carts;
use App\Model\Shoppingcart\products as products;
use OperatorInterface;
use DB;
use Hash;
use Session;
use Exception;

class Cart extends Controller
{
    protected $table = 'carts';  
    protected $fillable = [
        'ID', 'c_ID', 'c_PID', 'created_at', 'updated_at'
    ];

    /**
     * 取得購物車列表
     * @return json
     */
    public function getList()
    {
        $num = 0;
        $aCartList = [];
        $sUserid = Session::get('id');
        if (!empty($sUserid)) {
            $aCartList = carts::join('products','c_PID','=','PID')
            ->where('c_ID','=',$sUserid)
            ->orderBy('carts.created_at','desc')
            ->groupBy('c_PID')
            ->selectRaw('*, sum(c_Amount) as c_Amount')
            ->get()
            ->toArray();
        } else {
            $aCartList = carts::join('products','c_PID','=','PID')->orderBy('carts.created_at','desc')->get()->toArray();
        }
        while(isset($aCartList[$num]['Price'])){
            $aCartList[$num]['Price'] = number_format($aCartList[$num]['Price']);
            $num  =  $num + 1;
        }
        return response()->json(['result' => true, 'data' => $aCartList]);
    }

    /**
     * 新增商品到購物車
     * @param  Request $Request
     * @return json
     */
    public function createCart(Request $Request)
    {
        $sPid = $Request->input('pid');
        $sAmount = $Request->input('amount');
        $sUserid = Session::get('id');
        $aProducts = products::where('PID','=',$sPid)->get()->toArray();
        if ($aProducts[0]['Amount'] < $sAmount) {
            return response()->json(['result' => false, 'message' => '商品庫存不足']);
        }
        $aCart = [
        'c_ID'=> $sUserid,
        'c_PID' => $sPid,
        'c_Amount'=> $sAmount,
        'created_at' => date("Y-m-d H:i:s")
        ];
        carts::insert($aCart);
        return response()->json(['result' => true, 'data' => $aCart]);
    }

    /**
     * 修改購物車商品數量
     * @param  Request $Request
     * @return json
     */
    public function putCart(Request $Request)
    {
        $i = 0;
        DB::beginTransaction();
        try {
        while($Request->has($i.'.PID')) {
            $sPid = $Request->input($i.'.PID');
            $sAmount = $Request->input($i.'.c_Amount');
            $sUserid = Session::get('id');
            $aProducts = products::where('PID','=',$sPid)->get()->toArray();
            if (empty($sPid) || empty($sAmount)) {
                throw new Exception($aProducts[0]['Name'].'的商品資料有誤');
            }
            if ($aProducts[0]['Amount'] < $sAmount) {
                throw new Exception($aProducts[0]['Name'].'的商品庫存不足,目前庫存數:'.$aProducts[0]['Amount']);
            }
            carts::where('c_ID', $sUserid)->where('c_PID', $sPid)->delete();
            $aCart = [
            'c_ID'=> $sUserid,
            'c_PID' => $sPid,
            'c_Amount'=> $sAmount,
            'created_at' => date("Y-m-d H:i:s")
            ];
            carts::insert($aCart);
            $i = $i + 1;
        }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['result' => false, 'message' => $e->getMessage()]);
    }
        return response()->json(['result' => true]);
    }

    /**
     * 移除購物車商品
     * @param  integer $_fPid
     * @return json
     */
    public function delCart($_fPid)
    {
        $sUserid = Session::get('id');
        carts::where('c_ID', $sUserid)->where('c_PID', $_fPid)->delete();
        return response()->json(['result' => true]);
    }
}

