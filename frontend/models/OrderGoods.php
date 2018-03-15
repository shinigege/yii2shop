<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order_goods".
 *
 * @property int $id
 * @property int $order_id 订单ID
 * @property int $goods_id 商品ID
 * @property string $goods_name 商品名
 * @property string $logo 图片
 * @property string $price 价格
 * @property int $amount 数量
 * @property string $total 小计
 */
class OrderGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'amount'], 'integer'],
            [['price', 'total'], 'number'],
            [['goods_name', 'logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单ID',
            'goods_id' => '商品ID',
            'goods_name' => '商品名',
            'logo' => '图片',
            'price' => '价格',
            'amount' => '数量',
            'total' => '小计',
        ];
    }
}
