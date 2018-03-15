<?php
namespace frontend\controllers;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\db\Exception;
use yii\web\Controller;

class OrderController extends Controller{
    //购物车提交页面
    public function actionIndex(){
        //查询出收货地址
        $address = Address::find()->where(['member_id'=>\Yii::$app->user->id])->asArray()->all();
        $cart = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
//        var_dump($address);exit();




        return $this->render('index',['addresses'=>$address,'carts'=>$cart]);

    }

    //ajax生成订单
    public function actionPay($add,$delivery)
    {

        $address = Address::findOne($add);//收货地址
        $deli = Delivery::findOne($delivery);
        $pay = Payment::findOne(['id' => 2]);
        $order = new Order();
        $order->member_id = \Yii::$app->user->id;//用户ID
        $order->name = $address->name;//收货人
        $order->province = $address->cmbProvince;
        $order->city = $address->cmbCity;
        $order->area = $address->cmbArea;
        $order->address = $address->address;
        $order->tel = $address->tel;
        $order->delivery_id = $delivery;
        $order->delivery_name = $deli->delivery_name;
        $order->delivery_price = $deli->delivery_price;
        $order->payment_id = 2;
        $order->payment_name = $pay->payment_name;
        $sum = 0;
        $cart = Cart::find()->where(['member_id' => \Yii::$app->user->id])->asArray()->all();
        //开启食物
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $order->status = 2;
            $order->create_time = time();
            $order->trade_no = uniqid();
            $order->save();//生成订单
            foreach ($cart as $v) {

                $good = Goods::findOne(['id' => $v['goods_id']]);
                if ($good->stock > $v['amount']) {//货多
                    $good->stock -= $v['amount'];
                } else {
                    //抛出异常
                    throw new Exception('商品[' . $good->name . ']存货不足');
                }

                $sum += $v['amount'] * $good['shop_price'];
                $order_goods = new OrderGoods();
                $order_goods->goods_id = $v['goods_id'];
                $order_goods->goods_name = $good->name;
                $order_goods->logo = $good->logo;
                $order_goods->price = $good->shop_price;
                $order_goods->amount = $v['amount'];
                $order_goods->total = $v['amount'] * $good->shop_price;
                $order_goods->order_id = $order->id;
                $order_goods->save();
                $good->save();
            }
            $order->total = $sum + $deli->delivery_price;
            $order->save();//生成订单


            $buy = Cart::find()->where(['member_id' => \Yii::$app->user->id])->all();
            foreach ($buy as $delete) {
                $delete->delete();//清空购物车
            }
            $transaction->commit();
            return json_encode('success');
        } catch (Exception $e) {
            //回滚
            $transaction->rollBack();
            return json_encode($good->name);
        }
    }
    public function actionPaySuccess(){
        return $this->render('pay');
    }
    public function actionOrder(){
        //查询自己的订单
        $order = Order::find()->where(['member_id'=>\Yii::$app->user->id])->orderBy(['id'=>SORT_DESC])->all();

        return $this->render('order',['order'=>$order]);
    }
}