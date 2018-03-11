<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180310_024053_create_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->comment('用户ID'),
            'name'=>$this->string()->comment('收货人'),
            'address'=>$this->string(255)->comment('详细地址'),
            'tel'=>$this->string()->comment('手机号码'),
            'cmbProvince'=>$this->string()->comment('省份'),
            'cmbCity'=>$this->string()->comment('城市'),
            'cmbArea'=>$this->string()->comment('区县'),
            'auto'=>$this->integer(1)->comment('默认 1默认 0不默认'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('address');
    }
}
