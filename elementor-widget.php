<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register the widget after Elementor is loaded
function register_content_block_widget( $widgets_manager ) {

    // Include the widget file
    require_once( __DIR__ . '/widgets/content-block-widget.php' );

    // Register the widget
    $widgets_manager->register( new \Content_Block_Widget() );

}
add_action( 'elementor/widgets/register', 'register_content_block_widget' );