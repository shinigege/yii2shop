<?php

namespace frontend\controllers;

use frontend\models\Address;

class AddressController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    //添加
    public function actionAddress(){
        $request = \Yii::$app->request;
        $address = Address::find()->asArray()->all();
        $model = new Address();
        if($request->isPost){

            $model->load($request->post(),'');
            $model->auto=$model->auto=='on'?1:0;
            if($model->validate()){
                $model->member_id = \Yii::$app->user->id;
                $model->save(0);
                return $this->redirect(['address/address']);
            }else{
                var_dump($model->getErrors());
                exit();
            }
        }
        return $this->render('address',['address'=>$address]);
    }
    //修改回显
    public function actionEdit(){
        $id = $_GET['id'];
        $arr = Address::findOne(['id'=>$id])->toArray();
        $arr['id']=$id;
//        var_dump($arr);
        return json_encode($arr);
    }
    //修改保存
    public function actionUpdate(){
        $request = \Yii::$app->request;
        $address = Address::find()->asArray()->all();
        if($request->isPost){
//            echo '<pre>';
//var_dump($request->post());
//exit();

            $val = Address::findOne(['id'=>$request->post('id')]);
            $val->load($request->post(),'');
            $val->auto=$val->auto=='on'?1:0;
            if($val->validate()){
//
//                var_dump($val);
//                exit();
                $val->save(0);
                return $this->redirect(['address/address']);
            }else{
                var_dump($val->getErrors());
                exit();
            }
        }
        return $this->render('address',['address'=>$address]);

    }
    //删除
    public function actionDelete(){
//        var_dump($_GET);exit();
        $id = $_GET['id'];
        $val = Address::findOne(['id'=>$id]);
        $re = $val->delete();
        if($re){
            return json_encode(true);
        }else{
            return json_encode(false);
        }

    }
//    public function
    public function actionValidateProvince($cmbProvince)
    {

//        var_dump($model);
        if ($cmbProvince!='请选择省份') {
            return 'true';
        } else {
            return 'false';
        }

    }
    public function actionValidateCity($cmbCity)
    {
//        var_dump($model);
        if ($cmbCity!='请选择城市') {
            return 'true';
        } else {
            return 'false';
        }

    }
    public function actionValidateArea($cmbArea)
    {
//        var_dump($model);
        if ($cmbArea!='请选择区县') {
            return 'true';
        } else {
            return 'false';
        }

    }



}
