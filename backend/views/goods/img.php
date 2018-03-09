<?php
/**
 * Created by PhpStorm.
 * User: 是你哥哥
 * Date: 2018/3/3
 * Time: 10:56
 */
$form = \yii\bootstrap\ActiveForm::begin();
//============================ 文件上传插件 =======================================
//上传插件
//加载css和js
$this->registerCssFile('@web/webuploader-0.1.5/webuploader.css');
$this->registerJsFile('@web/webuploader-0.1.5/webuploader.js', ['depends' => ['yii\web\JqueryAsset']]);
$url = \yii\helpers\Url::to(['brand/update']);
echo <<<HTML
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
HTML;
echo \yii\bootstrap\Html::a("返回", ["goods/index"], ["class" => "btn btn-primary"]);
$this->registerJs(
    <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf: '/web/webuploader-0.1.5/Uploader.swf',

    // 文件接收服务端。
    server: '{$url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/gif,image/jpg,image/jpeg,image/png,'
    }
});
//文件上传成功
uploader.on( 'uploadSuccess', function( file,response ) {
    $( '#'+file.id ).addClass('upload-state-done');
    var path = response.path;
    $('#goods-logo').val(path);
    var data = {};
    var id = $id;
    data['path']=response.path;
    data['id']={$id};
    $.post("save",data,function(re) {

    },'json');
    location.reload();
});

 // $("div").delegate('img','click',function(v) {
 //        console.log(v);
 //        alert('你确定吗');
 // })
JS
);

\yii\bootstrap\ActiveForm::end();
?>

<?php foreach ($imgs as $img):?>
<p data-field="<?=$img['id']?>">
        <img src="<?=$img['path']?>"id="<?=$img['id']?>" alt="">
    <?php echo \yii\bootstrap\Html::a("删除", null, ["class" => "btn btn-danger"]) ?>
</p>
<?php endforeach;?>
<?php
$this->registerJs(<<<js
         $('p').delegate('.btn-danger','click',function () {
            if(confirm('你确定删除吗?')){
                // console.log(111);
             console.log($(this));//找到自己
             var tr = $(this).closest('p');
             var id = {};
             id['id'] = tr.attr('data-field');
             // console.log(id);
             // console.log(tr);
             
             $.post('deleteimg',id,function(re) {
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

