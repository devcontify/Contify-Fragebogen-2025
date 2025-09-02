<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

$table_name = $wpdb->prefix . 'con_fra_2025_submissions';

$wpdb->query("DROP TABLE IF EXISTS $table_name");

delete_option('con_fra_2025_settings');

$wpdb->query(
    $wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        'con_fra_2025_%'
    )
);