<?php 

	/*
	 * Collection of functions which write to the database
	 */
	
	//function called after loop
	function mark_post_as_read() {
		global $post;
		$postid = $post->ID;
		
		//check if you are on a single page
		if (is_single()) {
			user_read_post($postid);
		}
	}
	
	//delete unread information from database
	function user_read_post($postid) {
		global $current_user;
     	get_currentuserinfo();
		$userid = $current_user->ID;
     	
		global $wpdb;
		$table_name = get_table_name();
		
		$wpdb->query("DELETE FROM " . $table_name . " WHERE postid = " . $postid . " AND userid = " . $userid);
	}
	
	//insert the post that has not been read with COMMENT type, will be fired after a comment post
	function comment_not_read($comment_id, $approved) {
		$comment = get_comment($comment_id); 	
		
		insert_not_read($comment->comment_post_ID, "comment");
	}
	
	//insert the post that has not been read with POST type, will be fired after post publish or update
	function post_not_read($postid) {
		$post = get_post($postid);
		
		insert_not_read($post->ID, "post");
	}
	
	//insert the update into the DB
	function insert_not_read($postid, $type) {
		global $flag;
		global $wpdb;
		$table_name = get_table_name();
		
		$users = get_all_users();
		
		foreach($users as $user) {
			$userid = (int) $user->ID;
		
			$wpdb->query("INSERT INTO " . $table_name . " (userid, postid, type) VALUES (" . $userid . ", " . $postid . ", '" . $type . "')");
		}
	}
	
	//check if a post is unread, else give types of update
	function check_read_post($userid, $postid) {
		global $wpdb;
		$table_name = get_table_name();
		
		$check = $wpdb->get_results("SELECT type FROM " . $table_name . " WHERE userid = " . $userid . " AND postid = " . $postid);
		$return = false;
		
		foreach($check as $result) {
			if (!is_array($return)) {
				$return = array();
			}
			
			array_push($return, $result->type);
		}
		
		return $return;
	}
	
	//get all the users
	function get_all_users() {
		global $wpdb;
		
		return $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");
	}
	
	//get main table name
	function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . "mark_as_read_data";
	}

?>