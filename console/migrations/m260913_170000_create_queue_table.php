<?php

use yii\db\Migration;

/**
 * Creates the `{{%queue}}` table used by the yii2-queue DB driver.
 */
class m260913_170000_create_queue_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%queue}}', [
            'id' => $this->primaryKey(),
            'channel' => $this->string()->notNull(),
            'job' => $this->binary()->notNull(),
            'pushed_at' => $this->integer()->notNull(),
            'ttr' => $this->integer()->notNull(),
            'delay' => $this->integer()->notNull()->defaultValue(0),
            'priority' => $this->integer()->unsigned()->notNull()->defaultValue(1024),
            'reserved_at' => $this->integer(),
            'attempt' => $this->integer(),
            'done_at' => $this->integer(),
        ]);
        $this->createIndex('channel', '{{%queue}}', 'channel');
        $this->createIndex('reserved_at', '{{%queue}}', 'reserved_at');
        $this->createIndex('priority', '{{%queue}}', 'priority');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%queue}}');
    }
}
