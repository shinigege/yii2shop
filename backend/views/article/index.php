<?php
/* @var $this yii\web\View */
?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>文章ID</th>
        <th>文章名</th>
        <th>简介</th>
        <th>分类</th>
        <th>排序</th>
        <th>创建时间</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($article as $row):?>
        <tr>
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><?=\backend\models\Article::getType()[$row->article_category_id]?></td>
            <td><?=$row->sort?></td>
            <td><?=date('Y-m-d H:i:s',$row->create_time)?></td>
            <td><?=$row->is_delete?'已删除':'已上架'?></td>
            <td><?php echo $row->is_delete?\yii\bootstrap\Html::a("恢复",["article/delete","id"=>$row->id],["class"=>"btn btn-warning"]):\yii\bootstrap\Html::a("删除",["article/delete","id"=>$row->id],["class"=>"btn btn-warning"])?>
                <?php echo $row->is_delete?'':\yii\bootstrap\Html::a("修改",["article/edit","id"=>$row->id],["class"=>"btn btn-warning"])?>
                <?php echo $row->is_delete?'':\yii\bootstrap\Html::a("查看",["article/show","id"=>$row->id],["class"=>"btn btn-warning"])?>
            </td>
        </tr>
    <?php endforeach;?>
    <tr>
        <td><a href="<?=\yii\helpers\Url::to(['article/add'])?>" class="btn btn-primary">添加</a></td>
        <td><a href="<?=\yii\helpers\Url::to(['article/recovery'])?>" class="btn btn-primary">回收站</a></td>
        <td><a href="<?=\yii\helpers\Url::to(['article/index'])?>" class="btn btn-primary">文章</a></td>
    </tr>

</table>
<?php echo \yii\widgets\LinkPager::widget(['pagination'=>$page])?>