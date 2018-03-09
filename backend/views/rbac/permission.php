<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo '<button type="submit" class="btn btn-info">添加</button>';
\yii\bootstrap\ActiveForm::end();