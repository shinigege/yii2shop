<?php
/**
 **@var $this \yii\web\View
 */

/*
 * <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css">

<!-- jQuery -->
<script type="text/javascript" charset="utf8" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>
 * */
$this->registerCssFile('http://cdn.datatables.net/1.10.15/css/jquery.dataTables.css');
$this->registerJsFile('http://cdn.datatables.net/1.10.15/js/jquery.dataTables.js',['depends'=>['yii\web\JqueryAsset']]);
$this->registerJs(
    <<<JS
$(document).ready( function () {
      $('.display').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
});
    $('#table_id_example').DataTable();
    $('td').delegate('.btn-danger','click',function () {
            if(confirm('你确定要删除吗?')){
                // console.log(111);
             // console.log($(this));//找到父元素
             var tr = $(this).closest('tr');
             var id = {};
             id['key'] = tr.attr('data-field');
             // console.log(id);
             // console.log(tr);

             $.post('delete-role',id,function(re) {
                  if(re){
                      tr.fadeOut();//删除
                  }else {
                   
                  }
             });
           
            }
         });
    
       
} );
JS

);
?>
<table id="table_id_example" class="display">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($model as $key=>$value):?>
    <tr data-field="<?=$key?>">
        <td><?=$key?></td>
        <td><?=$value?></td>
        <td> <?php echo \yii\bootstrap\Html::a("修改",["rbac/update-role","key"=>$key],["class"=>"btn btn-primary"])?>
            <?php echo \yii\bootstrap\Html::a("删除",null,["class"=>"btn btn-danger"])?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>

