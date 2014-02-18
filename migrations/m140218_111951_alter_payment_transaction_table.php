<?php

class m140218_111951_alter_payment_transaction_table extends CDbMigration
{
    public function up()
    {
        $this->alterColumn(
            'payment_transaction',
            'description',
            'VARCHAR(255) NULL DEFAULT NULL AFTER `referenceNumber`'
        );
    }

    public function down()
    {
        $this->alterColumn('payment_transaction', 'description', 'VARCHAR(255) NOT NULL AFTER `referenceNumber`');
    }
}