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
             
             $.post('delete',id,function(re) {
              if(re){
                   tr.fadeOut();//删除
              }
              
             });

            }else {

            }
         })
js

);


?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>分类名</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($model as $row): ?>
        <tr data-field="<?= $row->id ?>">
            <td><?php echo $row->name ?></td>
            <td><?= $row->url ?></td>
            <td><?= $row->sort ?></td>
            <td><?php echo \yii\bootstrap\Html::a("删除", null, ["class" => "btn btn-danger"]) ?>
                <?php echo \yii\bootstrap\Html::a("修改", ["menu/edit", "id" => $row->id], ["class" => "btn btn-warning"]) ?></td>
        </tr>
    <?php endforeach; ?>

</table>
<?php echo \yii\widgets\LinkPager::widget(['pagination' => $page]);

