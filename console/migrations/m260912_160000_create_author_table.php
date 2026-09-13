<?php

use yii\db\Migration;

/**
 * Creates the `{{%author}}` table.
 */
class m260912_160000_create_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%author}}');
    }
}
