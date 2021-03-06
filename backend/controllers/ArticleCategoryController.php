<?php

namespace backend\controllers;

use backend\filters\Filter;
use backend\models\ArticleCategory;
use yii\data\Pagination;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
//        $model = ArticleCategory::find()->all();
        $page = new Pagination();
        $query = ArticleCategory::find()->where(['is_delete'=>0]);
        $page->totalCount=$query->count();//总条数
        $page->defaultPageSize=3;//每页显示条数
        $model = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['model'=>$model,'page'=>$page]);
    }
    public function actionRecovery()
    {
//        $model = ArticleCategory::find()->all();
        $page = new Pagination();
        $query = ArticleCategory::find()->where(['is_delete'=>1]);
        $page->totalCount=$query->count();//总条数
        $page->defaultPageSize=3;//每页显示条数
        $model = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['model'=>$model,'page'=>$page]);
    }
    public function actionAdd(){
        $model = new ArticleCategory();
        $requset = \Yii::$app->request;
        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){
                $model->is_delete=0;
                $model->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        $model =ArticleCategory::findOne($id);
        $requset = \Yii::$app->request;
        if($requset->isPost){
            $model->load($requset->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $model = ArticleCategory::findOne($id);
        if($model->is_delete==0){
            $model->is_delete=1;
            $model->save();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['article-category/index']);

        }else{
            $model->is_delete=0;
            $model->save();
            \Yii::$app->session->setFlash('success','恢复成功');
            return $this->redirect(['article-category/index']);
        }
    }
    public function actionDeletes(){
        $id = $_POST['id'];
        $model = ArticleCategory::findOne($id);
        $model->is_delete=1;
        $result = $model->save();
        if($result){
            return json_encode(true);
        }else{
            return json_encode(false);
        }

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
