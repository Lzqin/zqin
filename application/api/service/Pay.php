<?php

namespace app\api\service;

use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\Order as OrderMode;
use app\api\service\Order as OrderService;
use app\lib\enum\OrderStatusEnum;
use think\Loader;
use think\Log;

//  extend/WxPay/WxPay.API.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if (!$orderID){
            throw new Exception('订单号不允许为空');
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        //订单号可能根本不存在
        //订单号存在，但是订单号和当前用户不匹配
        //订单有可能已经被支付过
        //进行库存量检测
        $this->checkOederValid();
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if(!$status['pass']){
            return$status;
        }
        $WxOrderData = new \WxPayUnifiedOrder();
        $WxOrderData->SetOut_trade_no($this->orderNO);
        $WxOrderData->SetTrade_type('JSAPI');
        $WxOrderData->SetTotal_fee($totalPrice*100);
        $WxOrderData->SetBody('零食商贩');
        $WxOrderData->SetOpenid($openid);
        $WxOrderData->SetNotify_url('');
    }

    private function getPaySignature($WxOrderData)
    {
        $WxOrder = \WxPayApi::unifiedOrder($WxOrderData);
        if ($WxOrder['retuen_code'] != 'SUCCESS' ||
            $WxOrder['result_code'] != 'SUCCESS')
        {
            Log::record($WxOrder,'error');
            Log::record('获取订单失败','error');
        }
    }

    private function makeWxPreOrder()
    {
        $openid = Token::getCurrenTokenVar('opemid');
        if (!$openid){
            throw new TokenException();
        }
    }

    private function checkOederValid()
    {
        $order = OrderMode::where('id' ,'=' ,$this->orderID)
            ->find();
        if(!$order){
            throw new OrderException();
        }
        if(!Token::isValidOperate($order->user_id)){
            throw new TokenException([
               'msg' =>'订单与用户不匹配',
                'errorCode' => 100003
            ]);
        }
        if($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg' => '订单异常',
                'errorCode'=> 800003,
                'code' => 400
            ]);
        }
        $this->orderNO = $order->order_no;
        return true;
    }

}