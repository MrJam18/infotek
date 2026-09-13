<?php

namespace console\components;

use yii\db\ColumnSchemaBuilder;
use yii\db\Migration;

class CustomMigration extends Migration
{
    protected string $table;
    protected function createForeignId(string $refTable, ?ColumnSchemaBuilder $column = null): void
    {
        if (!$column) {
            $column = $this->integer();
        }
        $columnName = $refTable . '_id';
        $name = "fk_$this->table" . "_to_$refTable";
        $this->addColumn($this->table, $columnName, $column);
        $this->addForeignKey($name, $this->table, $columnName, $refTable, 'id');
    }

    protected function dropForeignId(string $refTable): void
    {
        $name = "fk_$this->table" . "_to_$refTable";
        $this->dropForeignKey($name, $this->table);
        $this->dropIndex($name, $this->table);
        $this->dropColumn($this->table, $refTable . '_id');
    }
}