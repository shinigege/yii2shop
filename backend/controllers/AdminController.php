<?php

namespace backend\controllers;

use backend\filters\Filter;
use backend\models\Admin;
use backend\models\AdminForm;
use Codeception\Lib\ParamsLoader;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Cookie;
use yii\web\User;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //做判断  用户是否自动登录

        //=====================自动登录==================================
//        $cookies = \Yii::$app->request->cookies;
////        var_dump($cookies->getValue('username'));exit();
////        var_dump($cookies['password']);exit();
//        if ($cookies->has('username')&&$cookies->has('password')) {//如果有session的话
//            $admin = Admin::findOne(['username' => $cookies->getValue('username')]);
//            if ($admin) {
//                //账号存在
//                //验证密码 明文$model->password  密文$admin->password
//                if ($cookies->getValue('password')==$admin->password_hash) {//登录成功
//                    //保存用户信息
//                    $re = \Yii::$app->user->login($admin);
////                    var_dump($re);
//                }
//            }
//        }

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
        $model->scenario = Admin::SCENARIO_ADD;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            $model->password_reset_token = uniqid();
            $model->created_at = time();
            $model->auth_key = \Yii::$app->security->generateRandomString();
            //实例化表单组件
            if ($model->validate()) {
                if ($model->repwd != $model->pwd) {
                    \Yii::$app->session->setFlash('error', '添加失败,两次输入密码不一致');
                    return $this->redirect(['admin/add']);  //返回管理员列表
                }
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->pwd);
                $model->save(0);
//                var_dump($model->id);
//                var_dump($model->arr);exit();
                //=========添加角色==================
                $authManager = \Yii::$app->authManager;

                foreach ($model->arr as $key => $value) {
                    $role = $authManager->getRole($value);
                    $authManager->assign($role, $model->id);
                }


                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['admin/index']);  //返回管理员列表
            }

        }
        return $this->render('add', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = Admin::findOne($id);
        $authManager = \Yii::$app->authManager;
        //========多选框回显=================
        $arr = $authManager->getAssignments($id);
//        var_dump($arr);
//        $roles = $authManager->getRole($id);
//        var_dump($arr);exit();
        $roles = [];
        $i = 0;
        foreach ($arr as $m => $s) {
            $roles[$i] = $m;
            $i++;
        }
//        var_dump($roles);exit();
        $model->arr = $roles;
        //========多选框回显=================

        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            echo '<pre>';
            var_dump($request->post());
            $model->load($request->post());
            $model->password_reset_token = uniqid();
            $model->updated_at = time();
            $model->arr = $request->post('Admin')['arr'];
            //实例化表单组件
            if ($model->validate()) {
//                var_dump($model->arr);

//                exit();

                $model->save(0);

//                echo '<pre>';
//                var_dump($arr);exit();
                $authManager->revokeAll($id);
                foreach ($model->arr as $key => $value) {
                    $role = $authManager->getRole($value);
                    $authManager->assign($role, $model->id);
                }
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['admin/index']);  //返回管理员列表
            }

        }
        return $this->render('update', ['model' => $model]);
    }


    public function actionEdit($id)
    {
//        $model = new Admin();
        $admin = Admin::findOne($id);
        $admin->scenario = Admin::SCENARIO_EDIT;
//        var_dump($model);exit;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $admin->load($request->post());
            $admin->auth_key = \Yii::$app->security->generateRandomString();
            //实例化表单组件
            if ($admin->validate()) {

                if (\Yii::$app->security->validatePassword($admin->password, $admin->password_hash)) {
                    //验证成功
                } else {
                    \Yii::$app->session->setFlash('error', '修改失败,原密码错误');
                    return $this->redirect(['admin/edit', "id" => \Yii::$app->user->id]);  //返回管理员列表
                }
                $admin->updated_at = time();
                $admin->password_hash = \Yii::$app->security->generatePasswordHash($admin->pwd);
                $admin->save(0);
                \Yii::$app->user->logout();
                \Yii::$app->session->setFlash('success', '修改成功,请重新登录');
                return $this->redirect(['admin/login']);//返回管理员列表
            }

        }
        return $this->render('edit', ['model' => $admin]);
    }//修改只能修改自己的!!!  先不做

    public function actionDelete()
    {
        $id = $_POST['id'];
        $model = Admin::findOne($id);
        $result = $model->delete();

        if ($result) {
            return json_encode(true);
        } else {
            return json_encode(false);
        }
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
//                    if ($model->auto_load) {
////                        echo '勾选了';exit;
//                        //勾选了自动登录  把用户名和ID存入session
//                        $cookies = \Yii::$app->response->cookies;
//
//                        $cookie = new Cookie();
//                        $cookie->name ='password';
//                        $cookie->expire=time()+3600;
//                        $cookie->value =$admin->password_hash;
//                        $cookies->add($cookie);
//
//                        $cookie = new Cookie();
//                        $cookie->name ='username';
//                        $cookie->expire=time()+3600;
//                        $cookie->value =$admin->username;
//                        $cookies->add($cookie);
//                    }
                    $admin->last_login_time = time();
                    $admin->password = 1;
                    $admin->pwd = $admin->password_hash;
                    $admin->repwd = $admin->password_hash;
                    $admin->last_login_ip = $_SERVER["REMOTE_ADDR"];
                    if ($admin->validate()) {
                        $admin->save();
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
//        $cookies = \Yii::$app->response->cookies;
//        $cookies->remove('username');
//        $cookies->remove('password');
        $su = \Yii::$app->user->logout();

//        var_dump($su);exit();
        \Yii::$app->session->setFlash('success', '退出成功');
        return $this->redirect(['admin/login']);
    }

    public function actionReset($id)
    {//直接充值密码
        $model = Admin::findOne($id);
        $model->scenario = Admin::SCENARIO_RESET;
        $requset = \Yii::$app->request;
        if ($requset->isPost) {
            $model->load($requset->post());
            $model->auth_key = \Yii::$app->security->generateRandomString();
            if ($model->validate()) {
                $model->updated_at = time();
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->pwd);
                $model->save(0);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['admin/index']);//返回管理员列表
            }
        }

        return $this->render('reset', ['model' => $model]);

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
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>Filter::class,
                'except'=>['index','captcha','login','logout']
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
