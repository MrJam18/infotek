<?php

use console\components\CustomMigration;
use yii\db\Migration;

/**
 * Creates the `{{%book}}` table.
 */
class m260912_160100_create_book_table extends CustomMigration
{
    protected string $table = 'book';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(13)->notNull(),
            'photo' => $this->string(255)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createForeignId('user');
        $this->createIndex('idx_book_isbn', '{{%book}}', 'isbn', true);
        $this->createIndex('idx_book_year', '{{%book}}', 'year');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book}}');
    }
}
