<?php
$form = \yii\widgets\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'pwd')->passwordInput();
echo $form->field($model,'repwd')->passwordInput();
echo $form->field($model,'email')->textInput();
//上传头像
echo "<button type='submit' class='btn btn-primary'>添加</button>";


\yii\widgets\ActiveForm::end();