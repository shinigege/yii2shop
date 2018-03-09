<?php
/**
 * Created by PhpStorm.
 * User: 是你哥哥
 * Date: 2018/3/7
 * Time: 14:52
 */
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

class Filter extends ActionFilter{
    public function beforeAction($action)
    {
        $resutl = \Yii::$app->user->can($action->uniqueId);
        if(!$resutl){//未通过
            if(\Yii::$app->user->isGuest){//如果未登录
                    return $action->controller->redirect(['admin/login'])->send();
            }
            throw new HttpException(403,'无权限访问,请登录或切换账号');
        }
        return true;
    }
}