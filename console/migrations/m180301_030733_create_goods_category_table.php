<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m180301_030733_create_goods_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            /*
             *
字段名	类型	注释
id	primaryKey
tree	int()	树id
lft	int()	左值
rgt	int()	右值
depth	int()	层级
name	varchar(50)	名称
parent_id	int()	上级分类id
intro	text()	简介*/
            'tree'=>$this->integer(),
            'lft'=>$this->integer(),
            'rgt'=>$this->integer(),
            'depth'=>$this->integer(),
            'name'=>$this->string(50)->comment('商品分类名称'),
            'parent_id'=>$this->integer()->comment('上级分类ID'),
            'intro'=>$this->text()->comment('简介'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_category');
    }
}
