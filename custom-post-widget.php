<?php
/*
 Plugin Name: Custom Post Widget
 Plugin URI: http://www.vanderwijk.com/wordpress/wordpress-custom-post-widget/?utm_source=wordpress&utm_medium=plugin&utm_campaign=custom_post_widget
 Description: Show the content of a custom post of the type 'content_block' in a widget or with a shortcode.
 Version: 2.8.5
 Author: Johan van der Wijk
 Author URI: http://vanderwijk.nl
 Text Domain: custom-post-widget
 Domain Path: /languages
 License: GPL2

 Release notes: Fix for compatibility issue with Slider Revolution plugin
 
 Copyright 2015 Johan van der Wijk
 
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as 
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

// Launch the plugin.
function custom_post_widget_plugin_init() {
	add_action( 'widgets_init', 'custom_post_widget_load_widgets' );
}
add_action( 'plugins_loaded', 'custom_post_widget_plugin_init' );

// Load plugin textdomain.
function custom_post_widget_load_textdomain() {
	load_plugin_textdomain( 'custom-post-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'custom_post_widget_load_textdomain' );

// Loads the widgets packaged with the plugin.
function custom_post_widget_load_widgets() {
	require_once( 'post-widget.php' );
	register_widget( 'custom_post_widget' );
}

require_once( 'meta-box.php' );
require_once( 'popup.php' );
require_once( 'notice.php' );