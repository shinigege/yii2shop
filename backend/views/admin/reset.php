<?php
$form = \yii\widgets\ActiveForm::begin();
echo $form->field($model,'pwd')->passwordInput();
echo "<button type='submit' class='btn btn-primary'>修改</button>";


\yii\widgets\ActiveForm::end();