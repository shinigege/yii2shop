<?php
?>
<h2><?=$model->name?></h2><h6>类型:<?=\backend\models\Article::getType()[$model->article_category_id]?></h6>
内容:<p><?=$content->content?></p>
<a href="<?=\yii\helpers\Url::to(['article/index'])?>" class="btn btn-primary">返回</a>