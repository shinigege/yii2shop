<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'parent_id')->dropDownList($parent);
echo $form->field($model,'url')->dropDownList(\backend\models\Role::getUrl());
echo $form->field($model,'sort')->textInput();

echo '<button type="submit" class="btn btn-primary">添加</button>';

\yii\bootstrap\ActiveForm::end();