<?php

$table_name = $wpdb->prefix . 'drawers';
//$charset_collate = $wpdb->get_charset_collate();

//$your_db_name = 'plankeye_woo';
 
// function to create the DB / Options / Defaults					
function invoice_history_hook() {
   	global $wpdb;
  	global $table_name; 
    $table_name = $wpdb->prefix . 'invoice';
	// create the ECPT metabox database table
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
	{
        $charset_collate = $wpdb->get_charset_collate();
		  $sql = "CREATE TABLE `" . $table_name . "` (
        `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
        `invoice_date` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '0001-01-01',
        `invoice_name` varchar(32) CHARACTER SET utf8 NOT NULL,
        `invoice_purchase_date` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '0001-01-01',
        `invoice_dist` varchar(32) CHARACTER SET utf8 NOT NULL,
        `invoice_dist_order_count` int(3) unsigned zerofill DEFAULT NULL,
        `invoice_import_date` date NOT NULL DEFAULT '0001-01-01',
        `invoice_total` varchar(32) CHARACTER SET utf8 NOT NULL,
        `invoice_shipping` varchar(32) CHARACTER SET utf8 NOT NULL,
        `invoice_payment` varchar(32) CHARACTER SET utf8 NOT NULL,
        `invoice_comments` mediumtext CHARACTER SET utf8 DEFAULT NULL,
        PRIMARY KEY (`invoice_id`)
          )" . $charset_collate . ";";
        echo '$sql is ' . $sql; 
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
 
}

function invoice_history_data() {
    global $wpdb;
    $welcome_name = "Mr. WordPress";
    $welcome_text = "Congratulations, you just completed the Invoice History installation!";
    $table_name = $wpdb->prefix . "drawers";
    $rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );
    }

// run the install scripts upon plugin activation
/*register_activation_hook(__FILE__,'invoice_history_hook');
/*register_activation_hook(__FILE__,'invoice_history_data');
*/









