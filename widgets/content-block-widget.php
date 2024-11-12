<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Content_Block_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'content_block_widget';
    }

    public function get_title() {
        return __( 'Content Block', 'custom-post-widget' );
    }

    public function get_icon() {
        return 'eicon-posts-ticker';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'custom-post-widget' ),
            ]
        );

        // Get all content blocks
        $content_blocks = get_posts( [
            'post_type'      => 'content_block',
            'posts_per_page' => -1,
        ] );

        $options = [];
        if ( $content_blocks ) {
            foreach ( $content_blocks as $content_block ) {
                $options[ $content_block->ID ] = $content_block->post_title;
            }
        }

        $this->add_control(
            'content_block_id',
            [
                'label'   => __( 'Select Content Block', 'custom-post-widget' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => $options,
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['content_block_id'] ) ) {
            $post = get_post( $settings['content_block_id'] );
            if ( $post ) {
                // Check if we are in the Elementor editor
                if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                    // Display the content without filters in the editor
                    echo wp_kses_post( $post->post_content );
                } else {
                    // Apply 'the_content' filters on the frontend
                    $content = apply_filters( 'the_content', $post->post_content );
                    echo $content;
                }
            } else {
                echo '<p>' . esc_html__( 'Content block not found.', 'custom-post-widget' ) . '</p>';
            }
        } else {
            echo '<p>' . esc_html__( 'No content block selected.', 'custom-post-widget' ) . '</p>';
        }
    }

}