<?php
/* @var $this yii\web\View */
//var_dump( );exit();
$form = \yii\bootstrap\ActiveForm::begin(['method' => 'get','layout'=>'inline']);
            echo $form->field($select, 's_name')->textInput(['style' => 'width:100%', 'placeholder' => "商品名",'value'=>"{$arr['name']}"]);
            echo $form->field($select, 's_sn')->textInput(['style' => 'width:100%', 'placeholder' => "货号",'value'=>"{$arr['s_sn']}"]);
            echo $form->field($select, 's_money')->textInput(['style' => 'width:100%', 'placeholder' => "最小金额",'value'=>"{$arr['s_money']}"]);
            echo $form->field($select, 'm_money')->textInput(['style' => 'width:100%', 'placeholder' => "最大金额",'value'=>"{$arr['m_money']}"]);
echo '<button type="submit" class="btn btn-primary">搜索</button>';
\yii\bootstrap\ActiveForm::end() ?>

<table class="table table-bordered table-responsive">
    <tr>
        <th>商品ID</th>
        <th>商品名</th>
        <th>货号</th>
        <th>品牌</th>
        <th>商品类别</th>
        <th>创建时间</th>
        <th>市场价格</th>
        <th>本店价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>浏览次数</th>
        <th>LOGO</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $row): ?>
        <tr>
            <td><?= $row->id ?></td>
            <td><?= $row->name ?></td>
            <td><?= $row->sn ?></td>
            <td><?= \backend\models\Goods::getType()[$row->brand_id] ?></td>
            <td><?= \backend\models\Goods::getCategory()[$row->goods_category_id] ?></td>
            <td><?= date('Y-m-d H:i:s', $row->create_time) ?></td>
            <td><?= $row->market_price ?>元</td>
            <td><?= $row->shop_price ?>元</td>
            <td><?= $row->stock ?></td>
            <td><?= $row->is_on_sale ? '在售' : '下架' ?></td>
            <td><?= $row->view_times ?></td>
            <td><img src="<?= $row->logo ?>" alt=""></td>
            <td><?php echo $row->status == 0 ? \yii\bootstrap\Html::a("恢复", ["goods/delete", "id" => $row->id], ["class" => "btn btn-warning"]) : \yii\bootstrap\Html::a("删除", ["goods/delete", "id" => $row->id], ["class" => "btn btn-danger"]) ?>
                <?php echo $row->status == 0 ? '' : \yii\bootstrap\Html::a("修改", ["goods/edit", "id" => $row->id], ["class" => "btn btn-info"]) ?>
                <?php echo $row->status == 0 ? '' : \yii\bootstrap\Html::a("查看", ["goods/show", "id" => $row->id], ["class" => "btn btn-primary"]) ?>
                <?php echo $row->status == 0 ? '' : \yii\bootstrap\Html::a("相册", ["goods/img", "id" => $row->id], ["class" => "btn btn-primary"]) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <tr>

        <td><?php
//            var_dump(strstr($_SERVER['REQUEST_URI'], '/goods/index'));//检测字符串中是否有该特定字符串
            echo  strstr($_SERVER['REQUEST_URI'], '/goods/index')? \yii\bootstrap\Html::a("回收站", ["goods/recovery"], ["class" => "btn btn-warning"]) . '<td/>' . \yii\bootstrap\Html::a("添加", ["goods/add"], ["class" => "btn btn-primary"]) : \yii\bootstrap\Html::a("商品", ["goods/index"], ["class" => "btn btn-primary"]) ?>
        </td>
    </tr>

</table>
<?php echo \yii\widgets\LinkPager::widget(['pagination' => $page]) ?>
