<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = GoodsCategory::find()->orderBy(['tree' => SORT_ASC,'lft'=>SORT_ASC])->all();
        return $this->render('index', ['model' => $model]);
    }
    public function actionAdd(){
        $model = new GoodsCategory();
        $requset = \Yii::$app->request;
        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){
                if($model->parent_id!=0){
                    $parent_id = $model->parent_id;
                    $parent = GoodsCategory::findOne(['id'=>$parent_id]);
//                    var_dump($parent);exit();
                    $model->prependTo($parent);
                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect(['goods-category/index']);
                }else{

                    $model->makeRoot();
                    \Yii::$app->session->setFlash('success','添加成功');
                    return $this->redirect(['goods-category/index']);
                }
            }
        }
        $type = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $type[]=['name'=>'顶级分类','id'=>0,'parent_id'=>0];
        return $this->render('add',['model'=>$model,'type'=>json_encode($type)]);
    }
    public function actionText(){
        $countries = new GoodsCategory(['name' => 'Countries']);
        $countries->parent_id=1;
        $countries->prependTo(GoodsCategory::findOne(['id'=>2]));

    }
    public function actionEdit($id){
        $model = GoodsCategory::findOne($id);
        $requset = \Yii::$app->request;
        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){
                if($model->parent_id!=0){
                    $parent_id = $model->parent_id;
                    $parent = GoodsCategory::findOne(['id'=>$parent_id]);
//                    var_dump($parent);exit();
                    $model->appendTo($parent);
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['goods-category/index']);
                }else{
                    if($model->getOldAttribute('parent_id')){
                        $model->makeRoot();
                    }else{
                        $model->save();
                    }
                    \Yii::$app->session->setFlash('success','修改成功');
                    return $this->redirect(['goods-category/index']);
                }
            }
        }
        $type = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        $type[]=['name'=>'顶级分类','id'=>0,'parent_id'=>0];
        return $this->render('add',['model'=>$model,'type'=>json_encode($type)]);
    }
    public function actionDelete($id){
        $model = GoodsCategory::findOne($id);
        if($model->parent_id){
            $model->delete();
        }else{
            $model->deleteWithChildren();
        }
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['goods-category/index']);
    }
    public function actionZtree(){
        $model = GoodsCategory::find()->asArray()->select(['id','parent_id','name'])->all();
        return $this->renderPartial('text',['model'=>json_encode($model)]);
    }
}
