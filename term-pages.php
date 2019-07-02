<?php

/**
 * Plugin Name: Term-Pages
 * Description: Overwrites first page of term archives with a page.
 * Version: 1.0.3
 * Author: PALASTHOTEL <rezeption@palasthotel.de>
 * Author URI: http://www.palasthotel.de
 * Requires at least: 4.0
 * Tested
 * Text Domain: term-pages
 * Domain Path: /languages
 * @copyright Copyright (c) 2019, Palasthotel
 */
class Term_Pages {

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'add_taxonomie_fields' ), 10 );
		add_action( 'pre_get_posts', array( $this, 'custom_page_query' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10 );
		add_action( 'wp_ajax_tp_lookup', array( $this, 'lookup_pages' ), 8 );
		add_action( 'wp_ajax_nopriv_tp_lookup', array( $this, 'lookup_pages' ), 8 );
		add_action( 'admin_footer', array( $this, 'render_frontend_js' ), 9 );
	}

	/**
	 * Add Page-ID field to every registered taxonomy
	 */
	function add_taxonomie_fields() {

		load_plugin_textdomain( 'term-pages', FALSE, 'term-pages/languages' );

		$taxonomies = get_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {

			add_action( $taxonomy . '_add_form_fields', array( $this, 'add_extra_taxonomy_field' ) );
			add_action( $taxonomy . '_edit_form_fields', array( $this, 'admin_render_taxonomy_field' ), 10, 2 );
			add_action( 'created_' . $taxonomy, array( $this, 'save_extra_field' ), 10 , 2 );
			add_action( 'edited_' . $taxonomy, array( $this, 'update_extra_field' ), 10, 2 );

		}
	}

	/**
	 * Enqueue Scripts
	 */
	function admin_enqueue_scripts() {
		wp_enqueue_script( 'suggest' );
		wp_enqueue_style( 'suggest' );
		wp_enqueue_script('remove', plugin_dir_url(__FILE__) . 'remove.js');
	}

	/**
	 * Render js-Snippet in frontend
	 */
	function render_frontend_js() {
		?>
		<script type="text/javascript">
			jQuery(document).ready(function () {
				var se_ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
				jQuery('.or-page-id').suggest(se_ajax_url + '?action=tp_lookup');
			});
		</script>
		<?php
	}


	/**
	 * Render html for taxonomy field
	 * @param $taxonomy
	 */
	function add_extra_taxonomy_field( $taxonomy ) {
		global $orpageid;
		?>
		<div class="form-field term-group">
			<label for="feature-group"><?php _e( 'overriding page', 'term-pages' ); ?></label>
			<input type="text" placeholder="<?= _e( 'Please insert the title of the published page', 'term-pages' ); ?>" class="or-page-id" name="or-page-id" size="20" value="<?php echo esc_attr( $orpageid ); ?>">
		</div>



		<?php
	}

	/**
	 * Save page-ID field.
	 * @param $term_id
	 * @param $tt_id
	 */
	function save_extra_field( $term_id, $tt_id ) {
		if ( isset( $_POST['or-page-id'] ) && '' !== $_POST['or-page-id'] ) {
			$group = sanitize_title( $_POST['or-page-id'] );
			add_term_meta( $term_id, 'or-page-id', $group, true );
		}
	}

	/**
	 * Render form-field for page overwriting the taxonomy on admin
	 * @param $term
	 * @param $taxonomy
	 */
	function admin_render_taxonomy_field( $term, $taxonomy ) {
		$post = get_term_meta( $term->term_id, 'or-page-id', true );

		$orpageid = "";

		//check if we have a post to prefill this field
		if(isset($post) && $post != null && $post > 0) {
			$orpageid = get_the_title( $post );
			$orpageid .= ' : ' . $post;
		}

		?>
		<tr>
			<td colspan="2">
			<div class="form-field term-group">
				<label for="feature-group"><?php _e( 'overriding page', 'term-pages' ); ?></label>
				<input type="text" placeholder="<?= _e( 'Please insert the title of the published page', 'term-pages' ); ?>" class="or-page-id" name="or-page-id" size="20" value="<?php echo esc_attr( $orpageid ); ?>">
			</div>
		</tr>
		<?php
	}

	/**
	 * Update field value
	 * @param $term_id
	 * @param $tt_id
	 */
	function update_extra_field( $term_id, $tt_id ) {
		if ( isset( $_POST['or-page-id'] ) && '' !== $_POST['or-page-id'] ) {
			$str = $_POST['or-page-id'];

			//get post id from string
			preg_match( '/(?<name>.+) : (?<id>\d+)/', $str, $treffer );

			//check value for id and save it
			if(isset($treffer['id']) && get_post(intval($treffer['id'])) != null) {
				update_term_meta( $term_id, 'or-page-id', intval($treffer['id']) );
				return;
			}
		}
		//delete term meta if post-value is not set, but also if
		//regex does not match (no valid format of input)
		delete_term_meta( $term_id, 'or-page-id' );
	}


	/**
	 * Look for pages by query string (for autocomplete)
	 */
	function lookup_pages() {
		global $wpdb;

		$search = $wpdb->esc_like( $_REQUEST['q'] );

		$query = 'SELECT ID,post_title FROM ' . $wpdb->posts . '
        WHERE post_title LIKE \'' . $search . '%\'
        AND post_type = \'page\'
        AND post_status = \'publish\'
        ORDER BY post_title ASC';

		foreach ( $wpdb->get_results( $query ) as $row ) {
			$post_title = $row->post_title;
			$id         = $row->ID;


			echo $post_title . ' : ' . $id ."\n";
		}


		die();
	}


	/**
	 * Redirect to taxonomy-page if there is no page parameter
	 * @param \WP_Query $query
	 */
	function custom_page_query( $query ) {
		//check if we have a taxonomy
		if ( $query->is_main_query() && ( $query->is_category() ) || ( $query->is_tax() ) || ( $query->is_tag() ) ) {

			$term = get_queried_object();
			if($term == null && isset($query->query["category_name"]) && !empty($query->query["category_name"])){
				$cat = $query->query["category_name"];
				$term = get_term_by("slug", $cat, "category");
			}
			
			if ( !($term instanceof WP_Term)) {
				return;
			}
			
			$term_id = $term->term_id;
			$orid = intval( get_term_meta( $term_id, 'or-page-id', true ) );

			$paged = get_query_var( 'paged' );

			//redirect if this is the first (unpaged) page of the taxonomy and we have an id for overwriting
			if ( ( $orid > 0 ) && ( $paged < 1 ) ) {
				wp_redirect(get_permalink($orid) , $status= 301);
				exit;
			}
		}
	}
}

new Term_Pages();
