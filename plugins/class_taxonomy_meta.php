<?php 
/*
Taxonomy Meta Class

Modified from http://en.bainternet.info/2012/wordpress-taxonomies-extra-fields-the-easy-way

Removed certain field types of trivial utility and attempted to make syntactically consistent with the theme
*/

if( ! class_exists( 'Tax_Meta_Class' ) ) {

	class Tax_Meta_Class {

		protected $_meta_box; // Holds meta box object
		protected $_fields; // Holds meta box fields.
		protected $_form_type; // What form is this? edit or new term.
		protected $SelfPath; // SelfPath to allow themes as well as plugins.

		// Constructor
		public function __construct( $meta_box ) {
			// If we are not in admin area exit.
			if( ! is_admin() )
				return;
			// Assign meta box values to local variables and add its missed values.
			$this->_meta_box = $meta_box; 
			$this->_fields = &$this->_meta_box['fields'];
			//$this->add_missed_values();
			if( isset( $meta_box['use_with_theme'] ) ) {
				if( $meta_box['use_with_theme'] === true )
					$this->SelfPath = get_template_directory_uri() . '/taxonomy_meta_class';
				elseif( $meta_box['use_with_theme'] === false )
					$this->SelfPath = plugins_url( 'Tax-meta-class', plugin_basename( dirname( __FILE__ ) ) );
				else
					$this->SelfPath = $meta_box['use_with_theme'];
			} else {
				$this->SelfPath = plugins_url( 'Tax-meta-class', plugin_basename( dirname( __FILE__ ) ) );
			}
			add_action( 'admin_init', array( &$this, 'add' ) );
			add_action( 'delete_term', array( $this, 'delete_taxonomy_metadata' ), 10, 2 );
		}
		
		// Add Meta Box for multiple post types.
		public function add() {
			foreach( $this->_meta_box['pages'] as $page ) {
				add_action( $page.'_edit_form_fields', array( &$this, 'show_edit_form' ) ); //add fields to edit form
				add_action( $page.'_add_form_fields', array( &$this, 'show_new_form' ) ); //add fields to add new form
				add_action( 'edited_'.$page, array( &$this, 'save' ), 10, 2 ); // saves the edit fields
				add_action( 'created_'.$page, array( &$this, 'save' ), 10, 2 ); // this saves the add fields
			}
		}
	
		// Callback function to show fields on add new taxonomy term form.
		public function show_new_form( $term_id ) {
			$this->_form_type = 'new';
			$this->show($term_id);
		}
	
		// Callback function to show fields on term edit form.
		public function show_edit_form( $term_id ) {
			$this->_form_type = 'edit';
			$this->show($term_id);
		}
	
		// Callback function to show fields in meta box.
		public function show( $term_id ) {
			wp_nonce_field( basename( __FILE__ ), 'tax_meta_class_nonce' );
			foreach( $this->_fields as $field )
			{
				$meta = $this->get_tax_meta( $term_id, $field['id'] );
				$meta = ( $meta !== '' ) ? $meta : $field['std'];
				echo "<tr class='form-field'>";
				// Call Separated methods for displaying each type of field.
				call_user_func( array( &$this, 'show_field_' . $field['type'] ), $field, is_array( $meta )? $meta : stripslashes( $meta ) );
				echo "</tr>";
			}
			echo "</table>";
		}
	
		// Begin Field.
		public function show_field_begin( $field, $meta ) {
			if( isset( $field['group'] ) ) {
				if( $field['group'] == "start" )
					echo "<td class='at-field'>";
			} else {
				if( $this->_form_type == 'edit' )
					echo "<th valign='top' scope='row'>";
				else
					echo "<div class='form-field'>";
			}
			if( $field['name'] != '' || $field['name'] != FALSE )
					echo "<label for='{$field['id']}'>{$field['name']}</label>";
			if( $this->_form_type == 'edit' )
					echo "</th><td>";
		}
		
		// End Field.
		public function show_field_end( $field, $meta=NULL ) {
			if( isset( $field['group'] ) ) {
				if( $group == 'end' ) {
					if( $field['desc'] != '' )
						echo "<p class='description'>{$field['desc']}</p></td>";
					else 
						echo "</td>";
				} else {
					if( $field['desc'] != '' )
						echo "<p class='description'>{$field['desc']}</p><br/>";  
					else
						echo "<br/>"; 
				}    
			}
			else { 
				if( $field['desc'] != '' )
					echo "<p class='description'>{$field['desc']}</p>";
				if( $this->_form_type == 'edit' )
					echo "</td>";  
				else
					echo "</div>";
			}
		}

		// Show Text field
		public function show_field_text( $field, $meta ) {  
			$this->show_field_begin( $field, $meta );
			echo "<input type='text' class='at-text' name='{$field['id']}' id='{$field['id']}' value='{$meta}' style='{$field['style']}' size='30' />";
			$this->show_field_end( $field, $meta );
		}

		// Show Checkbox Field.
		public function show_field_checkbox( $field, $meta ) {
			$this->show_field_begin( $field, $meta );
			echo "<input type='checkbox' style='width:auto;' name='{$field['id']}' id='{$field['id']}'" . checked( !empty($meta), true, false ) . " />";
			$this->show_field_end( $field, $meta );
		}
		
		// Show select input.
		public function show_field_select( $field,  $meta ) {
			
			if( ! is_array( $meta ) ) 
				$meta = ( array ) $meta;
				
			$this->show_field_begin( $field,  $meta );
				echo "<select name='{$field['id']}' id='{$field['id']}'>";
				foreach( $field['options'] as $key => $value ) {
					echo "<option value='{$key}'" . selected( in_array( $key,  $meta ),  true,  false ) . ">{$value}</option>";
				}
				echo "</select>";
			$this->show_field_end( $field,  $meta );
			
		}
		
		// Show radio buttons.
		public function show_field_radio( $field,  $meta ) {
			
			if( ! is_array( $meta ) )
				$meta = ( array ) $meta;
				
			$this->show_field_begin( $field,  $meta );
				foreach( $field['options'] as $key => $value ) {
					echo "<input style='width:auto;' type='radio' name='{$field['id']}' value='{$key}'" . checked( in_array( $key,  $meta ),  true,  false ) . " /> {$value}";
				}
			$this->show_field_end( $field,  $meta );
		}

		// Save Data from Metabox
		public function save( $term_id ) {
			if (isset($_REQUEST['action'])  &&  $_REQUEST['action'] == 'inline-save-tax')
				return $term_id;
	
			if ( ! isset( $term_id ) || ( ! isset( $_POST['taxonomy'] ) ) || ( ! in_array( $_POST['taxonomy'], $this->_meta_box['pages'] ) ) || ( ! check_admin_referer( basename( __FILE__ ), 'tax_meta_class_nonce') ) || ( ! current_user_can('manage_categories') ) )
				return $term_id;
			
			foreach ( $this->_fields as $field ) {
				$name = $field['id'];
				$type = $field['type'];
				$old = $this->get_tax_meta( $term_id, $name );
				$new = ( isset( $_POST[$name] ) ) ? $_POST[$name] : ( '' );

				// Validate meta value
				if ( class_exists( 'Tax_Meta_Validate' ) && method_exists( 'Tax_Meta_Validate', $field['validate_func'] ) )
					$new = call_user_func( array( 'Tax_Meta_Validate', $field['validate_func'] ), $new );

				// Call defined method to save meta value, if there's no methods, call common one.
				$save_func = 'save_field_' . $type;

				if ( method_exists( $this, $save_func ) )
					call_user_func( array( &$this, 'save_field_' . $type ), $term_id, $field, $old, $new );
				else
					$this->save_field( $term_id, $field, $old, $new );

			}
		}

		//Common function for saving fields.
		public function save_field( $term_id, $field, $old, $new ) {
			$name = $field['id'];
			$this->delete_tax_meta( $term_id, $name );
			if ( $new === '' || $new === array() ) 
				return;

			$this->update_tax_meta( $term_id, $name, $new );
		}

		// Check if field with $type exists.
		public function has_field( $type ) {
			foreach( $this->_fields as $field ) {
				if( $type == $field['type'] ) 
					return true;
			}
			return false;
		}

		// Check if current page is edit page.
		public function is_edit_page() {
			global $pagenow;
			return( $pagenow == 'edit-tags.php' );
		}

		//  Add Text Field to meta box
		public function addText( $id, $args ) {
			$new_field = array( 'type' => 'text', 'id'=> $id, 'std' => '', 'desc' => '', 'style' =>'', 'name' => 'Text Field' );
			$new_field = array_merge( $new_field, $args );
			$this->_fields[] = $new_field;
		}

		// Add Checkbox Field to meta box
		public function addCheckbox( $id, $args ) {
			$new_field = array( 'type' => 'checkbox', 'id'=> $id,'std' => '', 'desc' => '', 'style' =>'', 'name' => 'Checkbox Field' );
			$new_field = array_merge( $new_field, $args );
			$this->_fields[] = $new_field;
		}
		
		//  Add select input
		public function addSelect( $id, $options, $args ) {
			$new_field = array( 'type' => 'select', 'id'=> $id, 'std' => array(), 'desc' => '', 'style' =>'', 'name' => 'Select Field', 'options' => $options );
			$new_field = array_merge( $new_field,  $args );
			$this->_fields[] = $new_field;
		}
		
		// Add radio buttons
		public function addRadio( $id, $options, $args ) {
			$new_field = array( 'type' => 'radio', 'id'=> $id, 'std' => array(), 'desc' => '', 'style' =>'', 'name' => 'Radio Field', 'options' => $options );
			$new_field = array_merge( $new_field,  $args );
			$this->_fields[] = $new_field;
		}

		// Finish Declaration of Meta Box
		public function Finish() {
			//$this->add_missed_values();
		}

		// Helper function to check for empty arrays
		public function is_array_empty( $array ) {
			if( !is_array( $array ) )
				return true;
			foreach( $array as $a ) {
				if( is_array( $a ) ) {
					foreach( $a as $sub_a ) {
						if( !empty( $sub_a ) && $sub_a != '')
							return false;
					}
				} else {
					if( !empty( $a ) && $a != '')
						return false;
				}
			}
			return true;
		}
	
		// get term meta field
		public function get_tax_meta( $term_id, $key ) {
			$t_id = (is_object($term_id))? $term_id->term_id: $term_id;
			$m = get_option( 'tax_meta_'.$t_id);	
			if( isset( $m[$key] ) )
				return $m[$key];
			else
				return '';
		}
	
		// delete meta
		public function delete_tax_meta( $term_id, $key ) {
			$m = get_option( 'tax_meta_'.$term_id );
			if( isset( $m[$key] ) )
				unset( $m[$key] );
			update_option( 'tax_meta_'.$term_id,$m );
		}
	
		// update meta
		public function update_tax_meta( $term_id, $key, $value ) {
			$m = get_option( 'tax_meta_'.$term_id );
			$m[$key] = $value;
			update_option( 'tax_meta_'.$term_id,$m );
		}
	
		// delete meta on term deletion
		public function delete_taxonomy_metadata( $term, $term_id ) {
			delete_option( 'tax_meta_'.$term_id );
		}
	
	} // End Class

} // End Check Class Exists

