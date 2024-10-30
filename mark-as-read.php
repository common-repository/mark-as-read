<?php
/*
	Plugin Name: Mark as Read
	Plugin URI: http://www.gayadesign.com/general/wordpress-plugin-mark-as-read/
	Description: Shows a list of recent changes to posts a registered user hasn't read. Lists the posts with changes (published & updates) and new comments. If both changes apply; both changes are displayed.
	Version: 0.9
	Author: Gaya Kessler
	Author URI: http://www.gayadesign.com
*/

include("installation.php");
include("storage.php");
include("functions.php");

//make install call
register_activation_hook(__FILE__,'mar_install');

//do action when post is published or updated
add_action("save_post", "post_not_read");
add_action("comment_post", "comment_not_read");

//mark as read if opened
add_action('loop_end', 'mark_post_as_read');
?>