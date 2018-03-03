<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsGallery;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //搜索表单
        $select = new Goods();
        $page = new Pagination();
        $requset = \Yii::$app->request;
        if (!empty($requset->get())) {//如果有查询
            $conditions = [];
            $conditions['status'] = 1;//条件->没有被删除的数据


            //======================= 精准查询 =================================


            if (!empty($requset->get()['Goods']['s_name'])) {//如果有传入s_name
                $conditions['name'] = ['name' => $requset->get()['Goods']['s_name']];

            }
            //===================== 模糊查询 ===================================
            if (!empty($requset->get()['Goods']['s_sn'])) {//s_sn
                $conditions['sn'] = ['like', 'sn', $requset->get()['Goods']['s_sn']];
            }



            if (!empty($requset->get()['Goods']['s_money'])) {
                $conditions['min_price'] = ['>', 'shop_price', $requset->get()['Goods']['s_money']];
            }
            if (!empty($requset->get()['Goods']['m_money'])) {
                $conditions['max_price'] = ['<', 'shop_price', $requset->get()['Goods']['m_money']];
            }

            /*
             * Usermodel->find()->where(["like","字段名","查询值"])->one();
             *
             *
             *
             *
             * $result=User::find()->where([
                    'status'=>1,
                    ])->andwhere([
                    'or',
                    userna['like','username',$me],
                    ['like','email',$email],
                    ['like','mobile',$mobile],
                    ])->all();
             * */
            //======================= 最小 最大值查询 ==========================

//            if(!empty($requset->get()['Goods']['s_money'])){//如果有传入s_money
//                $conditions['shop_price']=$requset->get()['Goods']['s_money'];
//            }
//            if(!empty($requset->get()['Goods']['m_money'])){//如果有传入m_money
//                $conditions=['shop_price'=>$requset->get()['Goods']['m_money']];           }
//            //拼接where 条件
//            if(!empty($conditions)){
//                $where = " where ".implode(" and ",$conditions);//implode 拼接 数组
//            }
//            var_dump($conditions);
////            echo 111;exit();
////            var_dump($requset->get()['Goods']);exit();
//            $str = "";
//            foreach ($conditions as $key => $condition){
////                var_dump($condition);
//                $str.= "'$key'=>'$condition',";
//            }
//            var_dump($str);
//            exit();
//            var_dump(!empty($requset->get()['Goods']['s_name'])? $conditions['name']:'');exit();
            $query = Goods::find()->where(!empty($requset->get()['Goods']['s_sn']) ? $conditions['sn'] : '')->andWhere(!empty($requset->get()['Goods']['s_money']) ? $conditions["min_price"] : '')->andWhere(!empty($requset->get()['Goods']['m_money']) ? $conditions["max_price"] : '')->andWhere(!empty($requset->get()['Goods']['s_name']) ? $conditions["name"] : '')->andWhere(['status'=>1]);//只查询正常的状态
//            var_dump($query);
//            exit();
        } else {
            $query = Goods::find()->where(['status' => 1]);//只查询正常的状态
        }
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 3;//每页显示条数
        $model = $query->offset($page->offset)->limit($page->limit)->all();
        $arr['name']=$requset->get()['Goods']['s_name']??'';
        $arr['s_sn']=$requset->get()['Goods']['s_sn']??'';
        $arr['s_money']=$requset->get()['Goods']['s_money']??'';
        $arr['m_money']=$requset->get()['Goods']['m_money']??'';
        return $this->render('index', ['model' => $model, 'page' => $page, 'select' => $select,'arr'=>$arr]);
    }

    public function actionRecovery()
    {
        //搜索表单
        $select = new Goods();
        $page = new Pagination();
        $requset = \Yii::$app->request;
        if (!empty($requset->get())) {//如果有查询
            $conditions = [];
            $conditions['status'] = 0;//条件->没有被删除的数据
            //======================= 精准查询 =================================
            if (!empty($requset->get()['Goods']['s_name'])) {//如果有传入s_name
                $conditions['name'] = ['name' => $requset->get()['Goods']['s_name']];
            }
            //===================== 模糊查询 ===================================
            if (!empty($requset->get()['Goods']['s_sn'])) {//s_sn
                $conditions['sn'] = ['like', 'sn', $requset->get()['Goods']['s_sn']];
            }
            if (!empty($requset->get()['Goods']['s_money'])) {
                $conditions['min_price'] = ['>', 'shop_price', $requset->get()['Goods']['s_money']];
            }
            if (!empty($requset->get()['Goods']['m_money'])) {
                $conditions['max_price'] = ['<', 'shop_price', $requset->get()['Goods']['m_money']];
            }
            $query = Goods::find()->where(!empty($requset->get()['Goods']['s_sn']) ? $conditions['sn'] : '')->andWhere(!empty($requset->get()['Goods']['s_money']) ? $conditions["min_price"] : '')->andWhere(!empty($requset->get()['Goods']['m_money']) ? $conditions["max_price"] : '')->andWhere(!empty($requset->get()['Goods']['s_name']) ? $conditions["name"] : '')->andWhere(['status'=>0]);//只查询正常的状态
        } else {
            $query = Goods::find()->where(['status' => 0]);//只查询正常的状态
        }
        $page->totalCount = $query->count();//总条数
        $page->defaultPageSize = 3;//每页显示条数
        $model = $query->offset($page->offset)->limit($page->limit)->all();
        $arr['name']=$requset->get()['Goods']['s_name']??'';
        $arr['s_sn']=$requset->get()['Goods']['s_sn']??'';
        $arr['s_money']=$requset->get()['Goods']['s_money']??'';
        $arr['m_money']=$requset->get()['Goods']['m_money']??'';
        return $this->render('index', ['model' => $model, 'page' => $page, 'select' => $select,'arr'=>$arr]);
    }

    public function actionAdd()
    {
        $model = new Goods();
        $intro = new GoodsIntro();
        $requset = \Yii::$app->request;
        if ($requset->isPost) {
            //双方加载
            $model->load($requset->post());
            $intro->load($requset->post());
            if ($model->validate() && $intro->validate()) {//双方验证通过
                //日期自动保存
                $model->create_time = time();
                //自动生成货号->查询 goods_day_count
                //填充0代码   str_pad(1,5,"0",STR_PAD_LEFT);
                //查询是否今天添加过商品 ->查出今天的日期
                $date = date('Y-m-d', time());
                $time = GoodsDayCount::findOne(['day' => $date]);
                if (empty($time)) {//如果没有数据
                    $time = new GoodsDayCount();
                    $time->day = $date;//赋值 添加时间
                    $time->count = 1;//添加的第一个商品
                    //生成货号
                    $str = str_replace('-', '', $time->day);
                    $model->sn = $str . str_pad($time->count, 5, "0", STR_PAD_LEFT);//商品货号
                    $time->save();//商品时间
                    $model->save();//保存goods
                    $intro->save();//保存内容
//                    echo $model->sn,'<br>';

//                    echo '今天没有添加商品';exit();

                } else {
                    $str = str_replace('-', '', $time->day);//替换字符串函数 去除日期中的 '-'字符
                    $time->count++;//每添加一次商品 货号+1
                    $model->sn = $str . str_pad($time->count, 5, "0", STR_PAD_LEFT);//商品货号
//                    echo $time->count;
                    $time->save();
                    $model->save();//保存goods
                    $intro->save();//保存内容
//                    echo $model->sn,'<br>';
//                    echo '今天又商品';exit();
                }
//                echo $date;exit();
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['goods/index']);
//                var_dump($model->create_time);exit();
            }
        }
        $type = GoodsCategory::find()->select(['id', 'parent_id', 'name'])->asArray()->all();
