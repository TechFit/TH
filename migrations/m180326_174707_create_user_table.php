<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180326_174707_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(128)->notNull()->unique(),
            'created_at' => $this->integer()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
