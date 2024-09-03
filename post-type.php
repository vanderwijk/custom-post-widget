<?php

// Create the Content Block custom post type
function cpw_post_type_init() {
	$labels = array(
		'name' => _x( 'Content Blocks', 'post type general name', 'custom-post-widget' ),
		'singular_name' => _x( 'Content Block', 'post type singular name', 'custom-post-widget' ),
		'plural_name' => _x( 'Content Blocks', 'post type plural name', 'custom-post-widget' ),
		'add_new' => _x( 'Add Content Block', 'block', 'custom-post-widget' ),
		'add_new_item' => __( 'Add New Content Block', 'custom-post-widget' ),
		'edit_item' => __( 'Edit Content Block', 'custom-post-widget' ),
		'new_item' => __( 'New Content Block', 'custom-post-widget' ),
		'view_item' => __( 'View Content Block', 'custom-post-widget' ),
		'search_items' => __( 'Search Content Blocks', 'custom-post-widget' ),
		'not_found' =>  __( 'No Content Blocks Found', 'custom-post-widget' ),
		'not_found_in_trash' => __( 'No Content Blocks found in Trash', 'custom-post-widget' )
	);
	$content_block_public = false; // added to make this a filterable option
	$options = array(
		'labels' => $labels,
		'public' => apply_filters( 'content_block_post_type', $content_block_public ),
		'publicly_queryable' => false,
		'exclude_from_search' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'show_in_rest' => true,
		'hierarchical' => false,
		'menu_icon' => 'dashicons-screenoptions',
		'supports' => array( 'title','editor','revisions','thumbnail','author' )
	);
	register_post_type( 'content_block',$options );
}
add_action( 'init', 'cpw_post_type_init' );

function content_block_messages( $messages ) {
	$messages['content_block'] = array(
		0 => '',
		// translators: Placeholder is the URL for managing widgets.
		1 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Content Block updated. <a href="%s">Manage Widgets</a>', 'custom-post-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Content Block updated.', 'custom-post-widget' ), esc_url( 'widgets.php' ) ),
		// translators: Placeholder is the custom field.
		2 => __( 'Custom field updated.', 'custom-post-widget' ),
		3 => __( 'Custom field deleted.', 'custom-post-widget' ),
		4 => __( 'Content Block updated.', 'custom-post-widget' ),
		// translators: Placeholder is the revision title.
		5 => isset($_GET['revision']) ? sprintf( __( 'Content Block restored to revision from %s', 'custom-post-widget' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		// translators: Placeholder is the URL for managing widgets.
		6 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Content Block published. <a href="%s">Manage Widgets</a>', 'custom-post-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Content Block published.', 'custom-post-widget' ), esc_url( 'widgets.php' ) ),
		7 => __( 'Block saved.', 'custom-post-widget' ),
		// translators: Placeholder is the URL for managing widgets.
		8 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Content Block submitted. <a href="%s">Manage Widgets</a>', 'custom-post-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Content Block submitted.', 'custom-post-widget' ), esc_url( 'widgets.php' ) ),
		// translators: Placeholder is the scheduled date and time.
		9 => sprintf( __( 'Content Block scheduled for: <strong>%1$s</strong>.', 'custom-post-widget' ), date_i18n( __( 'M j, Y @ G:i' , 'custom-post-widget' ), strtotime(isset($post->post_date) ? $post->post_date : '') ), esc_url( 'widgets.php' ) ),
		// translators: Placeholder is the URL for managing widgets.
		10 => current_user_can( 'edit_theme_options' ) ? sprintf( __( 'Content Block draft updated. <a href="%s">Manage Widgets</a>', 'custom-post-widget' ), esc_url( 'widgets.php' ) ) : sprintf( __( 'Content Block draft updated.', 'custom-post-widget' ), esc_url( 'widgets.php' ) ),
	);
	return $messages;
}
add_filter( 'post_updated_messages', 'content_block_messages' );
