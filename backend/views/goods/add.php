<?php
/**
 ** @var  $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'logo')->hiddenInput();//使用插件自动上传
//============================ 文件上传插件 =======================================
//上传插件
//加载css和js
$this->registerCssFile('@web/webuploader-0.1.5/webuploader.css');
$this->registerJsFile('@web/webuploader-0.1.5/webuploader.js',['depends'=>['yii\web\JqueryAsset']]);
$url=\yii\helpers\Url::to(['brand/update']);
echo <<<HTML
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
    
</div>
HTML;
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
    $('#showImg').attr('src',path);//图片回显
});
JS

);
if ($model->getIsNewRecord()){
    echo '<img id="showImg" src=""/>';
}else{
    echo '<img id="showImg" src="'.$model->logo.'"/>';
}




echo $form->field($model, 'goods_category_id')->hiddenInput();//商品分类 使用ztree
//=============================== ztree ================================
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJs(
    <<<JS
    // $('#treeDemo_1_span').onclick(function() {
    //   console.log('你不能这样');
    //   return false;
    // });
    // ;
var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
		onClick: function(event, treeId, treeNode) {
		      // console.log(treeNode.id);
		          $('#goods-goods_category_id').val(treeNode.id);         
		}
	}
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes ={$type};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            var node = zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null);
            	zTreeObj.selectNode(node);

            zTreeObj.expandAll(true);
JS

);
echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;





echo $form->field($model, 'brand_id')->dropDownList(\backend\models\Goods::getType());

echo $form->field($model, 'market_price')->textInput();
echo $form->field($model, 'shop_price')->textInput();
echo $form->field($model, 'stock')->textInput();
echo $form->field($model, 'is_on_sale')->radioList(['1'=>'在售','0'=>'下架']);
echo $form->field($model, 'sort')->textInput();
//$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', ['depends' => \yii\web\JqueryAsset::className()]);
//$this->registerJs(
//    <<<JS
//var zTreeObj;
//        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
//        var setting = {
//            data: {
//                simpleData: {
//                    enable: true,
//                    idKey: "id",
//                    pIdKey: "parent_id",
//                    rootPId: 0
//                }
//            },
//            callback: {
//		onClick: function(event, treeId, treeNode) {
//		      // console.log(treeNode);
//		      $('#goodscategory-parent_id').val(treeNode.id);
//		}
//	}
//        };
//        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
//        var zNodes ={$type};
//            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
//            var node = zTreeObj.getNodeByParam("id", "{$model->parent_id}", null);
//            	zTreeObj.selectNode(node);
//
//            zTreeObj.expandAll(true);
//JS
//
//);
//echo <<<HTML
//<div>
//    <ul id="treeDemo" class="ztree"></ul>
//</div>
//HTML;

//==================================== 富文本框插件 ================================
echo $form->field($intro, 'content')->widget('kucha\ueditor\UEditor',[]);
echo '<button type="submit" class="btn btn-primary">添加</button>';
\yii\bootstrap\ActiveForm::end();