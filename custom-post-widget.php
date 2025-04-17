<?php
/*
 Plugin Name: Content Blocks (Custom Post Widget)
 Plugin URI: https://vanderwijk.com/wordpress/wordpress-custom-post-widget/?utm_source=wordpress&utm_medium=plugin&utm_campaign=custom_post_widget
 Description: Show the content of a custom post of the type 'content_block' in a widget or with a shortcode.
 Version: 3.3.8
 Author: Johan van der Wijk
 Author URI: https://vanderwijk.nl
 Text Domain: custom-post-widget
 Domain Path: /languages
 License: GPL2

 Release notes: WP 6.8 compatibility tested and confirmed

 Copyright 2025 Johan van der Wijk

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

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Launch the plugin
function custom_post_widget_plugin_init() {
	add_action( 'widgets_init', 'custom_post_widget_load_widgets' );;
}
add_action( 'plugins_loaded', 'custom_post_widget_plugin_init' );

// Loads the widgets packaged with the plugin
function custom_post_widget_load_widgets() {
	require_once( 'post-type.php' );
	require_once( 'shortcode.php' );
	require_once( 'widget.php' );
	register_widget( 'custom_post_widget' );
}

// Include Elementor widget after Elementor is initialized
function cpw_load_elementor_widget() {
    // Check if Elementor is active and loaded
    if ( did_action( 'elementor/loaded' ) ) {
        // Include the Elementor widget file
        require_once( 'elementor-widget.php' );
    }
}
add_action( 'init', 'cpw_load_elementor_widget' );

// Load plugin textdomain
function custom_post_widget_load_textdomain() {
	load_plugin_textdomain( 'custom-post-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'custom_post_widget_load_textdomain' );

// Add featured image support
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}

// Admin-only functions
if ( is_admin() ) {

	// Add donation and review links to plugin description
	if ( ! function_exists ( 'cpw_plugin_links' ) ) {
		function cpw_plugin_links( $links, $file ) {
			$base = plugin_basename( __FILE__ );
			if ( $file == $base ) {
				$links[] = '<a href="https://wordpress.org/support/plugin/custom-post-widget/reviews/" target="_blank">' . __( 'Review', 'custom-post-widget' ) . ' <span class="dashicons dashicons-thumbs-up"></span></a> | <a href="https://paypal.me/vanderwijk">' . __( 'Donate', 'custom-post-widget' ) . ' <span class="dashicons dashicons-money"></span></a>';
			}
			return $links;
		}
	}
	add_filter( 'plugin_row_meta', 'cpw_plugin_links', 10, 2 );

	require_once( 'meta-box.php' );
	require_once( 'popup.php' );

	// Enqueue styles and scripts on content_block edit page
	function cpw_enqueue() {
		$screen = get_current_screen();
		// Check screen base and current post type
		if ( 'post' === $screen -> base && 'content_block' === $screen -> post_type ) {
			wp_enqueue_style( 'cpw-style', plugins_url( '/assets/css/custom-post-widget.css', __FILE__ ), array(), esc_attr( get_plugin_data( __FILE__ )['Version'] ), 'all' );
			wp_enqueue_script( 'clipboard', plugins_url( '/assets/js/clipboard.min.js', __FILE__ ), array(), '2.0.6', true );
			wp_enqueue_script( 'clipboard-init', plugins_url( '/assets/js/clipboard.js', __FILE__ ), array(), esc_attr( get_plugin_data( __FILE__ )['Version'] ), true );
		}
	}
	add_action( 'admin_enqueue_scripts', 'cpw_enqueue' );

	// Only add content_block icon above posts and pages
	function cpw_add_content_block_button() {
		global $current_screen;
		if ( ( 'content_block' != $current_screen -> post_type ) && ( 'toplevel_page_revslider' != $current_screen -> id ) ) {
			add_action( 'media_buttons', 'add_content_block_icon' );
			add_action( 'admin_footer', 'add_content_block_popup' );
		}
	}
	add_action( 'admin_head', 'cpw_add_content_block_button' );

}

// Register the shortcode
function cpw_content_block_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'id' => '',
    ), $atts, 'cpw_content_block' );

    if ( ! empty( $atts['id'] ) ) {
        $post = get_post( $atts['id'] );
        if ( $post ) {
            $content = apply_filters( 'the_content', $post->post_content );
            return $content;
        }
    }
    return '';
}
add_shortcode( 'cpw_content_block', 'cpw_content_block_shortcode' );

// Map the shortcode to WPBakery Page Builder
function cpw_vc_content_block_mapping() {
    // Check if WPBakery Page Builder is installed
    if ( ! defined( 'WPB_VC_VERSION' ) ) {
        return;
    }

    // Get all content blocks
    $content_blocks = get_posts( array(
        'post_type'      => 'content_block',
        'posts_per_page' => -1,
    ) );

    $options = array();
    if ( $content_blocks ) {
        foreach ( $content_blocks as $content_block ) {
            $options[ $content_block->post_title ] = $content_block->ID;
        }
    } else {
        $options[ __( 'No content blocks found', 'custom-post-widget' ) ] = '';
    }

    vc_map( array(
        'name'        => __( 'Content Block', 'custom-post-widget' ),
        'base'        => 'cpw_content_block',
        'description' => __( 'Display a content block', 'custom-post-widget' ),
        'category'    => __( 'Content', 'custom-post-widget' ),
        'params'      => array(
            array(
                'type'        => 'dropdown',
                'heading'     => __( 'Select Content Block', 'custom-post-widget' ),
                'param_name'  => 'id',
                'value'       => $options,
                'admin_label' => true,
                'description' => __( 'Choose which content block to display.', 'custom-post-widget' ),
            ),
        ),
    ) );
}
add_action( 'vc_before_init', 'cpw_vc_content_block_mapping' );
