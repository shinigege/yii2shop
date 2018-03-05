<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180227_035049_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'article_category_id'=>$this->integer(11)->comment('文章分类ID'),
            'sort'=>$this->integer(11)->comment('排序'),
            'is_delete'=>$this->integer(1)->comment('文章状态 0 正常 1 被删除'),
            'create_time'=>$this->integer(11)->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
