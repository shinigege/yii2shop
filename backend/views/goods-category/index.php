<?php
/* @var $this yii\web\View */
?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>商品分类ID</th>
        <th>分类名</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $row):?>
        <tr>
            <td><?=$row->id?></td>
            <td><?php for($i =0;$i<$row->depth;$i++){echo '一 ';}echo $row->name?></td>
            <td><?=$row->intro?></td>
            <td><?php echo \yii\bootstrap\Html::a("删除",["goods-category/delete","id"=>$row->id],["class"=>"btn btn-warning"])?>
                <?php echo \yii\bootstrap\Html::a("修改",["goods-category/edit","id"=>$row->id],["class"=>"btn btn-warning"])?></td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td><a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>" class="btn btn-primary">添加</a></td>
    </tr>

</table>

