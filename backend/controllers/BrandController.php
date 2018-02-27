<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex()
    {
        $page = new Pagination();
        $query = Brand::find();
        $page->totalCount=$query->count();//总条数
        $page->defaultPageSize=3;//每页显示条数
        $brand = $query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['brand'=>$brand,'page'=>$page]);
    }
    //品牌添加
    public function actionAdd(){
        //展示表单
        $model = new Brand();
        $requeset = \Yii::$app->request;
        if($requeset->isPost){
            //实例化
            $model->load($requeset->post());
            if($model->validate()){
                $model->is_delete=0;
                $model->save(0);
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
                exit();
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    public function actionEdit($id){
        //展示表单
        $model = Brand::findOne($id);
        $requeset = \Yii::$app->request;
        if($requeset->isPost){
            //实例化
            $model->load($requeset->post());
            if($model->validate()){
                $model->is_delete=0;
                $model->save(0);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['brand/index']);
            }else{
                var_dump($model->getErrors());
                exit();
            }
        }

        return $this->render('add',['model'=>$model]);
    }
    public function actionDelete($id){
        $model = Brand::findOne($id);
        if($model->is_delete==0){
            $model->is_delete=1;
            $model->save();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['brand/index']);

        }else{
            $model->is_delete=0;
            $model->save();
            \Yii::$app->session->setFlash('success','恢复成功');
            return $this->redirect(['brand/index']);
        }
    }
    public function actionUpdate(){
        //实例化组件
        $img = UploadedFile::getInstanceByName('file');
        $file = '/upload/' . uniqid() . '.' . $img->extension;//文件路径
        $img->saveAs(\Yii::getAlias('@webroot').$file);//文件保存
        return json_encode(['path'=>$file]);
    }

}
