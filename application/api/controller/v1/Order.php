<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Token;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;
use think\Controller;
use app\api\service\Token as TokenService;

class Order extends BaseController
{
    //用户再选择商品后，向API提交包含它所选择商品的相关信息
    //API再接受到信息后，需要检查订单相关商品的库存量
    //有库存，把订单数据存入数据库中=下单成功，返回客户端消息
    //调用我们的支付接口，进去支付
    //还需要再次进行库存量检测
    //服务器这边就可以调用微信的支付接口
    //微信返回一个支付结果（异步）
    //成功：也需要进行库存量的检查
    //成功：进行库存量的扣除，失败：返回支付失败的结果

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];

    public function placeOrder()
    {
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid,$products);
        return $status;
    }
}