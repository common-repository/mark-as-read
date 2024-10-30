<?php
	function mar_install() {
		global $wpdb;
	
		$table_name = get_table_name();
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
	      
			$sql = "CREATE TABLE " . $table_name . " (
			userid int(11) NOT NULL,
			postid int(11) NOT NULL,
			type varchar(255) NOT NULL,
			PRIMARY KEY (userid, postid, type)
			)";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
		}
	}
?>