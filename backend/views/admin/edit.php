<?php
$form = \yii\widgets\ActiveForm::begin();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'pwd')->passwordInput();
echo $form->field($model,'repwd')->passwordInput();

//上传头像
echo "<button type='submit' class='btn btn-primary'>修改</button>";


\yii\widgets\ActiveForm::end();