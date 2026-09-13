<?php

use console\components\CustomMigration;
use yii\db\Migration;

/**
 * Creates the `{{%book_author}}` junction table (many-to-many between books and authors).
 */
class m260912_160200_create_book_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_author}}', [
            'book_id' => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);
        $this->addPrimaryKey('pk_book_author', '{{%book_author}}', ['book_id', 'author_id']);
        $this->addForeignKey('fk_book_author_to_book', '{{%book_author}}', 'book_id', '{{%book}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_book_author_to_author', '{{%book_author}}', 'author_id', '{{%author}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%book_author}}');
    }
}
