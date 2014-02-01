<?php

class m131204_140856_create_payment_method_table extends CDbMigration
{
    public function up()
    {
        $this->execute(
            "CREATE TABLE `payment_method` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `label` VARCHAR(255) NOT NULL,
                `status` TINYINT(4) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
            ) COLLATE='utf8_general_ci' ENGINE=InnoDB;"
        );
    }

    public function down()
    {
        $this->dropTable('payment_method');
    }
}