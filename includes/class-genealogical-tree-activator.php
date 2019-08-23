<?php
namespace Genealogical_Tree\Includes;

/**
 * Fired during plugin activation
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option( 'genealogical_tree_active_ver', '1.2.0' );
		$plugin_admin = new \Genealogical_Tree\Genealogical_Tree_Admin\Genealogical_Tree_Admin( '', '' );
		$plugin_admin->init_post_type_and_taxonomy();
		flush_rewrite_rules();
	}

}
