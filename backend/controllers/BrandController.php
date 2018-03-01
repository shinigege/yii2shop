<?php

namespace backend\controllers;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $page = new Pagination();
        $query = Brand::find()->where(['is_delete' => 0]);
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 3;//每页显示条数
        $brand = $query->offset($page->offset)->limit($page->limit)->where(['is_delete' => 0])->all();
        return $this->render('index', ['brand' => $brand, 'page' => $page]);
    }

    public function actionRecovery()
    {
        $page = new Pagination();
        $query = Brand::find()->where(['is_delete' => 1]);
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 3;//每页显示条数
        $brand = $query->offset($page->offset)->limit($page->limit)->where(['is_delete' => 1])->all();
        return $this->render('index', ['brand' => $brand, 'page' => $page]);
    }

    //品牌添加
    public function actionAdd()
    {
        //展示表单
        $model = new Brand();
        $requeset = \Yii::$app->request;
        if ($requeset->isPost) {
            //实例化
            $model->load($requeset->post());
            if ($model->validate()) {
                $model->is_delete = 0;
                $model->save(0);
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                exit();
            }
        }

        return $this->render('add', ['model' => $model]);
    }

    public function actionEdit($id)
    {
        //展示表单
        $model = Brand::findOne($id);
        $requeset = \Yii::$app->request;
        if ($requeset->isPost) {
            //实例化
            $model->load($requeset->post());
            if ($model->validate()) {
                $model->is_delete = 0;
                $model->save(0);
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['brand/index']);
            } else {
                var_dump($model->getErrors());
                exit();
            }
        }

        return $this->render('add', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = Brand::findOne($id);
        if ($model->is_delete == 0) {
            $model->is_delete = 1;
            $model->save();
            \Yii::$app->session->setFlash('success', '删除成功');
            return $this->redirect(['brand/index']);

        } else {
            $model->is_delete = 0;
            $model->save();
            \Yii::$app->session->setFlash('success', '恢复成功');
            return $this->redirect(['brand/index']);
        }
    }
    public function actionUpdate()
    {
        //实例化组件
        $img = UploadedFile::getInstanceByName('file');
        $file = '/upload/' . uniqid() . '.' . $img->extension;//文件路径
        $result = $img->saveAs(\Yii::getAlias('@webroot') . $file);//文件保存
        if($result){
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey = "UomPkOiNJUHhSBHZfsB-flXs5hOnQvFIniVGxlkH";
            $secretKey = "IfjulWPhl03QRMD9MosmnGJAv_Ut2Np5pMPoUDg3";
            $bucket = "yuhao";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot').$file;
            // 上传到七牛后保存的文件名
            $key = $file;
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if($err == null){//没有错误
                return json_encode([
                    'path'=>"http://p4v17zx23.bkt.clouddn.com/{$key}"
                ]);
            }else{
                return json_encode([
                    'path'=>$err
                ]);
            }
        }else{
            return json_encode([
                'path'=>"fail"
            ]);
        }
    }


    public function actionText()
    {
// 需要填写你的 Access Key 和 Secret Key
        $accessKey = "UomPkOiNJUHhSBHZfsB-flXs5hOnQvFIniVGxlkH";
        $secretKey = "IfjulWPhl03QRMD9MosmnGJAv_Ut2Np5pMPoUDg3";
        $bucket = "yuhao";
// 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
// 生成上传 Token
        $token = $auth->uploadToken($bucket);
// 要上传文件的本地路径
        $filePath = \Yii::getAlias('@webroot').'/upload/1.jpg';
// 上传到七牛后保存的文件名
        $key = '/upload/1.jpg';
// 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();
// 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        echo "\n====> putFile result: \n";
        if ($err !== null) {
            var_dump($err);
        } else {
            var_dump($ret);
        }
    }
}
