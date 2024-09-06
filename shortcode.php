<?php

// Add the ability to display the content block in a reqular post using a shortcode
function custom_post_widget_shortcode ( $atts ) {
	$params = shortcode_atts ( array (
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

	// Sanitize and escape attributes
	$id = sanitize_text_field ( $params['id'] );
	$slug = sanitize_text_field ( $params['slug'] );
	$class = sanitize_text_field ( $params['class'] );
	$suppress_content_filters = sanitize_text_field ( $params['suppress_content_filters'] );
	$featured_image = sanitize_text_field ( $params['featured_image'] );
	$featured_image_size = sanitize_text_field ( $params['featured_image_size'] );
	$title = sanitize_text_field ( $params['title'] );
	$title_tag = strtolower( sanitize_text_field( $params['title_tag'] ) );
	$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div' ); // Add or remove allowed HTML tags as needed
	if ( ! in_array( $title_tag, $allowed_tags ) ) {
		$title_tag = 'h3'; // Default to 'h3' if the specified tag is not allowed
	}
	$markup = sanitize_text_field ( $params['markup'] );
	$template = sanitize_text_field ( $params['template'] );

	if ( $slug ) {
		$block = get_page_by_path ( $slug, OBJECT, 'content_block' );
		if ( $block ) {
			$id = $block->ID;
		}
	}

	$content = "";

	// Attempt to load a template file
	if ( $template != '' ) {

		$located = locate_template( $template );

		$template_in_theme_or_parent_theme = (
			0 === strpos ( realpath ( $located ), realpath ( get_stylesheet_directory() ) ) ||
			0 === strpos ( realpath ( $located ), realpath ( get_template_directory() ) )
		);
		
		if ( $template_in_theme_or_parent_theme ) {
			require_once( $located );
		}

	}

	if ( $id != "" ) {
		$args = array (
			'post__in' => array ( $id ),
			'post_type' => 'content_block',
		);

		$content_post = get_posts ( $args );

		foreach ( $content_post as $post ) :

			if ( isset( $template_in_theme_or_parent_theme ) && $template_in_theme_or_parent_theme === true ) {
				// Template-based content
				$content .= call_user_func ( 'shortcode_template', $post );

			} else {
				// Standard format content
				$content .= '<' . tag_escape ( $markup ) . ' class="'. esc_attr ( $class ) .'" id="custom_post_widget-' . esc_attr ( $id ) . '">';
				if ( $title === 'yes' ) {
					$content .= '<' . tag_escape ( $title_tag ) . '>' . esc_html ( $post->post_title ) . '</' . tag_escape ( $title_tag ) . '>';
				}
				if ( $featured_image === 'yes' ) {
					$content .= get_the_post_thumbnail ( $post->ID, esc_attr ( $featured_image_size ) );
				}
				if ( $suppress_content_filters === 'no' ) {
					$content .= apply_filters ( 'the_content', $post->post_content );
				} else {
					$content .= $post->post_content;
				}
				$content .= '</' . tag_escape ( $markup ) . '>';
			}
		endforeach;
	}

	return $content;
}
add_shortcode ( 'content_block', 'custom_post_widget_shortcode' );
