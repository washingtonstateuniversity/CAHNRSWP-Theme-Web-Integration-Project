<?php
/*
Registers a taxonomy for use on attachments (uploads)

Adapted from:
	http://wordpress.org/extend/plugins/media-tags/
	http://wordpress.org/extend/plugins/media-categories-2/
*/

if( !class_exists( 'wipMediaCategories' ) ) {
	class wipMediaCategories {

		function wipMediaCategories_init() {
			$labels = array(
				'name' => 'Media Categories',
				'singular_name' => 'Media Category',
				'search_items' =>	'Search Media Categories',
				'popular_items' => NULL,
				'all_items' => 'All Media Categories',
				'parent_item' => 'Parent Category',
				'parent_item_colon' => 'Parent Category:',
				'edit_item' => 'Edit Media Category', 
				'update_item' => 'Update Media Category',
				'add_new_item' => 'Add New Media Category',
				'new_item_name' => 'New Media Category Name',
			);
			$args = array(
				'labels' => $labels,
				'show_in_nav_menus' => false,
				'hierarchical' => true,
				'query_var' => true,
			);
			register_taxonomy( 'media_category', 'attachment', $args );
		}

		// Add table header in Media Library
		function wipMediaCategories_addColumn( $columns ) {	
			$new = array();	
			foreach( $columns as $key => $title ) {		
				if( $key == 'comments' ) // Put the column before the $key column			
					$new['media_categories'] = 'Media Categories';	 
				$new[$key] = $title;
			}	
			return $new;
		}

		// Adding tags entries in Media Library
		function wipMediaCategories_fillColumn( $column_name, $id ) {
			switch( $column_name ) {
				case 'media_categories':
					$admin = get_admin_url();
					$string = "upload.php?";
					$tags = get_the_terms( $id, 'media_category' );

					if( !empty( $tags ) ) {
						$out = array();
						foreach( $tags as $tag )
							$out[] = "<a href='".$string."media_category=$tag->slug'> " . esc_html( sanitize_term_field( 'name', $tag->name, $tag->term_id, 'media_category', 'display' ) ) . "</a>";
						echo join( ', ', $out );
					} else {
						_e( 'No Categories' );
					}
				break;
			default:
				break;
			}		 
		}

		// Get media categories from database
		/*function wipMediaCategories_query( $term, $size )
		{
			$args = array( 
				'post_type' => 'attachment', 
				'post_mime_type' => 'image',
				'post_status' => 'inherit',
				'tax_query' => array(
					array(
						'taxonomy' => 'media_category',
						'terms' => $term,
						'field' => 'slug',
					)
				)
			);
			$loop = new WP_Query( $args );

			while( $loop->have_posts() )
			{
				$loop->the_post();
				$image = wp_get_attachment_image( '', $size, false );
				$url = wp_get_attachment_url();
					$output = '<a href="' . $url . '">' . $image . '</a>';
					echo $output;
			}
		}*/
	}
}


add_action( 'init', array( 'wipMediaCategories', 'wipMediaCategories_init' ) );
add_filter( 'manage_media_columns', array( 'wipMediaCategories', 'wipMediaCategories_addColumn' ) );
add_action( 'manage_media_custom_column', array( 'wipMediaCategories', 'wipMediaCategories_fillColumn' ), 10, 2 );

