<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name 菜单名
 * @property int $parent_id 上级菜单
 * @property string $url 地址/路由
 * @property int $sort 排序
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['url','name','parent_id'],'required'],
            [['name'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
            ['parent_id','validateParent'],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名',
            'parent_id' => '上级菜单',
            'url' => '地址/路由',
            'sort' => '排序',
        ];
    }
    public function validateParent(){//不能当自己的父亲
        if($this->parent_id==$this->id){//parent_id = id
            $this->addError('parent_id', '你不能做自己的父亲!');
        }
    }
}
