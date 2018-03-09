<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m180308_033254_create_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('菜单名'),
            'parent_id'=>$this->integer()->comment('上级菜单'),
            'url'=>$this->string()->comment('地址/路由'),
            'sort'=>$this->integer()->comment('排序'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('menu');
    }
}
