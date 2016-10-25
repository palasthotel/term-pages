<?php
/**
 * Plugin Name: Term-Pages
 * Description: Overwrites first page of term archives with a page.
 * Version: 1.0
 * Author: PALASTHOTEL <rezeption@palasthotel.de>
 * Author URI: http://www.palasthotel.de
 * Requires at least: 4.0
 * @copyright Copyright (c) 2016, Palasthotel
 */

class Term_Pages {

	/**
	 * Class construct method. Adds actions to their respective WordPress hooks.
	 */
	public function __construct() {
		add_action( 'init', array($this, 'add_taxonomie_fields'), 10);
		add_action( 'pre_get_posts', array( $this, 'custom_page_query' ),1);		
	}

	function add_taxonomie_fields(){
		$taxonomies = get_taxonomies( );

		foreach( $taxonomies as $taxonomy) {

			  add_action( $taxonomy.'_add_form_fields', array($this,'add_extra_taxonomy_field' ));
			  add_action( $taxonomy.'_edit_form_fields',array($this, 'edit_extra_taxonomy_field'),10,2);
			  add_action( 'created_'.$taxonomy, array($this,'save_extra_field' ));
			  add_action( 'edited_'.$taxonomy, array($this, 'update_extra_field' ),10,2);
			
			}
	}
	
function add_extra_taxonomy_field( $taxonomy ) { 
	global $orpageid;
 ?><div class="form-field term-group">
    <label for="featuret-group"><?php _e('overriding page', 'term-pages'); ?></label>
	<input type="text" id="or-page-id" name="or-page-id" size="20" value="<?php echo esc_attr( $orpageid); ?>">
    </div><?php
} 

function save_extra_field( $term_id, $tt_id ){
	if( isset( $_POST['or-page-id'] ) && '' !== $_POST['or-page-id'] ){
		$group = sanitize_title( $_POST['or-page-id'] );
		add_term_meta( $term_id, 'or-page-id', $group, true );
	}
}

function edit_extra_taxonomy_field( $term, $taxonomy ){
	$orpageid = get_term_meta( $term->term_id, 'or-page-id', true );
    
	?><tr class="form-field term-group-wrap">
	<th scope="row"><label for="feature-group"><?php _e( 'overriding page id', 'term-pages' ); ?></label></th>
	<td><input type="text" id="or-page-id" name="or-page-id" size="20" value="<?php echo esc_attr( $orpageid); ?>">
	</tr><?php
}

function update_extra_field( $term_id, $tt_id){
	if( isset( $_POST['or-page-id'] ) && '' !== $_POST['or-page-id'] ){
	$group = sanitize_title( $_POST['or-page-id'] );
	update_term_meta( $term_id, 'or-page-id', $group );
	}
}

function custom_page_query ( $query ){
	
/*

	echo("<pre>");
	var_dump($query);
	echo("</pre>");
*/


	if ($query->is_category() &&  !($query->is_paged())) {		
		$term = get_queried_object();

/*

	echo("<pre>");
	var_dump( $term );
	echo("</pre>");

	echo("<pre>");
	var_dump( $query );
	echo("</pre>");
	
*/

	 $term_id = $term -> term_id; 
	
	$orid = get_term_meta( $term_id, 'or-page-id', TRUE );
/*
	$paged = get_query_var( 'paged');
	
	$new_pager = $paged +1;
	 set_query_var( 'paged', $new_pager );
	 
	echo("<pre>");
	var_dump( $paged );
	var_dump( $new_pager );
	echo("</pre>");
	
*/
	//wp_redirect(get_permalink($orid) , $status= 301);
	//exit;
	

	$query->set('post_type','page');
	$query->set('post_count','1');
	$query->set('page_id',$orid);


	
	
	echo("<pre>");
	var_dump( $orid );
	echo("</pre>");
	
/*
	$query->query_vars['post_type'] = 'page';
	$query->query_vars['page_id'] = $orid;
	
*/
	
/*
	echo("<pre>");
	var_dump( $query );
	echo("</pre>");
*/
		
	}
	
}
   
}

new Term_Pages;