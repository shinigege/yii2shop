<?php
/**
 * Created by PhpStorm.
 * User: 是你哥哥
 * Date: 2018/2/26
 * Time: 17:54
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort')->textInput();
echo '<button type="submit" class="btn btn-primary">添加</button>';
\yii\bootstrap\ActiveForm::end();