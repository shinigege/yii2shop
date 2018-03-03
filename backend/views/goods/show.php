<?php
?>
<h2><?=\backend\models\Goods::getType()[$model->brand_id]?>牌 <?=$model->name?></h2><br>
<p><?=$content->content?></p>
<a href="<?=\yii\helpers\Url::to(['goods/index'])?>" class="btn btn-primary">返回</a>