<?php
/* @var $this yii\web\View */
//var_dump(\Yii::$app->user->identity);exit();
//$session = Yii::$app->session;
//echo $session['id'];
//echo $session['username'];exit();
$id = \Yii::$app->user->id;//登录的ID
?>
    <table class="table table-bordered table-responsive">
        <tr>
            <th>管理员ID</th>
            <th>管理员姓名</th>
            <th>邮箱</th>
            <th>创建时间</th>
            <th>修改时间</th>
            <th>最后登录时间</th>
            <th>最后登录IP</th>
            <th>操作</th>
        </tr>
        <?php foreach ($model as $row): ?>
            <tr data-field="<?= $row->id ?>">
                <td><?= $row->id ?></td>
                <td><?= $row->username ?></td>
                <td><?= $row->email ?></td>
                <td><?= date('Y-m-d H:i:s', $row->created_at) ?></td>
                <td><?= $row->updated_at == 0 ? '无记录' : date('Y-m-d H:i:s', $row->updated_at) ?></td>
                <td><?= $row->last_login_time ? date('Y-m-d H:i:s', $row->last_login_time) : '无记录' ?></td>
                <td><?= $row->last_login_ip ? $row->last_login_ip : '无记录' ?></td>
                <td><?php echo  Yii::$app->user->can('admin/delete')?\yii\bootstrap\Html::a("删除", null, ["class" => "btn btn-danger"]):'' ?>
                    <?php echo Yii::$app->user->can('admin/update')?\yii\bootstrap\Html::a("修改", ["admin/update", "id" => $row->id], ["class" => "btn btn-primary"]):'' ?>
                    <?php echo Yii::$app->user->can('admin/reset')?\yii\bootstrap\Html::a("改密", ["admin/reset", "id" => $row->id], ["class" => "btn btn-warning"]) : ''//只有id为2的管理员可以修改别人的密码  ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
<?php echo \yii\widgets\LinkPager::widget(['pagination' => $page]);
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

