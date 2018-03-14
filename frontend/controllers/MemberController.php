<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\aliyun\SignatureHelper;
use frontend\models\Brand;
use frontend\models\Goods;
use frontend\models\GoodsGallery;
use frontend\models\GoodsIntro;
use frontend\models\Member;
use yii\data\Pagination;

class MemberController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //首页展示
        $model = \frontend\models\GoodsCategory::find()->where(['parent_id'=>0])->all();
//        var_dump(\Yii::$app->user->identity);
//        var_dump(\Yii::$app->user->isGuest);exit();
        return $this->render('index',['model'=>$model]);
    }
    //商品列表展示
    public function actionList($id){
//        $cate = GoodsCategory::findOne(['id'=>$id]);
//        //处理分类不存在的情况
//        switch ($cate->depth){
//            case 0://1级分类
//            case 1://2级分类
//                $ids = $cate->children()->select(['id'])->andWhere(['depth'=>2])->asArray()->column();
//                break;
//            case 2://3级分类
//                $ids = [$id];
//                break;
//        }
//        $model = Goods::find()->where(['in','goods_category_id',$ids])->all();
//        $brand = Brand::find()->all();
//        return $this->render('list',['model'=>$model,'brand'=>$brand]);
        $page = new Pagination();
        $cate = GoodsCategory::findOne(['id'=>$id]);
        //处理分类不存在的情况
        switch ($cate->depth){
            case 0://1级分类
            case 1://2级分类
                $ids = $cate->children()->select(['id'])->andWhere(['depth'=>2])->asArray()->column();
                break;
            case 2://3级分类
                $ids = [$id];
                break;
        }
        $query = Goods::find()->where(['in','goods_category_id',$ids]);
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 6;//每页显示条数
        $model = $query->offset($page->offset)->limit($page->limit)->all();
        $brand = Brand::find()->all();
        return $this->render('list',['model'=>$model,'brand'=>$brand,'page'=>$page]);
    }
    public function actionGoods($id){
        $model = Goods::findOne($id);
        $model->view_times+=1;
        $model->save();
        $brand = Brand::findOne(['id'=>$model->brand_id]);
        $goods = GoodsIntro::findOne(['goods_id'=>$id]);
//        var_dump($brand);exit;

        $img = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        return $this->render('goods',['model'=>$model,'img'=>$img,'brand'=>$brand,'goods'=>$goods]);
    }

    //用户注册
    public function actionRegist()
    {
        $request = \Yii::$app->request;
        $model = new Member();

        if ($request->isPost) {
            $model->load($request->post(), '');
            $model->auth_key = \Yii::$app->security->generateRandomString();
            $model->status = 1;
            $model->password_hash = \Yii::$app->security->generatePasswordHash($request->post('password'));

            if ($model->validate()) {
                $model->created_at = time();
                $model->save(0);
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['member/index']);  //返回管理员列表
            }else{
                var_dump($model->getErrors());exit();
            }
//            echo '<pre>';
//            var_dump($request);exit();
        }
        return $this->render('regist');
    }

    //用户登录
    public function actionLogin()
    {
        $model = new \frontend\models\LoginForm();
        $request = \Yii::$app->request;
        //判断用户是否提交表单
        if ($request->isPost) {
            //加载数据
            $model->load($request->post(), '');
            if ($model->validate()) {
                if ($model->login()) {//登录成功
                    $id = \Yii::$app->user->id;//登录的ID
                    $admin = Member::findOne($id);
                    $admin->last_login_time = time();
                    $admin->last_login_ip = $_SERVER["REMOTE_ADDR"];
                    $admin->save();
                    \Yii::$app->session->setFlash('success', '登录成功');
                    return $this->redirect(['member/index']);

                } else {
                    echo '???';
                    exit();
                }
            }
        }
        return $this->render('login');

    }

    //退出
    public function actionLogout()
    {
        $su = \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success', '退出成功');
        return $this->redirect(['member/login']);
    }

    public function actionValidateSms($tel,$code){
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $c = $redis->get('code_'.$tel);
        if($c == $code){
            return 'true';
        }
        return 'false';
    }
    //发送短信
    public function actionSms($tel){
        $code = rand(100000,999999);
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->set('code_'.$tel,$code,5*60);
        $r = \Yii::$app->sms->setTel($tel)->setParam(['code'=>$code])->send();
        if($r){
            return 'success';
        }
        return 'fail';
    }
    //测试发送短信功能
    public function actionText(){
        $r = \Yii::$app->sms->setTel('15181013877')->setParam(["code" => rand(100000,999999),])->send();
        var_dump($r);


//        $params = array ();
//
//        // *** 需用户填写部分 ***
//
//        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
//        $accessKeyId = "LTAIPzzVO2PZnt3c";
//        $accessKeySecret = "as4Ov3z1Rwunq8kKFFhybzBa6X0IDr";
//
//        // fixme 必填: 短信接收号码
//        $params["PhoneNumbers"] = "15181013877";
//
//        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
//        $params["SignName"] = "伟伟安逸";
//
//        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
//        $params["TemplateCode"] = "SMS_126935079";
//
//        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
//        $params['TemplateParam'] = Array (
//            "code" => rand(100000,999999),
//        );
//
//        // fixme 可选: 设置发送短信流水号
//        $params['OutId'] = "12345";
//
//        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
//        $params['SmsUpExtendCode'] = "1234567";
//
//
//        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
//        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
//            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
//        }
//
//        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
//        $helper = new SignatureHelper();
//
//        // 此处可能会抛出异常，注意catch
//        $content = $helper->request(
//            $accessKeyId,
//            $accessKeySecret,
//            "dysmsapi.aliyuncs.com",
//            array_merge($params, array(
//                "RegionId" => "cn-hangzhou",
//                "Action" => "SendSms",
//                "Version" => "2017-05-25",
//            ))
//        );
//
//        var_dump($content);
    }


    //验证用户名是否重复
    public function actionValidateMember($username)
    {
        $model = Member::findOne(['username' => $username]);
//        var_dump($model);
        if (!$model) {
            return 'true';
        } else {
            return 'false';
        }

    }

}
