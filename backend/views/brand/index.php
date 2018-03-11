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
        <tr data-field="<?=$row->id?>">
            <td><?=$row->id?></td>
            <td><?=$row->name?></td>
            <td><?=$row->intro?></td>
            <td><img src="<?=$row->logo?>" alt="" style="width: 20px"></td>
            <td><?=$row->sort?></td>
            <td><?=$row->is_delete?'已删除':'已上架'?></td>
            <td><?php echo $row->is_delete?\yii\bootstrap\Html::a("恢复",["brand/delete","id"=>$row->id],["class"=>"btn btn-warning"]):\yii\bootstrap\Html::a("删除",null,["class"=>"btn btn-danger"])?>
                <?php echo $row->is_delete==false&&Yii::$app->user->can('brand/edit')?\yii\bootstrap\Html::a("修改",["brand/edit","id"=>$row->id],["class"=>"btn btn-warning"]):''?></td>
        </tr>
    <?php endforeach;?>


</table>
<?php echo \yii\widgets\LinkPager::widget(['pagination'=>$page]);
$this->registerJs(<<<js
         $('td').delegate('.btn-danger','click',function () {
            if(confirm('你确定删除吗?')){
                // console.log(111);
             // console.log($(this));//找到父元素
             var tr = $(this).closest('tr');
             var id = {};
             id['id'] = tr.attr('data-field');
             // console.log(id);
             // console.log(tr);
             
             $.post('deletes',id,function() {
               tr.fadeOut();//删除
               
               
             });
             // var id = {};
             // id['id'] = tr.find('td[data-field=goods]');
             // console.log(id);

            
                //    alert("这里确定删除");

            }else {
                // console.log(222)
                //    alert("这里取消删除");

            }
         })
js

);

