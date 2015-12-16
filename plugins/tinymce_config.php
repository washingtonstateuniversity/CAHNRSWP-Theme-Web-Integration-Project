<?php
/*
Documentation at http://codex.wordpress.org/TinyMCE
*/

function wipFormatTinyMCE( $in ) {
 $in['theme_advanced_buttons1']='bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,anchor,link,unlink,wp_more,|,spellchecker,fullscreen';
 $in['theme_advanced_buttons2']='formatselect,forecolor,|,pastetext,pasteword,removeformat,|,charmap,|,outdent,indent,|,sup,sub,|,undo,redo,wp_help';
 $in['theme_advanced_buttons3']='tablecontrols,delete_table';
 $in['wordpress_adv_hidden'] = false;
 return $in;
}
add_filter('tiny_mce_before_init', 'wipFormatTinyMCE' );


// Add the Table plugin - adapted from http://wordpress.org/extend/plugins/mce-table-buttons/
class addTableControls {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_init() {
		add_filter( 'mce_external_plugins', array( $this, 'mceTablePlugin' ) );
	}

	public function mceTablePlugin( $plugin_array ) {
		$plugin_array['table'] = get_bloginfo('template_url').'/plugins/tinymce_table/editor_plugin.js';
   	return $plugin_array;
	}

}

$mce_table_buttons = new addTableControls;
?>