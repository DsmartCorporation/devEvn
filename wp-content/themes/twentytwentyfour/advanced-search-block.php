<?php
/**
 * Plugin Name: Advanced Search Block
 * Description: Adds a Gutenberg block for advanced searching.
 * Author: Hien
 * Version: 1.0
 */

defined( 'ABSPATH' ) || exit;

function example_enqueue_block_variations() {
    wp_enqueue_script(
        'example-enqueue-block-variations',
        get_template_directory_uri() . '/src/block.js',
        array( 'wp-blocks' ),
        wp_get_theme()->get( 'Version' ),
        false
    );
}
add_action( 'enqueue_block_editor_assets', 'example_enqueue_block_variations' );