<?php

/* Display a notice that can be dismissed */

function cpw_admin_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	$screen = get_current_screen();
	if ( ! get_user_meta($user_id, 'cpw_notice_hidden') && ( $screen->id == 'edit-content_block' || $screen->id == 'content_block' ) ) {
		echo '<div class="updated" style="border-color: #00b1ff;"><p>'; 
		printf(__('Thank you for using the Custom Post Widget plugin. Visit the <a href="http://www.vanderwijk.com/wordpress/wordpress-custom-post-widget/?utm_source=wordpress&utm_medium=plugin&utm_campaign=custom_post_widget" target="_blank">plugin website</a> to find out more about using this plugin. <a href="%1$s" style="float:right;">Hide Notice</a>'), '?post_type=content_block&cpw_hide_notice=yes');
		echo "</p></div>";
	}
}
add_action('admin_init', 'cpw_hide_notice');

function cpw_hide_notice() {
	global $current_user;
	$user_id = $current_user->ID;
	if ( isset($_GET['cpw_hide_notice']) && 'yes' == $_GET['cpw_hide_notice'] ) {
		add_user_meta($user_id, 'cpw_notice_hidden', 'yes', true);
	}
}
add_action('admin_notices', 'cpw_admin_notice');