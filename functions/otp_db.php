<?php


/*
 * Phone Number table
 */
function phone_number_db_table()
{
    // change the version to update db structure
    $phone_number_db_version = "1.0";
    if ($phone_number_db_version != get_option("phone_number_db_version")) {
        global $wpdb;

        $table_phone_numbers = $wpdb->prefix . 'otp';
        $sql = "CREATE TABLE $table_phone_numbers (
			`id` INTEGER (10) NOT NULL AUTO_INCREMENT,
			`date` DATETIME NOT NULL,
			`phone_number` double NOT NULL UNIQUE,
			`verfication_key` double NOT NULL,
			`status` varchar(255) DEFAULT 0,
			PRIMARY KEY (`id`))";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        update_option('phone_number_db_version', $phone_number_db_version);
    }
}

add_action('after_setup_theme', 'phone_number_db_table');