//        var_dump($type);exit();

        return $this->render('add', ['model' => $model, 'intro' => $intro, 'type' => json_encode($type)]);
    }

    public function actionEdit($id)
    {
        $model = Goods::findOne($id);
        $intro = GoodsIntro::findOne($id);
        $requset = \Yii::$app->request;
        if ($requset->isPost) {
            //双方加载
            $model->load($requset->post());
            $intro->load($requset->post());
            if ($model->validate() && $intro->validate()) {//双方验证通过
                $model->save();//保存goods
                $intro->save();//保存内容
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['goods/index']);
            }
        }
        $type = GoodsCategory::find()->select(['id', 'parent_id', 'name'])->asArray()->all();
//        var_dump($type);exit();

        return $this->render('add', ['model' => $model, 'intro' => $intro, 'type' => json_encode($type)]);
    }

    public function actionDelete($id)
    {
        $model = Goods::findOne($id);
        if ($model->status == 0) {
            $model->status = 1;
            $model->save();
            \Yii::$app->session->setFlash('success', '恢复成功');
            return $this->redirect(['goods/index']);

        } else {
            $model->status = 0;
            $model->save();
            \Yii::$app->session->setFlash('success', '删除成功');
            return $this->redirect(['goods/index']);
        }
    }

    public function actionShow($id)
    {
        $model = Goods::findOne($id);
        $content = GoodsIntro::findOne($id);

        return $this->render('show', ['content' => $content, 'model' => $model]);
    }
    public function actionSave()
    {
//        var_dump($_POST);
        $id = $_POST['id'];//商品ID
        $path = $_POST['path'];//图片路径
        $model = new GoodsGallery();
        $model->goods_id = $id;
//        $id = $model->id;
        $model->path = $path;
        $model->save();//保存到数据库
    }


    //相册
    public function actionImg($id){
        $model = new GoodsGallery();

        $imgs=GoodsGallery::find()->asArray()->where(['goods_id'=>$id])->orderBy(['id'=>SORT_DESC])->all();
//        return json_encode($model);//返回保存的路径作为回显
        return $this->render('img',['model'=>$model,'id'=>$id,'imgs'=>$imgs]);
    }
    public function actionDeleteimg($id){
        $model=GoodsGallery::findOne($id);
        $id = $model->goods_id;
        $model->delete();
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['goods/img?id='.$id]);
    }

    public function actions()//副文本插件
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
