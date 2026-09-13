<?php

use yii\db\Migration;

/**
 * Guest: an anonymous visitor identified by a cookie key.
 */
class m260912_150540_create_guest_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%guest}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(64)->notNull(),
            'phone' => $this->string(20)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createIndex('idx_guest_key', '{{%guest}}', 'key', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%guest}}');
    }
}
