<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $member_id 用户ID
 * @property string $name 收货人
 * @property string $address 详细地址
 * @property string $tel 手机号码
 * @property string $cmbProvince 省份
 * @property string $cmbCity 城市
 * @property string $cmbArea 区县
 * @property int $auto 默认 1默认 0不默认
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'auto'], 'integer'],
            [['name', 'address', 'tel', 'cmbProvince', 'cmbCity', 'cmbArea'], 'string', 'max' => 255],
            ['id','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户ID',
            'name' => '收货人',
            'address' => '详细地址',
            'tel' => '手机号码',
            'cmbProvince' => '省份',
            'cmbCity' => '城市',
            'cmbArea' => '区县',
            'auto' => '默认 1默认 0不默认',
        ];
    }
}
