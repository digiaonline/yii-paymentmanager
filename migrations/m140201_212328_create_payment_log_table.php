<?php

class m140201_212328_create_payment_log_table extends CDbMigration
{
	public function up()
	{
        $this->execute(
            "CREATE TABLE `payment_log` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `transactionId` INT UNSIGNED NOT NULL,
                `transactionStatus` TINYINT(4) NOT NULL,
                `createdAt` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );
        $this->addForeignKey('payment_log_transactionId', 'payment_log', 'transactionId', 'payment_transaction', 'id');
	}

	public function down()
	{
        $this->dropForeignKey('payment_log_transactionId', 'payment_log');
		$this->dropTable('payment_log');
	}
}