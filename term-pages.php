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
//		add_action( 'template_redirect', array( $this, 'hooks_setup' ), 20 );
		add_action('admin_enqueue_scripts', array($this, 'se_admin_enqueue_scripts'),10);
//		add_action('wp_head', array($this, 'se_wp_head'),10);
		add_action('wp_ajax_se_lookup',array($this, 'se_lookup'),8);
		add_action('wp_ajax_nopriv_se_lookup',array($this, 'se_lookup'),8);
		add_action('admin_footer',array($this, 'my_admin_function'),9);
		
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

function se_admin_enqueue_scripts() {
    wp_enqueue_script('suggest');
    wp_enqueue_style( 'suggest' );
}

function my_admin_function() {
?>
<script type="text/javascript">
  jQuery(document).ready(function() {
    
    
 

   var se_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
   jQuery('.or-page-id').suggest(se_ajax_url + '?action=se_lookup');
   
   
   

/*
   var myString = jQuery("input[name=or-page-id]").val(); 
   console.log(myString);
   var myNewString = myString.split("-");
   console.log(myNewString[1]);
   jQuery("input[name=or-page-id]").focusout("input[name=or-page-id]").val(myNewString[1]); 
*/


  });
</script>
<?php
}


function add_extra_taxonomy_field( $taxonomy ) { 
	global $orpageid;
 ?><div class="form-field term-group">
    <label for="feature-group"><?php _e('overriding page', 'term-pages'); ?></label>
	<input type="text" class="or-page-id" name="or-page-id" size="20" value="<?php echo esc_attr( $orpageid); ?>">
	
    </div>

    
    
    <?php
} 

function save_extra_field( $term_id, $tt_id ){
	if( isset( $_POST['or-page-id'] ) && '' !== $_POST['or-page-id'] ){
		$group = sanitize_title( $_POST['or-page-id'] );
		add_term_meta( $term_id, 'or-page-id', $group, true );
	}
}

function edit_extra_taxonomy_field( $term, $taxonomy ){
	$post = get_term_meta( $term->term_id, 'or-page-id', true );
	
	$orpageid = get_the_title( $post );
	$orpageid .= ':'.$post;
    
	?><tr class="form-field term-group-wrap">
	<th scope="row"><label for="feature-group"><?php _e( 'overriding page id', 'term-pages' ); ?></label></th>
	
	
	<td><input type="text" class="or-page-id" name="or-page-id" size="20" value="<?php echo esc_attr( $orpageid); ?>">
	</tr><?php
}

function update_extra_field( $term_id, $tt_id){
	if( isset( $_POST['or-page-id'] ) && '' !== $_POST['or-page-id'] ){
	//$str = sanitize_title( $_POST['or-page-id'] );
	$str = $_POST['or-page-id'];
	 

 
  preg_match('/(?<name>\w+) : (?<zahl>\d+)/', $str, $treffer);
		
	$group = $treffer[2];
	
	update_term_meta( $term_id, 'or-page-id', $group );
	}
	else if ( $_POST['or-page-id'] == ''){
	delete_term_meta( $term_id, 'or-page-id');	
		
	}
}




function se_lookup() {
    global $wpdb;

    $search = like_escape($_REQUEST['q']);

  $query = 'SELECT ID,post_title FROM ' . $wpdb->posts . '
        WHERE post_title LIKE \'' . $search . '%\'
        AND post_type = \'page\'
        AND post_status = \'publish\'
        ORDER BY post_title ASC';
        
    foreach ($wpdb->get_results($query) as $row) {
        $post_title = $row->post_title;
        $id = $row->ID;

       

        echo $post_title . ' : ' . $id. "\n";
    }
    
    
    die();
}


function custom_page_query ( $query ){
	
	$term = get_queried_object();

/*
	echo("<pre>");
	var_dump($query);
	echo("</pre>");
*/


	if (($query->is_category())||($query->is_tax()) || ($query->is_tag())) {		
		
			
			$term = get_queried_object();
			
			$term_id = $term -> term_id; 
			
			$orid = intval(get_term_meta( $term_id, 'or-page-id', TRUE ));
			
			$paged = get_query_var( 'paged');
		
			if (($orid > 0) && ($paged < 1)){
				
				//echo("bitte gehen sie weiter");
				wp_redirect(get_permalink($orid) , $status= 302);
				exit;	
			}
			else
			{
				//echo("1. 2. 3.seite, keine weiterleitung keine page nur terms");
			}
			
	}	
 }

   
}

new Term_Pages;