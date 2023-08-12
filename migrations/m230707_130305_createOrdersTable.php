<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 */
class m230707_130305_createOrdersTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'ticker' => $this->string()->notNull(),
            'owner_id' => $this->integer()->notNull(),
            'side' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'price' => $this->money(),
            'filled' => $this->integer()->defaultValue(0),
            'status' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression('\'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%orders}}');
    }
}
