<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "goods_intro".
 *
 * @property int $goods_id 商品ID
 * @property string $content 商品描述
 */
class GoodsIntro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_intro';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'goods_id' => '商品ID',
            'content' => '商品描述',
        ];
    }
}
