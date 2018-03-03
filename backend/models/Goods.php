<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名称
 * @property string $sn 商品货号
 * @property string $logo LOGO图片
 * @property int $goods_category_id 商品分类id
 * @property int $brand_id 品牌分类
 * @property string $market_price 市场价格
 * @property string $shop_price 商品价格
 * @property int $stock 库存
 * @property int $is_on_sale 是否在售 1在售 0下架
 * @property int $status 状态 1正常 0被删除
 * @property int $sort 排序
 * @property int $create_time 添加时间
 * @property int $view_times 浏览次数
 */
class Goods extends \yii\db\ActiveRecord
{
    public $s_name;
    public $s_money;
    public $m_money;
    public $s_sn;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '商品货号',
            'logo' => 'LOGO图片',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态 1正常 0被删除',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
            's_name'=>'',
            's_sn'=>'',
            's_money'=>'',
            'm_money'=>'',
        ];
    }
    public static function getType(){
        $types=Brand::find()->all();
        $arr =['--请选择分类--'];
        foreach ($types as $type){
            $arr[$type->id]=$type->name;
        }
//        var_dump($arr);exit();
        return $arr;
    }

    public static function getCategory(){
        $types=GoodsCategory::find()->all();
        $arr =[];
        foreach ($types as $type){
            $arr[$type->id]=$type->name;
        }
        return $arr;
    }
}
