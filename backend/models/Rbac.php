<?php
/**
 * Created by PhpStorm.
 * User: 是你哥哥
 * Date: 2018/3/7
 * Time: 14:27
 */
namespace backend\models;
use yii\base\Model;

class Rbac extends Model
{
    const SCENARIO_ADD ='add';
    const SCENARIO_EDIT ='edit';
    public $name;
    public $description;

    public function attributeLabels()
    {
        return [
            'name' => '名称(路由)',
            'description' => '描述'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'description'], 'required',],
            ['name', 'validateName','on'=>self::SCENARIO_ADD],
            ['name', 'validateRename','on'=>self::SCENARIO_EDIT],
        ];
    }

    public function validateName()
    {
        $authManager = \Yii::$app->authManager;
        if ($authManager->getPermission($this->name)) {
            //权限已存在
            $this->addError('name', '该权限已存在!');
        }
    }
    public function validateRename()
    {
        if(\Yii::$app->request->get('key')!= $this->name){
            $this->validateName();
        }

    }
}
