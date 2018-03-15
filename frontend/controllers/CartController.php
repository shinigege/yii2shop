<?php

namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\Goods;
use yii\web\Cookie;

class CartController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCateSuccess($goods_id,$amount){
        //判断用户是否登录
        if(\Yii::$app->user->isGuest){//没有登陆x
            $cookie = \Yii::$app->request->cookies;
            $value = $cookie->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            //如果Cookie中存在该商品 累加
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id] += $amount;
            }else{
                $carts[$goods_id]=$amount;
            }
            //保存cookie
//            var_dump($carts);exit();
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        }else{//登录了的
            if(Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id])){//如果有记录
                $model = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
                $model->amount += $amount;
                $model->save();

//                var_dump($model);exit();
            }else{
                $model = new Cart();
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->member_id = \Yii::$app->user->id;
                $model->save();
            }
        }


        return $this->render('success');
    }
    //购物车页面
    public function actionCart(){
        if(\Yii::$app->user->isGuest){
            $cookie = \Yii::$app->request->cookies;
            $value = $cookie->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            //查询商品
//            var_dump(empty($cookie));exit();
            return $this->render('cart',['cookies'=>$carts]);
        }else{//没有登录的情况
            $cart = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            return $this->render('rcart',['cookies'=>$cart]);
        }



    }
    //ajax操作购物车
    public function actionAjaxCart($id,$amount){
        if(\Yii::$app->user->isGuest){//如果没有登陆
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if($value){
                $carts = unserialize($value);
            }else{
                $carts = [];
            }
            if($amount){//直接覆盖上一个
                $carts[$id] = $amount;
            }else{//删除
                unset($carts[$id]);
            }
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);

        }else{//如果登录了
            $model = Cart::findOne(['goods_id'=>$id,'member_id'=>\Yii::$app->user->id]);

            if ($amount){
                $model->amount = $amount;
                $model->save();
            }else{
                $model->delete();
            }

        }
    }

}