// Allows users to assign categories to uploads via checkboxes instead of slugs in a text field - adapted from http://wordpress.org/extend/plugins/media-categories-2
$mediaCategoryTerms = get_terms( 'media_category', 'hide_empty=0' );
$count = count( $mediaCategoryTerms );
if( $count > 0 ) {

	// Custom walker to facilitate slugs instead of IDs on the list items
	class attachmentWalkerCategoryChecklist extends Walker {
	
		var $tree_type = 'category';
		var $db_fields = array( 'parent' => 'parent', 'id' => 'term_id' );
	
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$output .= "$indent<ul class='children'>\n";
		}
	
		function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$output .= "$indent</ul>\n";
		}
	
		function start_el( &$output, $category, $depth = 0, $args = array(), $current_object_id = 0 ) {
			extract( $args );
	
			if( empty( $taxonomy ) )
				$taxonomy = 'category';
	
			if( $taxonomy == 'category' )
				$name = 'post_category';
			else
				$name = 'tax_input['.$taxonomy.']';
	
			$class = in_array( $category->term_id, $popular_cats ) ? ' class="popular-category"' : '';
			// The following line is all that has been modified from the original walker, value="' . $category->term_id . '" to value="' . $category->slug . '"
			$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" . '<label class="selectit"><input value="' . $category->slug . '" type="checkbox" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' . esc_html( apply_filters( 'the_category', $category->name ) ) . '</label>';
		}
	
		function end_el( &$output, $category, $depth = 0, $args = array() ) {
			$output .= "</li>\n";
		}
	
	}
	
	
	class mediaCategories {
	
		public static $instances;
		public $taxonomy;
	
		// While normally run statically, this allows @param type $taxonomy 
		public function __construct( $taxonomy ) {
	
			// Store each instance of this class (for use when localizing scripts)
			$this->taxonomy = $taxonomy;
			self::$instances[] = $this;
	
			add_filter( 'attachment_fields_to_edit', array( &$this, 'mediaCategories_addMetabox' ), null, 2 );
			add_action( 'wp_enqueue_media', array(__CLASS__, 'mediaCategories_enqueueScripts' ) );
			add_action( 'wp_enqueue_media', array(__CLASS__, 'mediaCategories_enqueueStyles' ) );
	
		}
	
		// Enqueue javascript
		function mediaCategories_enqueueScripts() {
			if( is_admin() ) {
				// Get each instance of this class, and pass each taxonomy in to javascript
				foreach( self::$instances as $instance ) {
					$tax[] = apply_filters( 'mc_taxonomy', 'media_category' );
				}
	
				wp_register_script('media_categories_metabox_script', get_template_directory_uri() . '/js/media_categories.js' );
				wp_enqueue_script('media_categories_metabox_script');
				wp_localize_script('media_categories_metabox_script', 'taxonomy',	$tax);
			}
		}
		
		// Enqueue CSS
		function mediaCategories_enqueueStyles() {
			if( is_admin() ) {
				wp_register_style('media_categories_metabox_style', get_template_directory_uri() . '/css/media_categories.css' );
				wp_enqueue_style( 'media_categories_metabox_style');
			}
		}
	
		// Insert a custom form field into the media editor, capture the output of a custom metabox and insert it
		function mediaCategories_addMetabox( $form_fields, $post ) {
			require_once( './includes/meta-boxes.php' );
	
			$tax_name = 'media_category';
			$taxonomy = get_taxonomy( $tax_name );
	
			ob_start();
				$this->mediaCategories_metabox( $post, array( 'args' => array( 'taxonomy' => $tax_name, 'tax' => $taxonomy ) ) );
			$metabox = ob_get_clean();
	
			$form_slug = 'media_category_metabox';
			$form_fields[$form_slug]['label'] = $taxonomy->labels->name;
			$form_fields[$form_slug]['input'] = 'html';
			$form_fields[$form_slug]['html'] = $metabox;
			$form_fields[$form_slug]['show_in_edit'] = false; // Make sure the metabox only loads on the modal.
	
			return $form_fields;
		}
	
		// Metabox using the custom walker
		function mediaCategories_metabox( $post, $box ) {
			$defaults = array( 'taxonomy' => apply_filters( 'mc_taxonomy', $this->taxonomy ) );
			if( !isset( $box['args'] ) || !is_array( $box['args'] ) )
				$args = array();
			else
				$args = $box['args'];
	
			extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
			$tax = get_taxonomy( $taxonomy );
			?>
				<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
					<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
						<?php
							$name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
							// Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
							echo "<input type='hidden' name='{$name}[]' value='0' />";
						?>
						<ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy; ?> <?php echo $taxonomy; ?>checklist categorychecklist form-no-clear">
							<?php
								$custom_walker = new attachmentWalkerCategoryChecklist;
								wp_terms_checklist( $post->ID, array( 'taxonomy' => $taxonomy, 'walker' => $custom_walker ) );
							?>
						</ul>
					</div>
				</div>
			<?php
		}
	
	}
	
	$mc_category_metabox = new mediaCategories('category');

}
?>
