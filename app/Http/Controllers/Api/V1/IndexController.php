<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\MyRedis;
use App\Models\Banner;
use App\Models\User;
use App\Models\MainCurrency;
use App\Models\OrderLog;
use GuzzleHttp\Client;
use App\Models\Withdraw;
use App\Models\LoanRepayment;
use App\Models\LoanOrder;
use App\Models\NodeConfig;
use App\Models\Common\Notice;

class IndexController extends ApiController
{
    public function index(Request $request)
    {
        $user = $this->user();
        
        $data['address'] = '';
        
        if ($user) 
        {
            $data['address'] = $user->address;
        }
        
        return $this->response($data);
    }
    
    public function tokenList(Request $request)
    {
        $list = MainCurrency::query()
            ->get(['id','name','main_chain','contract_address','precision'])
            ->toArray();
        return $this->response($list);
    }
}
