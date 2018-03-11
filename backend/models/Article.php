<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $is_delete
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'is_delete', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类ID',
            'sort' => '排序',
            'is_delete' => '文章状态 0 正常 1 被删除',
            'create_time' => '创建时间',
        ];
    }
    public static function getType(){
        $types=ArticleCategory::find()->all();
        $arr =[];
        foreach ($types as $type){
            $arr[$type->id]=$type->name;
        }
        return $arr;
    }
}
