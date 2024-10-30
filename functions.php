<?php

	/*
	 * Output functions
	 */
	
	//get all unread posts in array
	function get_unread_posts($category = "all") {
		if ($category == "all") {
			$posts = get_posts(
				array(
					'orderby' => 'modified'
				)
			);
		} else {
			$posts = get_posts(
				array(
					'orderby' => 'modified',
					'category' => $category
				)
			);
		}
		
		$return = array();
		
		//check all posts for changes
		foreach($posts as $post) :
			$info = get_object_vars($post);

			$postid = $info['ID'];
			global $current_user;
     		get_currentuserinfo();
			
     		$type = check_read_post($current_user->ID, $postid);
     		
			if ($type != false) {
				$insert = array();
				$insert['post'] = $post;
				$insert['type'] = $type;
				
				array_push($return, $insert);
			}
		endforeach;
		
		return $return;
	}
	
	//display the posts in HTML
	function output_unread_posts($posts, $nounread, $updatetext, $row) {
		
		$return = "<ul>";
		
		if (sizeof($posts) == 0) {
			$return .= $nounread;
		}
		
		foreach($posts as $post) :
			$info = get_object_vars($post['post']);

			$postid = $info['ID'];
			
			if (sizeof($post['type']) > 1) {
				$type = $updatetext[0];
			} else if ($post['type'][0] == "post") {
				$type = $updatetext[1];
			} else if ($post['type'][0] == "comment") {
				$type = $updatetext[2];
			}
			
			$permalink = get_permalink($postid);
			$title = $info['post_title'];
			
			$insert = $row;
			$insert = str_replace("%link%", $permalink, $insert);
			$insert = str_replace("%title%", $title, $insert);
			$insert = str_replace("%type%", $type, $insert);
			
			$return .= $insert;
			
		endforeach;
		
		$return .= "</ul>";

		return $return;
	}
	
	//the main function to use in the template
	function the_unread_posts($category = "all", $nounread = "<li>No unread posts</li>", $updatetext = array("New comment(s) and changes", "Changes", "New comment(s)"), $row = "<li><a href='%link%'>%type% on: %title%</a></li>") {
		if (is_user_logged_in()) {
			$posts = get_unread_posts($category);
			echo output_unread_posts($posts, $nounread, $updatetext, $row);	
		}
	}

?>