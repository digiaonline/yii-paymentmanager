<?php

class m140218_112000_alter_payment_contact_table extends CDbMigration
{
    public function up()
    {
        $this->alterColumn('payment_contact', 'phoneNumber', 'VARCHAR(255) NULL DEFAULT NULL AFTER `email`');
    }

    public function down()
    {
        $this->alterColumn('payment_contact', 'phoneNumber', 'VARCHAR(255) NOT NULL AFTER `email`');
    }
}