<?php
$form = \yii\widgets\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
echo $form->field($model, 'code')->widget(\yii\captcha\Captcha::className(), [
    'captchaAction'=>'admin/captcha',
    'template' => '<div class="row"><div class="col-lg-3">{input}</div><div class="col-lg-6">{image}</div></div>',
]);
echo $form->field($model,'auto_load')->checkbox(['1'=>'自动登录']);
echo "<button type='submit' class='btn btn-primary'>登录</button>";

\yii\widgets\ActiveForm::end();