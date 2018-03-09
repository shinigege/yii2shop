<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/admin/login']];
    } else {
        //查询数据库
//        echo '<pre>';
        $models = \backend\models\Menu::find()->asArray()->where(['parent_id'=>0])->all();
        $menuItems=[];
        foreach ($models as $model){
            $son = \backend\models\Menu::find()->where(['parent_id'=>$model['id']])->asArray()->all();
            $arr = [];
            for ($i=0;$i<\backend\models\Menu::find()->where(['parent_id'=>$model['id']])->count();$i++){

                if(Yii::$app->user->can($son[$i]['url'])){
                    $arr[]=[
                        'label'=>$son[$i]['name'],
                        'url'=>'/'.$son[$i]['url'],
                    ];
                }
//                $result = \Yii::$app->user->can('brand/add');


            }
            if(!empty($arr)){
                $menuItems[] = ['label'=>$model['name'],'items'=>$arr];
            };
            $arr = [];
//                var_dump(\backend\models\Menu::find()->where(['parent_id'=>$model['id']])->asArray()->all());
            }
            $menuItems[]= ['label' => '修改密码', 'url' => ['/admin/edit',"id"=>Yii::$app->user->id]];
                $menuItems[]=['label' => '退出('.Yii::$app->user->identity->username.')', 'url' => ['/admin/logout']];


//        $arr =[];
//        foreach ($menuItems as $key =>$menuItem){
//            var_dump($menuItem);
//        }
//        var_dump($arr);
//        var_dump($menuItems);
//        $arr =  ['label' => '商品管理',
//            'items'=>[
//                [
//                    'label'=>'商品列表',
//                    'url'=>'/goods/index',
//                ]]];
//        var_dump($arr);
//        exit();

//        $menuItems = [
//            ['label' => '商品管理', 'url' => ['/goods/index'],
//                'items'=>[
//                    [
//                        'label'=>'商品列表',
//                        'url'=>'/goods/index',
//                    ],
//                    [
//                        'label'=>'添加商品',
//                        'url'=>'/goods/add'
//                    ],
//                    [
//                        'label'=>'商品分类添加',
//                        'url'=>'/goods-category/add'
//                    ],
//                    [
//                        'label'=>'商品分类列表',
//                        'url'=>'/goods-category/index'
//                    ]
//                ]],
//            ['label' => '文章管理', 'url' => ['/article/index'],
//                'items'=>[
//                    [
//                        'label'=>'文章列表',
//                        'url'=>'/article/index',
//                    ],
//                    [
//                        'label'=>'添加文章',
//                        'url'=>'/article/add'
//                    ],
//                    [
//                        'label'=>'文章分类添加',
//                        'url'=>'/article-category/add'
//                    ],
//                    [
//                        'label'=>'文章分类列表',
//                        'url'=>'/article-category/index'
//                    ]
//                ]],
//            ['label' => '品牌管理', 'url' => ['/brand/index'],
//                'items'=>[
//                    [
//                        'label'=>'查看品牌',
//                        'url'=>'/brand/index',
//                    ],
//                    [
//                        'label'=>'添加品牌',
//                        'url'=>'/brand/add'
//                    ],
//                    [
//                        'label'=>'文章分类添加',
//                        'url'=>'/article-category/add'
//                    ],
//                    [
//                        'label'=>'文章分类列表',
//                        'url'=>'/article-category/index'
//                    ]
//                ]
//            ],
//            ['label' => '修改密码', 'url' => ['/admin/edit',"id"=>Yii::$app->user->id]],
//            ['label' => '退出('.Yii::$app->user->identity->username.')', 'url' => ['/admin/logout'],
//        ]];

    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
