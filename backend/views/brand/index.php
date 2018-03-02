<?php
/* @var $this yii\web\View */
?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>品牌ID</th>
        <th>品牌名称</th>
        <th>简介</th>
        <th>LOGO图</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brand as $row):?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><img src="<?=$row->logo?>" alt="" style="width: 20px"></td>
            <td><?=$row->sort?></td>
            <td><?=$row->is_delete?'已删除':'已上架'?></td>
            <td><?php echo $row->is_delete?\yii\bootstrap\Html::a("恢复",["brand/delete","id"=>$row->id],["class"=>"btn btn-warning"]):\yii\bootstrap\Html::a("删除",["brand/delete","id"=>$row->id],["class"=>"btn btn-warning"])?>
                <?php echo $row->is_delete?'':\yii\bootstrap\Html::a("修改",["brand/edit","id"=>$row->id],["class"=>"btn btn-warning"])?></td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td><a href="<?=\yii\helpers\Url::to(['brand/add'])?>" class="btn btn-primary">添加</a></td>
        <td><a href="<?=\yii\helpers\Url::to(['brand/recovery'])?>" class="btn btn-primary">回收站</a></td>
        <td><a href="<?=\yii\helpers\Url::to(['brand/index'])?>" class="btn btn-primary">品牌</a></td>
    </tr>

</table>
<?php echo \yii\widgets\LinkPager::widget(['pagination'=>$page])?>

