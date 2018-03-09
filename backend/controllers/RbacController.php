<?php
/**
 * Created by PhpStorm.
 * User: 是你哥哥
 * Date: 2018/3/7
 * Time: 14:24
 */
namespace backend\controllers;
use backend\filters\Filter;
use backend\models\Rbac;
use yii\rbac\Role;
use yii\web\Controller;

class RbacController extends Controller {
    //使用authManager
    public function actionText(){
        $authManager = \Yii::$app->authManager;

        //步骤 1 添加权限    ->2 角色   ->3 给用户分配角色
//        $permission = $authManager->createPermission('brand/index');
//        $permission->description = '品牌列表';
//        $authManager->add($permission);
//        $permission = $authManager->createPermission('brand/add');
//        $permission->description = '添加';
//        $authManager->add($permission);

//        $role = $authManager->createRole('超级管理员');
//        $authManager->add($role);
//        $role2 = $authManager->createRole('普通管理员');
//        $authManager->add($role2);
//        $role = $authManager->getRole('普通管理员');//给角色分配权限
////        $permission = $authManager->getPermission('brand/add');
////        $authManager->addChild($role,$permission);
//        $permission2 = $authManager->getPermission('brand/index');
//        $authManager->addChild($role,$permission2);

//        $role1 = $authManager->getRole('超级管理员');
//        $role2 = $authManager->getRole('普通管理员');
//        $authManager->assign($role1,2);
//        $authManager->assign($role2,3);
        $result = \Yii::$app->user->can('brand/add');
//        var_dump($result);
//        echo 'success';

    }
    //添加权限
    public function actionPermission(){//添加权限
        $model = new Rbac();
        $model->scenario=Rbac::SCENARIO_ADD;

        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $authManager = \Yii::$app->authManager;
                $permission = $authManager->createPermission($model->name);
                $permission->description = $model->description;
                $authManager->add($permission);
                \Yii::$app->session->setFlash('seccess', '添加成功');
                return $this->redirect(['rbac/index-permission']);
            }
        }
        return $this->render('Permission',['model'=>$model]);
    }
    //权限列表
    public function actionIndexPermission(){//
        $model = \backend\models\Role::getPermission();
        return $this->render('indexpermission',['model'=>$model]);
    }
    //权限修改
    public function actionUpdatePermission($key){
        $model = new Rbac();
        $model->scenario=Rbac::SCENARIO_EDIT;
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($key);
        $model->description = $permission->description;
        $model->name = $permission->name;
//        var_dump($permission);exit();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $permission->description=$model->description;
                $permission->name=$model->name;
                $authManager->update($key,$permission);
                \Yii::$app->session->setFlash('seccess', '修改成功');
                return $this->redirect(['rbac/index-permission']);
            }
        }
        return $this->render('Permission',['model'=>$model,'permission'=>$permission]);
    }
    //删除权限
    public function actionDeletePermission(){
        $key = $_POST['key'];
        $authManager = \Yii::$app->authManager;
        $permission=$authManager->getPermission($key);
        $result = $authManager->remove($permission);
        return json_encode($result);

    }


    //===============================角色========================================

    public function actionRole(){//添加角色
        $model  = new \backend\models\Role();

        $model->scenario = \backend\models\Role::SCENARIO_ROLEADD;

        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $authManager = \Yii::$app->authManager;
                //添加角色
                $role = $authManager->createRole($model->name);
                $role->description=$model->description;
                $authManager->add($role);
//                var_dump($model->arr);exit();
                //给角色添加权限
                foreach ($model->arr as $array){
//                    var_dump($array);
                    $permission = $authManager->getPermission($array);
                    $authManager->addChild($role,$permission);//给角色添加权限
                }
                \Yii::$app->session->setFlash('seccess', '添加成功');
                return $this->redirect(['rbac/index-role']);
//                var_dump($model->arr);exit();
            }
        }
        return $this->render('AddRbac',['model'=>$model]);
    }
    //角色列表
    public function actionIndexRole(){
        $role = \backend\models\Role::getRole();
        return $this->render('indexRole',['model'=>$role]);
    }
    //角色修改
    public function actionUpdateRole($key){
//        var_dump($key);exit();
        $model = new \backend\models\Role();

        $model->scenario = \backend\models\Role::SCENARIO_ROLEEDIT;

        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($key);
        $model->description = $role->description;
        $model->name = $role->name;
        //========多选框回显============
        $arr = $authManager->getPermissionsByRole($key);
        $array = [];
        $i=0;
        foreach ($arr as $a=>$value) {
            $array[$i]=$a;
            $i++;
        }
        $model->arr = $array;//多选框回显
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $authManager = \Yii::$app->authManager;
                //修改角色
                var_dump($key);echo '<pre>';
                $role = $authManager->getRole($key);
//                var_dump($role);exit();
                $role->description=$model->description;
                $role->name=$model->name;
                $authManager->update($key,$role);
                //给角色添加权限
                $authManager->removeChildren($role);//首先移除所有儿子

                foreach ($model->arr as $array){

                    $permission = $authManager->getPermission($array);
                    $authManager->addChild($role,$permission);//给角色添加权限
                }
                \Yii::$app->session->setFlash('seccess', '修改成功');
                return $this->redirect(['rbac/index-role']);
            }
        }

        return $this->render('updateRbac',['model'=>$model,'permission'=>$role]);
    }
    //角色的删除
    public function actionDeleteRole(){
        $key = $_POST['key'];
        $authManager = \Yii::$app->authManager;
        $permission=$authManager->getRole($key);
        $result = $authManager->remove($permission);
        return json_encode($result);
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>Filter::class,
            ]
        ];
    }

}