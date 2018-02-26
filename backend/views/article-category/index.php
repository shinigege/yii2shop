<?php
/* @var $this yii\web\View */
?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>文章ID</th>
        <th>文章名</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $row):?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><?=$row->sort?></td>
            <td><?=$row->is_delete?'已删除':'已上架'?></td>
            <td><?php echo $row->is_delete?\yii\bootstrap\Html::a("恢复",["article-category/delete","id"=>$row->id],["class"=>"btn btn-warning"]):\yii\bootstrap\Html::a("删除",["article-category/delete","id"=>$row->id],["class"=>"btn btn-warning"])?>
                <?php echo $row->is_delete?'':\yii\bootstrap\Html::a("修改",["article-category/edit","id"=>$row->id],["class"=>"btn btn-warning"])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td><a href="<?=\yii\helpers\Url::to(['article-category/add'])?>" class="btn btn-primary">添加</a></td>
    </tr>

</table>
<?php echo \yii\widgets\LinkPager::widget(['pagination'=>$page])?>
