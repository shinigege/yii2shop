<?php
/**
 ** @var  $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'parent_id')->hiddenInput();
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', ['depends' => \yii\web\JqueryAsset::className()]);
$this->registerJs(
    <<<JS
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
		      // console.log(treeNode);
		      $('#goodscategory-parent_id').val(treeNode.id);
		}
	}
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes ={$type};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            var node = zTreeObj.getNodeByParam("id", "{$model->parent_id}", null);
            	zTreeObj.selectNode(node);

            zTreeObj.expandAll(true);
JS

);
echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;


echo $form->field($model, 'intro')->textarea();
echo '<button type="submit" class="btn btn-primary">添加</button>';
\yii\bootstrap\ActiveForm::end();