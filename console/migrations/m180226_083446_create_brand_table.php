<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m180226_083446_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('品牌名称'),
            'intro'=>$this->text()->comment('品牌简介'),
            'logo'=>$this->string(255)->comment('品牌商标'),
            'sort'=>$this->integer(11)->comment('排序'),
            'is_delete'=>$this->integer(1)->comment('状态 0->正常 1->删除'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
