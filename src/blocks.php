<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

namespace A3Rev\ContactPeople;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Blocks {

	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );

		include( 'blocks/profile/block.php' );

		// Hook: Editor assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'cgb_editor_assets' ) );
	}

	/**
	 * Enqueue Gutenberg block assets for backend editor.
	 *
	 * @uses {wp-blocks} for block type registration & related functions.
	 * @uses {wp-element} for WP Element abstraction — structure of blocks.
	 * @uses {wp-i18n} to internationalize the block's text.
	 * @uses {wp-editor} for WP editor styles.
	 * @since 1.0.0
	 */
	function cgb_editor_assets() { // phpcs:ignore
		// Scripts.
		wp_enqueue_script(
			'contact-people-block-js', // Handle.
			plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor', 'wp-components' ), // Dependencies, defined above.
			// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: File modification time.
			true // Enqueue the script in the footer.
		);

		// Styles.
		// wp_enqueue_style(
		// 	'contact-people-block-editor-css', // Handle.
		// 	plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		// 	array( 'wp-edit-blocks' ) // Dependency to include the CSS after it.
		// 	// filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
		// );

		$styles = file_get_contents(plugins_url( 'dist/blocks.editor.build.css', dirname( __FILE__ ) ) );
	
		// Add editor styles.
		wp_add_inline_style( 'wp-edit-blocks', $styles );
		
		$contacts = Data\Profile::get_results('', 'c_order ASC', '', 'ARRAY_A');
		
		$contactList = array();
		if ( is_array( $contacts ) && count( $contacts ) > 0 ) {
			foreach ( $contacts as $key=>$value ) {
				$profile_name =  trim( esc_attr( stripslashes( $value['c_name'] ) ) );
				if ( $profile_name == '' ) $profile_name = trim( esc_attr( stripslashes( $value['c_title'] ) ) );

				$c_identitier =  trim( esc_attr( stripslashes( $value['c_identitier'] ) ) );
				if ( strlen( $c_identitier ) > 30 ) {
					$c_identitier = substr( $c_identitier, 0, 30 ) . '...';
				}

				if ( '' !== $c_identitier ) {
					$c_identitier = ' - ' . $c_identitier;
				} 

				$contactList[] = array( 'label' => $profile_name . $c_identitier, 'value' => $value['id'] );
			}
		}

		wp_localize_script( 'contact-people-block-js', 'contact_people_vars', array( 
			'contactList'     => json_encode( $contactList ),
			'profile_preview' => PEOPLE_CONTACT_URL . '/src/blocks/profile/preview.jpg',
		) );
	}

	public function create_a3blocks_section() {

		add_filter( 'block_categories_all', function( $categories ) {

			$category_slugs = wp_list_pluck( $categories, 'slug' );

			if ( in_array( 'a3rev-blocks', $category_slugs ) ) {
				return $categories;
			}

			return array_merge(
				array(
					array(
						'slug' => 'a3rev-blocks',
						'title' => __( 'a3rev Blocks' ),
						'icon' => '',
					),
				),
				$categories
			);
		}, 2 );
	}

	public function register_block() {

		$this->create_a3blocks_section();

	}
}
