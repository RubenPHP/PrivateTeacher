<?php

use yii\db\Schema;
use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $this->execute("
          CREATE TABLE `currency` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `symbol` char(1) CHARACTER SET latin1 NOT NULL,
              `iso_name` char(3) CHARACTER SET latin1 NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
          CREATE TABLE `language` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(150) NOT NULL,
              `code` char(6) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
         CREATE TABLE `user` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
              `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `status` smallint(6) NOT NULL DEFAULT '10',
              `is_admin` tinyint(1) NOT NULL DEFAULT '0',
              `created_at` int(11) NOT NULL,
              `updated_at` int(11) NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `username` (`username`),
              UNIQUE KEY `email` (`email`),
              UNIQUE KEY `password_reset_token` (`password_reset_token`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ");

        $this->execute("
          CREATE TABLE `user_profile` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL,
              `name` varchar(255) CHARACTER SET latin1 NOT NULL,
              `lastname` varchar(255) CHARACTER SET latin1 NOT NULL,
              `lesson_cost` decimal(5,2) NOT NULL DEFAULT '0.00',
              `language_id` int(11) NOT NULL,
              `currency_id` int(11) NOT NULL,
              `created_at` int(10) unsigned NOT NULL,
              `updated_at` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `user_id_unique` (`user_id`),
              KEY `currency_id` (`currency_id`),
              KEY `language_id` (`language_id`),
              CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`),
              CONSTRAINT `user_profile_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`),
              CONSTRAINT `user_profile_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
              CONSTRAINT `user_profile_ibfk_4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
          CREATE TABLE `student` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL COMMENT 'student''s teacher id',
              `name` varchar(255) CHARACTER SET latin1 NOT NULL,
              `lastname` varchar(255) CHARACTER SET latin1 NOT NULL,
              `email` varchar(255) CHARACTER SET latin1 NOT NULL,
              `avatar` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
              `lesson_cost` decimal(5,2) DEFAULT NULL,
              `is_active` tinyint(1) NOT NULL DEFAULT '1',
              `created_by` int(11) NOT NULL,
              `updated_by` int(11) NOT NULL,
              `created_at` int(10) unsigned NOT NULL,
              `updated_at` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id`),
              KEY `user_id` (`user_id`),
              KEY `created_by` (`created_by`),
              KEY `updated_by` (`updated_by`),
              CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
              CONSTRAINT `student_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
              CONSTRAINT `student_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
          CREATE TABLE `student_appointment` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `student_id` int(11) NOT NULL,
              `week_day` int(1) NOT NULL,
              `begin_time` int(10) unsigned NOT NULL,
              `end_time` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id`),
              KEY `student_id` (`student_id`),
              CONSTRAINT `student_appointment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
          CREATE TABLE `payment` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL,
              `student_id` int(11) NOT NULL,
              `amount` decimal(5,2) NOT NULL,
              `date_time` int(10) unsigned NOT NULL,
              `created_by` int(11) NOT NULL,
              `updated_by` int(11) NOT NULL,
              `created_at` int(10) unsigned NOT NULL,
              `updated_at` int(10) unsigned NOT NULL,
              PRIMARY KEY (`id`),
              KEY `student_id` (`student_id`),
              KEY `created_by` (`created_by`),
              KEY `updated_by` (`updated_by`),
              KEY `user_id` (`user_id`),
              CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
              CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`),
              CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`),
              CONSTRAINT `payment_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    public function down()
    {

    }
}
