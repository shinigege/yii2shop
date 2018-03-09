<?php
/* @var $this yii\web\View */
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


?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>商品分类ID</th>
        <th>分类名</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $row):?>
        <tr data-field="<?=$row->id?>">
            <td><?=$row->id?></td>
            <td><?php for($i =0;$i<$row->depth;$i++){echo '一 ';}echo $row->name?></td>
            <td><?=$row->intro?></td>
            <td><?php echo \yii\bootstrap\Html::a("删除",null,["class"=>"btn btn-danger"])?>
                <?php echo \yii\bootstrap\Html::a("修改",["goods-category/edit","id"=>$row->id],["class"=>"btn btn-warning"])?></td>
        </tr>
    <?php endforeach;?>

</table>

