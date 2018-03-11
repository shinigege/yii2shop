<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m180309_032154_create_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户名'),//unique 唯一
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull()->comment('密码'),
            'tel'=>$this->char(11)->comment('电话'),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->comment('状态（1正常，0删除）'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'last_login_ip'=>$this->string()->comment('最后登录IP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('member');
    }
}
