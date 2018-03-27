<?php

use yii\db\Migration;

/**
 * Handles the creation of table `bill`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180326_174719_create_bill_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('bill', [
            'user_id' => $this->primaryKey(),
            'total' => $this->integer()->defaultValue(0),
            'updated_at' => $this->integer()->null(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-bill-user_id',
            'bill',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-bill-user_id',
            'bill',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-bill-user_id',
            'bill'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-bill-user_id',
            'bill'
        );

        $this->dropTable('bill');
    }
}
