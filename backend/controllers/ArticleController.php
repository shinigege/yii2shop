<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $page = new Pagination();
        $query = Article::find();
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 3;//每页显示条数
        $article = $query->offset($page->offset)->limit($page->limit)->where(['is_delete' => 0])->all();
        return $this->render('index', ['article' => $article, 'page' => $page]);
    }

    public function actionRecovery()
    {
        $page = new Pagination();
        $query = Article::find();
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 3;//每页显示条数
        $article = $query->offset($page->offset)->limit($page->limit)->where(['is_delete' => 1])->all();
        return $this->render('index', ['article' => $article, 'page' => $page]);
    }

    public function actionAdd()
    {
        $model = new Article();
        $content = new ArticleDetail();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            $content->load($request->post());
            if ($model->validate()) {
                $model->is_delete = 0;
                $model->create_time = time();
                $model->save();
                $content->save();
                \Yii::$app->session->setFlash('seccess', '添加成功');
                return $this->redirect(['article/index']);
            } else {
                var_dump($model->getErrors());
                exit();
            }
        }
        return $this->render('add', ['model' => $model, 'content' => $content]);
    }

    public function actionEdit($id)
    {
        $model = Article::findOne($id);
        $content = ArticleDetail::findOne($id);
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            $content->load($request->post());
            if ($model->validate()) {
                $model->save();
                $content->save();
                \Yii::$app->session->setFlash('seccess', '添加成功');
                return $this->redirect(['article/index']);
            } else {
                var_dump($model->getErrors());
                exit();
            }
        }
        return $this->render('add', ['model' => $model, 'content' => $content]);
    }

    public function actionShow($id)
    {
        $model = Article::findOne($id);
        $content = ArticleDetail::findOne($id);

        return $this->render('show', ['content' => $content, 'model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = Article::findOne($id);
        if ($model->is_delete == 0) {
            $model->is_delete = 1;
            $model->save();
            \Yii::$app->session->setFlash('success', '删除成功');
            return $this->redirect(['article/index']);

        } else {
            $model->is_delete = 0;
            $model->save();
            \Yii::$app->session->setFlash('success', '恢复成功');
            return $this->redirect(['article/index']);
        }
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => \Yii::getAlias('@web'),//图片访问路径前缀,
                ],
            ]
        ];
    }
}
