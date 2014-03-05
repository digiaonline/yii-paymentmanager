<?php

class m140304_140614_alter_payment_transaction_table extends CDbMigration
{
    public function up()
    {
        $this->addColumn('payment_transaction', 'context', 'VARCHAR(255) NOT NULL AFTER `id`');
    }

    public function down()
    {
        $this->dropColumn('payment_transaction', 'context');
    }
}