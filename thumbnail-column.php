<?php
/*
	Plugin Name: Thumbnail Column
	Plugin URI: http://wpengineer.com/display-post-thumbnail-post-page-overview/
	Description: Display post thumbnail in column on edit.php overview.
	Author: Brainstorm Media
	Version: 1.2
	Author URI: http://brainstormmedia.com
*/

if ( !function_exists('fb_AddThumbColumn') && function_exists('add_theme_support') ) {

	// for post and page
	add_theme_support('post-thumbnails', array( 'post', 'page' ) );

	function fb_AddThumbColumn($cols) {
		if ( in_array( $_GET['post_type'], apply_filters('pd_thumbnail_column_types', array( 'post', 'page' ) ), true ) ) {
			$cols['thumbnail'] = __('Thumbnail');
		}
		
		return $cols;
	}

	function fb_AddThumbValue($column_name, $post_id) {
		
			if ( 'thumbnail' == $column_name ) {
				if ( has_post_thumbnail($post_id) ) {
					// Featured Image
					echo get_the_post_thumbnail( $post_id, 'thumbnail' );
				
				}else if ( $attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') ) ) {
					// Image attachments
					foreach ( (array) $attachments as $attachment_id => $attachment ) {
						echo wp_get_attachment_image( $attachment_id, 'thumbnail' );

						if ( isset( $_GET['set-featured-thumbnails']) ) {
							add_post_meta($post_id, '_thumbnail_id', $attachment_id );
						}

						break;
					}
				}
			}
	}

	// for posts
	add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );

	// for pages
	add_filter( 'manage_pages_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_pages_custom_column', 'fb_AddThumbValue', 10, 2 );
}