<?php

class m131205_094734_create_payment_transaction_table extends CDbMigration
{
    public function up()
    {
        $this->execute(
            "CREATE TABLE `payment_transaction` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `gateway` VARCHAR(255) NOT NULL,
                `orderIdentifier` VARCHAR(255) NOT NULL,
                `userIdentifier` VARCHAR(255) NULL DEFAULT NULL,
                `shippingContactId` INT UNSIGNED NULL DEFAULT NULL,
                `billingContactId` INT UNSIGNED NULL DEFAULT NULL,
                `referenceNumber` VARCHAR(255) NULL DEFAULT NULL,
                `description` VARCHAR(255) NOT NULL,
                `currency` VARCHAR(255) NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `status` TINYINT(4) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                UNIQUE KEY `orderIdentifier` (`orderIdentifier`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );
    }

    public function down()
    {
        $this->dropTable('payment_transaction');
    }
}