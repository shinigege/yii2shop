<?php
$form = \yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textInput(['readonly'=>true,'value'=>$permission->name]);
echo $form->field($model,'description')->textInput(['value'=>$permission->description]);
echo '<button type="submit" class="btn btn-info">修改</button>';
\yii\bootstrap\ActiveForm::end();