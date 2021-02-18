<?php
if ( !$apply_content_filters ) { // Don't apply the content filter if checkbox selected
	$content = apply_filters( 'the_content', $content);
}
echo $before_widget;
if ( $show_custom_post_title ) {
	echo $before_title;
	echo apply_filters( 'widget_title',$content_post->post_title);
	if ( $show_featured_image ) {
		echo get_the_post_thumbnail( $content_post -> ID );
	}
	echo $after_title; // This is the line that displays the title (only if show title is set)
}
echo do_shortcode( $content ); // This is where the actual content of the custom post is being displayed
echo $after_widget;