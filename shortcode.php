<?php

// Add the ability to display the content block in a reqular post using a shortcode
function custom_post_widget_shortcode( $atts ) {
	$params = shortcode_atts( array(
		'id' => '',
		'slug' => '',
		'class' => 'content_block',
		'suppress_content_filters' => 'no',
		'featured_image' => 'no',
		'featured_image_size' => 'medium',
		'title' => 'no',
		'title_tag' => 'h3',
		'markup' => 'div',
		'template' => ''
	), $atts );

	$id = $params['id'];
	$slug = $params['slug'];
	$class = $params['class'];
	$suppress_content_filters = $params['suppress_content_filters'];
	$featured_image = $params['featured_image'];
	$featured_image_size = $params['featured_image_size'];
	$title = $params['title'];
	$title_tag = $params['title_tag'];
	$markup = $params['markup'];
	$template = $params['template'];

	if ( $slug ) {
		$block = get_page_by_path( $slug, OBJECT, 'content_block' );
		if ( $block ) {
			$id = $block->ID;
		}
	}

	$content = "";

	// Attempt to load a template file
	if ( $params['template'] != '' ) {
		if ( $located = locate_template( $params['template'] ) ) {
			include_once $located;
		}
	}

	if ( $id != "" ) {

		$args = array(
			'post__in' => array( $id ),
			'post_type' => 'content_block',
		);

		$content_post = get_posts( $args );

		foreach( $content_post as $post ) :

			if ( isset( $located ) ) {
				// Template-based content
				$content .= call_user_func( 'shortcode_template', $post );

			} else {
				// Standard format content
				$content .= '<' . esc_attr( $markup ) . ' class="'. esc_attr( $class ) .'" id="custom_post_widget-' . $id . '">';
				if ( $title === 'yes' ) {
					$content .= '<' . esc_attr( $title_tag ) . '>' . $post -> post_title . '</' . esc_attr( $title_tag ) . '>';
				}
				if ( $featured_image === 'yes' ) {
					$content .= get_the_post_thumbnail( $post -> ID, $featured_image_size );
				}
				if ( $suppress_content_filters === 'no' ) {
					$content .= apply_filters( 'the_content', $post -> post_content );
				} else {
					$content .= $post -> post_content;
				}
				$content .= '</' .  esc_attr( $markup ) . '>';
			}
		endforeach;

	}

	return $content;
}
add_shortcode( 'content_block', 'custom_post_widget_shortcode' );
