<?php

class m131205_094734_create_payment_transaction_table extends CDbMigration
{
    public function up()
    {
        $this->execute(
            "CREATE TABLE `payment_transaction` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `methodId` INT UNSIGNED NOT NULL,
                `orderIdentifier` VARCHAR(255) NOT NULL,
                `userIdentifier` VARCHAR(255) NULL DEFAULT NULL,
                `shippingContactId` INT UNSIGNED NULL DEFAULT NULL,
                `billingContactId` INT UNSIGNED NULL DEFAULT NULL,
                `referenceNumber` VARCHAR(255) NULL DEFAULT NULL,
                `description` VARCHAR(255) NOT NULL,
                `currency` VARCHAR(255) NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `status` TINYINT(4) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );
        $this->addForeignKey('payment_transaction_methodId', 'payment_transaction', 'methodId', 'payment_method', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('payment_transaction_methodId', 'payment_transaction');
        $this->dropTable('payment_transaction');
    }
}