/* Meta functions for easy access */

// get term meta field
if( !function_exists( 'get_tax_meta' ) ) {
	function get_tax_meta( $term_id, $key ) {
		$t_id = ( is_object($term_id) )? $term_id->term_id: $term_id;
		$m = get_option( 'tax_meta_'.$t_id);	

		if( isset( $m[$key] ) )
			return $m[$key];
		else
			return '';

	}
}

//delete meta
if( !function_exists( 'delete_tax_meta' ) ) {
	function delete_tax_meta( $term_id, $key ) {
		$m = get_option( 'tax_meta_'.$term_id );

		if( isset( $m[$key] ) )
			unset( $m[$key] );

		update_option( 'tax_meta_'.$term_id, $m );
	}
}

//update meta
if( !function_exists( 'update_tax_meta' ) ) {
	function update_tax_meta( $term_id, $key, $value ) {
		$m = get_option( 'tax_meta_'.$term_id );
		$m[$key] = $value;
		update_option( 'tax_meta_'.$term_id, $m );
	}
}

//get term meta field and strip slashes
if( !function_exists( 'get_tax_meta_strip' ) ) {
	function get_tax_meta_strip( $term_id, $key ) {
		$t_id = ( is_object($term_id) )? $term_id->term_id: $term_id;
		$m = get_option( 'tax_meta_'.$t_id);	

		if( isset( $m[$key] ) )
			return is_array( $m[$key] )? $m[$key] : stripslashes( $m[$key] );
		else
			return '';

	}
}