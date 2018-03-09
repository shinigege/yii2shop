<?php
/**
 * Created by PhpStorm.
 * User: 是你哥哥
 * Date: 2018/3/7
 * Time: 14:27
 */
namespace backend\models;
use yii\base\Model;
class Role extends Model{
    const SCENARIO_ROLEADD ='add';
    const SCENARIO_ROLEEDIT ='edit';
    public $name;
    public $description;
    public $arr;
    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
            'arr'=>'权限',
        ];
    }
    public function rules()
    {
        return [
            [['name','description','arr'],'required',],
            ['name', 'validateName','on'=>self::SCENARIO_ROLEADD],
            ['name', 'validateRename','on'=>self::SCENARIO_ROLEEDIT],


        ];
    }
    public function validateName()
    {
        $authManager = \Yii::$app->authManager;
        if ($authManager->getRole($this->name)) {
            //权限已存在
            $this->addError('name', '该角色已存在!');
        }
    }
    public function validateRename()
    {
        if(\Yii::$app->request->get('key')!= $this->name){
            $this->validateName();
        }

    }
    public static function getPermission(){
        $authManager = \Yii::$app->authManager;
        $arr = $authManager->getPermissions();
        $array =[];
        foreach ($arr as $a){
            $array[$a->name]=$a->description;
        }
        return $array;
    }
    public static function getRole(){
        $authManager = \Yii::$app->authManager;
        $arr = $authManager->getRoles();
        $array =[];
        foreach ($arr as $a){
            $array[$a->name]=$a->description;
        }
        return $array;
    }
    public static function getUrl(){
        $authManager = \Yii::$app->authManager;
        $arr = $authManager->getPermissions();

        $array =[];
        $array['']='=请选择路由=';
        foreach ($arr as $a){
            $array[$a->name]=$a->name;
        }
        return $array;
    }

}
