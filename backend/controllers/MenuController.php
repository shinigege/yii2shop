<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $page = new Pagination();
        $query = Menu::find();
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 5;//每页显示条数
        $model = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index', ['model' => $model, 'page' => $page]);
    }

    //添加菜单
    public function actionAdd(){
        $model = new Menu();
        $parent=[];
        $parent['']='=请选择上级菜单=';
        $parent[]='顶级分类';

        $arr= Menu::find()->where(['parent_id'=>0])->asArray()->all();
        foreach ($arr as $value) {
            $parent[$value['id']]=$value['name'];
        }
//        var_dump($arr);exit();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add',['model'=>$model,'parent'=>$parent]);
    }
    public function actionEdit($id){
        $model = Menu::findOne($id);
        $parent=[];
        $parent['']='=请选择上级菜单=';
        $parent[]='顶级分类';
        $arr= Menu::find()->where(['parent_id'=>0])->asArray()->all();
        foreach ($arr as $value) {
            $parent[$value['id']]=$value['name'];
        }
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['menu/index']);
            }
        }
        return $this->render('add',['model'=>$model,'parent'=>$parent]);
    }
    public function actionDelete(){
        $id = $_POST['id'];
        $model = Menu::findOne($id);
        $result = $model->delete();
        if($result){
            return json_encode(true);
        }else{
            return json_encode(false);
        }
    }
}
