<?php

namespace backend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $auth_key
 * @property string $password_hash 密码
 * @property string $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $last_login_time 最后登录时间
 * @property string $last_login_ip 最后登录IP
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    const SCENARIO_ADD ='add';
    const SCENARIO_EDIT ='edit';
    const SCENARIO_RESET ='reset';
    public $pwd;
    public $password;
    public $repwd;
    public $arr;
    public static function tableName()
    {
        return 'admin';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login_time'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'last_login_ip'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['repwd','pwd'],'required','on'=>[self::SCENARIO_EDIT,self::SCENARIO_ADD]],
            ['password','required','on'=>[self::SCENARIO_EDIT]],
            ['pwd','required','on'=>[self::SCENARIO_RESET]],
            ['repwd','validatePwd'],
            ['arr','safe'],
//            ['password','validateOldpwd']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'pwd' => '新密码',
            'password' => '原密码',
            'repwd' => '确认密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录IP',
            'arr'=>'角色',
        ];
    }
    public function validatePwd(){
        $result= $this->repwd==$this->pwd;
        if(!$result){
            $this->addError('repwd','两次输入不一致');
        }
    }
//    public function validateOldpwd(){
//        $user = Yii::$app->user->identity;
//        $resutl = Yii::$app->security->validatePassword($this->password,$user->password_hash);
//        if(!$resutl){
//            $this->addError('password','原密码错误');
//        }
//    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);

    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;

    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
       return $authKey==$this->auth_key;
    }


}
