<?php

class m131205_141808_create_payment_contact_table extends CDbMigration
{
    public function up()
    {
        $this->execute(
            "CREATE TABLE `payment_contact` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `firstName` VARCHAR(255) NOT NULL,
                `lastName` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `phoneNumber` VARCHAR(255) NOT NULL,
                `mobileNumber` VARCHAR(255) NULL DEFAULT NULL,
                `companyName` VARCHAR(255) NULL DEFAULT NULL,
                `streetAddress` TEXT NOT NULL,
                `postalCode` VARCHAR(255) NOT NULL,
                `postOffice` VARCHAR(255) NOT NULL,
                `countryCode` VARCHAR(3) NOT NULL,
                `status` TINYINT(4) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );
    }

    public function down()
    {
        $this->dropTable('payment_contact');
    }
}