<?php

class m131205_143825_alter_payment_transaction_table extends CDbMigration
{
	public function up()
	{
        $this->addForeignKey('payment_transaction_shippingContactId', 'payment_transaction', 'shippingContactId', 'payment_contact', 'id');
        $this->addForeignKey('payment_transaction_billingContactId', 'payment_transaction', 'billingContactId', 'payment_contact', 'id');
	}

	public function down()
	{
        $this->dropForeignKey('payment_transaction_billingContactId', 'payment_transaction');
        $this->dropForeignKey('payment_transaction_shippingContactId', 'payment_transaction');
	}
}