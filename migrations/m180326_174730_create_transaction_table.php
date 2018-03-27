<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transaction`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `user`
 */
class m180326_174730_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transaction', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'recipient_id' => $this->integer()->notNull(),
            'amount' => $this->float()->notNull(),
            'created_at' => $this->integer()->null(),
        ]);

        // creates index for column `sender_id`
        $this->createIndex(
            'idx-transaction-sender_id',
            'transaction',
            'sender_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-transaction-sender_id',
            'transaction',
            'sender_id',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `recipient_id`
        $this->createIndex(
            'idx-transaction-recipient_id',
            'transaction',
            'recipient_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-transaction-recipient_id',
            'transaction',
            'recipient_id',
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
            'fk-transaction-sender_id',
            'transaction'
        );

        // drops index for column `sender_id`
        $this->dropIndex(
            'idx-transaction-sender_id',
            'transaction'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-transaction-recipient_id',
            'transaction'
        );

        // drops index for column `recipient_id`
        $this->dropIndex(
            'idx-transaction-recipient_id',
            'transaction'
        );

        $this->dropTable('transaction');
    }
}
