<?php

use Phinx\Migration\AbstractMigration;

class QontentMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('qo_posts');

        $table->addColumn("title", "string", ["length" => "200"]);
        $table->addColumn("content", "string", ["length" => "60"]);
        $table->addColumn("tags", "string", ["length" => "60"]);
        $table->addColumn("uuid", "string", ["length" => "36"]);
        $table->addColumn("created_at", "datetime");
        $table->addColumn("updated_at", "datetime");
        $table->create();
    }
}
