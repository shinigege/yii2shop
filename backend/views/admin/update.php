<?php
$form = \yii\widgets\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'email')->textInput();
echo $form->field($model,'arr')->checkboxList(\backend\models\Role::getRole());

//上传头像
echo "<button type='submit' class='btn btn-primary'>修改</button>";


\yii\widgets\ActiveForm::end();