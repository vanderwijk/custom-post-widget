<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// Add button above editor if not editing content_block
function add_content_block_icon() {
	echo '<style>
	.cpw-button .dashicons-screenoptions {
		color: #888;
		height: 18px;
		margin: 0 4px 0 0;
		vertical-align: text-top;
		width: 18px;
	}
	.cpw-button {
		padding-left: 0.4em;
	}
	</style>
	<a id="add-content-block" class="button thickbox cpw-button" title="' . esc_html ( "Add Content Block", 'custom-post-widget' ) . '" href="' . esc_url ( plugins_url() ) . 'popup.php?type=add_content_block_popup&amp;TB_inline=true&amp;inlineId=content-block-form">
		<div class="dashicons dashicons-screenoptions"></div>' . esc_html ( "Add Content Block", "custom-post-widget") . '
	</a>';
}

// Displays the lightbox popup to insert a content block shortcode to a post/page
function add_content_block_popup() { ?>
	<script>
		function selectContentBlockId(select) {
			content_id = select.options[select.selectedIndex].value;
			content_slug = select.options[select.selectedIndex].getAttribute("data-slug");
		}
		function insertContentBlockShortcode() {
			if (typeof content_id === 'undefined') {
				alert( "<?php esc_html_e( 'Please select a Content Block', 'custom-post-widget' ); ?>" );
				return false;
			}
			var win = window.dialogArguments || opener || parent || top;
			win.send_to_editor( "[content_block id=" + content_id + " slug=" + content_slug + "]" );
		}
	</script>
	<div id="content-block-form" style="display: none;">
		<h3>
			<?php esc_html_e( 'Insert Content Block', 'custom-post-widget' ); ?>
		</h3>
		<p>
			<?php esc_html_e( 'Select a Content Block below to add it to your post or page.', 'custom-post-widget' ); ?>
		</p>
		<p>
			<select class="add-content-block-id" id="add-content-block-id" onchange="selectContentBlockId(this)">
				<option value="">
					<?php esc_html_e( 'Select a Content Block', 'custom-post-widget' ); ?>
				</option>
				<?php
					$args = array( 'post_type' => 'content_block', 'suppress_filters' => 0, 'numberposts' => -1, 'order' => 'ASC' );
					$content_block = get_posts( $args );
					if ( $content_block ) {
						foreach( $content_block as $content_block ) : setup_postdata( $content_block );
							echo '<option value="' . esc_attr ( $content_block -> ID ) . '" data-slug="' . esc_attr ( $content_block -> post_name ) . '">' . esc_html( $content_block -> post_title ) . '</option>';
						endforeach;
					} else {
						echo '<option value="">' . esc_html (  'No content blocks available', 'custom-post-widget' ) . '</option>';
					};
				?>
			</select>
		</p>
		<p>
			<input type="button" class="button-primary" value="<?php esc_attr_e( 'Insert Content Block', 'custom-post-widget' ) ?>" onclick="insertContentBlockShortcode();"/>
		</p>
	</div>

<?php }