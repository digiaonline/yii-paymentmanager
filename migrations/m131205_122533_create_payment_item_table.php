<?php

class m131205_122533_create_payment_item_table extends CDbMigration
{
    public function up()
    {
        $this->execute(
            "CREATE TABLE `payment_item` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `transactionId` INT UNSIGNED NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                `code` VARCHAR(255) NULL DEFAULT NULL,
                `quantity` INT UNSIGNED DEFAULT '1',
                `price` DECIMAL(12,2) NOT NULL,
                `vat` DECIMAL(4,2) NOT NULL,
                `discount` DECIMAL(12,2) NOT NULL,
                `status` TINYINT(4) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );
        $this->addForeignKey('payment_item_transactionId', 'payment_item', 'transactionId', 'payment_transaction', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('payment_item_transactionId', 'payment_item');
        $this->dropTable('payment_item');
    }
}