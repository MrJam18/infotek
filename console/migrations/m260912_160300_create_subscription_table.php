<?php

use console\components\CustomMigration;
use yii\db\Migration;

/**
 * Creates the `{{%subscription}}` table (guest subscriptions to authors).
 */
class m260912_160300_create_subscription_table extends CustomMigration
{
    protected string $table = 'subscription';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
        ]);
        $this->createForeignId('guest');
        $this->createForeignId('author');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%subscription}}');
    }
}
