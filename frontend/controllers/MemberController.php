<?php

namespace frontend\controllers;

use frontend\models\Member;

class MemberController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        var_dump(\Yii::$app->user->identity);
//        var_dump(\Yii::$app->user->isGuest);exit();
        return $this->render('index');
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
    //收货地址管理


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
