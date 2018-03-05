<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\AdminForm;
use Codeception\Lib\ParamsLoader;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Cookie;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //做判断  用户是否自动登录

        //=====================自动登录==================================
        $cookies = \Yii::$app->request->cookies;
//        var_dump($cookies->getValue('username'));exit();
//        var_dump($cookies['password']);exit();
        if ($cookies->has('username')&&$cookies->has('password')) {//如果有session的话
            $admin = Admin::findOne(['username' => $cookies->getValue('username')]);
            if ($admin) {
                //账号存在
                //验证密码 明文$model->password  密文$admin->password
                if ($cookies->getValue('password')==$admin->password_hash) {//登录成功
                    //保存用户信息
                    $re = \Yii::$app->user->login($admin);
//                    var_dump($re);
                }
            }
        }

//        var_dump($id);exit();
        $page = new Pagination();
        $query = Admin::find();
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 3;//每页显示条数
        $model = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index', ['model' => $model, 'page' => $page]);
    }

    public function actionAdd()
    {
        $model = new Admin();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            $model->auth_key = '1';
            $model->password = '1';
            $model->password_reset_token = uniqid();
            $model->created_at = time();
            //实例化表单组件
            if ($model->validate()) {
                if ($model->repwd != $model->pwd) {
                    \Yii::$app->session->setFlash('error', '添加失败,两次输入密码不一致');
                    return $this->redirect(['admin/add']);  //返回管理员列表
                }
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->pwd);
                $model->save(0);
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['admin/index']);  //返回管理员列表
            } else {
                var_dump($model->getErrors());
                exit();
            }

        }
        return $this->render('add', ['model' => $model]);
    }
    public function actionUpdate($id)
    {
        $model =Admin::findOne($id);
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            $model->auth_key = '1';
            $model->repwd = '1';
            $model->pwd = '1';
            $model->password = '1';
            $model->password_reset_token = uniqid();
            $model->updated_at = time();
            //实例化表单组件
            if ($model->validate()) {
                $model->save(0);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['admin/index']);  //返回管理员列表
            } else {
                var_dump($model->getErrors());
                exit();
            }

        }
        return $this->render('update', ['model' => $model]);
    }


    public function actionEdit($id)
    {
        $model = new Admin();
        $admin = Admin::findOne($id);
//        var_dump($model);exit;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            $admin->repwd = $model->repwd;
            $admin->pwd = $model->pwd;
            $admin->password = $model->password;
            //实例化表单组件
            if ($admin->validate()) {
                if ($model->repwd != $model->pwd) {
                    \Yii::$app->session->setFlash('error', '修改失败,两次输入密码不一致');
                    return $this->redirect(['admin/edit',"id"=>\Yii::$app->user->id]);  //返回管理员列表
                }
                if (\Yii::$app->security->validatePassword($model->password,$admin->password_hash)){
                    //验证成功
                }else{
                    \Yii::$app->session->setFlash('error', '修改失败,原密码错误');
                    return $this->redirect(['admin/edit',"id"=>\Yii::$app->user->id]);  //返回管理员列表

                }

                $admin->updated_at = time();
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($model->pwd);
                $admin->save(0);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['admin/index']);//返回管理员列表
            } else {
                var_dump($admin->getErrors());
                exit();
            }

        }
        return $this->render('edit', ['model' => $model]);
    }//修改只能修改自己的!!!  先不做

    public function actionDelete($id)
    {
        $model = Admin::findOne($id);
        $model->delete();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['admin/index']);  //返回管理员列表
    }

    public function actionLogin() //用户登录
    {
        //展示登录表单
        $model = new AdminForm();
        $request = \Yii::$app->request;
        //判断用户是否提交表单
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->login()) {//登录成功
//                    var_dump(\Yii::$app->user->identity);exit();
                    //验证用户是否勾选了自动登录
                    $id = \Yii::$app->user->id;//登录的ID
                    $admin = Admin::findOne($id);
                    if ($model->auto_load) {
//                        echo '勾选了';exit;
                        //勾选了自动登录  把用户名和ID存入session
                        $cookies = \Yii::$app->response->cookies;

                        $cookie = new Cookie();
                        $cookie->name ='password';
                        $cookie->expire=time()+3600;
                        $cookie->value =$admin->password_hash;
                        $cookies->add($cookie);

                        $cookie = new Cookie();
                        $cookie->name ='username';
                        $cookie->expire=time()+3600;
                        $cookie->value =$admin->username;
                        $cookies->add($cookie);
                    }
                    $admin->last_login_time = time();
                    $admin->password = 1;
                    $admin->pwd = $admin->password_hash;
                    $admin->repwd = $admin->password_hash;
                    $admin->last_login_ip = $_SERVER["REMOTE_ADDR"];
                    if ($admin->validate()) {
                        $admin->save();
                    } else {
                        var_dump($admin->getErrors());
                        exit();
                    }
                    \Yii::$app->session->setFlash('success', '登录成功');

                    return $this->redirect(['admin/index']);
                }
            }
        }
        return $this->render('login', ['model' => $model]);
    }

    //用户退出
    public function actionLogout()
    {
        $cookies = \Yii::$app->response->cookies;
        $cookies->remove('username');
        $cookies->remove('password');
        $su = \Yii::$app->user->logout();

//        var_dump($su);exit();
        \Yii::$app->session->setFlash('success', '退出成功');
        return $this->redirect(['admin/login']);
    }

    public function actions()
    {//验证码
        return [
            'captcha' => [
                'class' => CaptchaAction::className(),
                'minLength' => 3,//最短长度,
                'maxLength' => 4,//zuicahng
            ]
        ];
    }
//    public function behaviors()
//    {
//        return [
//            'access'=>[
//                'class'=>AccessControl::className(),
//                'only'=>['login','logout','signup'],
//                'rules'=>[
//                    [
//                        'allow' => true,
//                        'actions' => ['admin','login'],
//                        'roles' => ['?','@'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['admin','logout'],
//                        'roles' => ['?','@'],
//                    ],
//                    [
//                        'allow' => true,
//                        'actions' => ['admin','index'],
//                        'roles' => ['@'],
//                    ],
//
//                ]
//            ]
//        ];
//    }

}
