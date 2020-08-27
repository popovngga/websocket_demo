<?php


namespace App\Http\Controllers\okcoin;


use App\Events\ResponseEvent;
use App\Query;
use Illuminate\Http\Request;

class OkController
{
    public  function index(Request $config)
    {
        $config = $config->all();
        if ($config['side'] === 'something') {
            $obj = new AccountApi($config);
            $res = $obj -> getCurrencies();
        } else {
            $obj = new SpotApi($config);
            $res = $obj -> takeOrder($config['instrument_id'],$config['side'],$config['size'],$config['price']);
        }
        //$obj = new AccountApi($config);
        //$coin = "EOS";
        //$res = $obj -> getWalletInfo();
        //$res = $obj -> getSpecialWalletInfo($coin);
        //$res = $obj -> transfer($coin,"0.1","6","1","","");
        //$res = $obj -> withdrawal($coin,"1","4","eostoliuheng:OKEx","123456","0.1");
        //$res = $obj -> getLeger("EOS");
        //$res = $obj -> getDepositAddress($coin);
        //$res = $obj -> getWithdrawalHistory();
        //$res = $obj -> getCoinWithdrawalHistory($coin);
        //$res = $obj -> getDepositHistory();
        //$res = $obj -> getCoinDepositHistory($coin);
        //$res = $obj -> getCurrencies();
        //$res = $obj -> getWithdrawalFee($coin);

        /**
         * 币币
         */
        //$instrumentId = "BTC-USD";
        //$currency = "EOS";
        //$obj = new SpotApi($config);
        //$res = $obj -> getAccountInfo();
        //$res = $obj -> getCoinAccountInfo($currency);
        //$res = $obj -> getLedgerRecord($currency);
        //$res = $obj -> takeOrder($config['instrument_id'],$config['side'],$config['size'],$config['price']);
        //$res = $obj -> revokeOrder($instrumentId,"3452612358987776");
        //$res = $obj -> getOrdersList($instrumentId,"2","","",1);
        //$res = $obj -> getOrderInfo($instrumentId,"3271189018971137");
        //$res = $obj -> getFills($instrumentId,"3230072570268672");
        //$res = $obj -> getCoinInfo();
        //$res = $obj -> getDepth($instrumentId,1);
        //$res = $obj -> getTicker();
        //$res = $obj -> getSpecificTicker($instrumentId);
        //$res = $obj -> getDeal($instrumentId);
        //$res = $obj -> getKine($instrumentId,3600);

        //$instrumentId = "BTC-USD";
        //$currency = "BTC";
        //$obj = new MarginApi($config);
        //$res = $obj -> getAccountInfo();
        //$res = $obj -> getCoinAccountInfo($instrumentId);
        //$res = $obj -> getLedgerRecord($instrumentId);
        //$res = $obj -> getMarginConf();
        //$res = $obj -> getMarginSpecialConf($instrumentId);
        //$res = $obj -> getBorrowedRecord();
        //$res = $obj -> getSpecialBorrowedRecord($instrumentId,0);
        //$res = $obj -> borrowCoin($instrumentId, $currency, 0.1);
        //$res = $obj -> returnCoin($instrumentId, $currency, 0.1, "");
        //$res = $obj -> takeOrder($config['instrument_id'], $config['side'], $config['size'],"2","10");
        //$res = $obj -> revokeOrder($instrumentId,"3292706588398592");
        //$res = $obj -> getOrdersList($instrumentId,"-1","","",1);
        //$res = $obj -> getOrderInfo($instrumentId,"3292706588398592");
        //$res = $obj -> getFills($instrumentId,"3292706588398592");

        Query::create([
            'query_result' => json_encode($res),
            'query_param' => json_encode($config)
        ]);
        broadcast(new ResponseEvent($res));
    }

    public function coinInfo(Request $config)
    {
        $config = $config->all();
        $obj = new SpotApi($config);
        return $obj -> getCoinInfo();
    }
}
