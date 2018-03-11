<?php
namespace frontend\models;
use frontend\models\Member;
use yii\base\Model;


class LoginForm extends Model{
    //需要验证的-->用户名  密码  后续-->验证码
    public $username;
    public $password;
    public $checkcode;
    public $auto_load;
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'auto_load'=>'自动登录'
        ];
    }
    public function rules()
    {
        return [
            [['username','password'],'required'],  //不能为空
            ['auto_load','safe'],
            ['checkcode','captcha'],//验证码规则

        ];
    }
    //登录验证
    public function login(){
        $admin = Member::findOne(['username'=>$this->username]);
        if($admin){
            //验证密码
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){//登录成功
                //保存用户信息
                //自动登录
                $duration = $this->auto_load?7*24*3600:0;//判断用户是否勾选了自动登录
                return \Yii::$app->user->login($admin,$duration);
            }else{
                $this->addError('password','密码错误');
            }
        }else{
            $this->addError('username','账号不存在');
        }
        return false;
    }
}