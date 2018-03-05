<?php
namespace backend\models;
use yii\base\Model;


class AdminForm extends Model{
    //需要验证的-->用户名  密码  后续-->验证码
    public $username;
    public $password;
    public $code;
    public $auto_load;
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
            'auto_load'=>'自动登录'

            //'code'=>'验证码'    -->这是后续的  现在先不做!  一起做会比较难
        ];
    }
    public function rules()
    {
        return [
            [['username','password','code'],'required'],  //不能为空
            ['code','captcha','captchaAction'=>'admin/captcha'],//验证码规则
            ['auto_load','safe']

        ];
    }
    public function login(){//登录验证
        $admin = Admin::findOne(['username'=>$this->username]);
//        var_dump($admin);exit();
        if($admin){
            //账号存在
            //验证密码 明文$model->password  密文$admin->password
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){//登录成功
                //保存用户信息
                return \Yii::$app->user->login($admin);
            }else{
                $this->addError('password','密码错误');
            }
        }else{
            $this->addError('username','账号不存在');
        }
        return false;
    }




}