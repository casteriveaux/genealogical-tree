<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wordpress.org/plugins/genealogical-tree
 * @since             1.0.0
 * @package           Genealogical_Tree
 *
 * @wordpress-plugin
 * Plugin Name:       Genealogical Tree
 * Plugin URI:        https://wordpress.org/plugins/genealogical-tree
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.1
 * Author:            ak devs
 * Author URI:        https://github.com/akdevsfr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       genealogical-tree
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

if ( function_exists( 'gt_fs' ) ) {
    gt_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'gt_fs' ) ) {
        // Create a helper function for easy SDK access.
        function gt_fs()
        {
            global  $gt_fs ;
            
            if ( !isset( $gt_fs ) ) {
                // Activate multisite network integration.
                if ( !defined( 'WP_FS__PRODUCT_3592_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_3592_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $gt_fs = fs_dynamic_init( array(
                    'id'             => '3592',
                    'slug'           => 'genealogical-tree',
                    'premium_slug'   => 'genealogical-tree-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_e7259dba96b5463b7e746506d5e2c',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'    => 'genealogical-tree',
                    'support' => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $gt_fs;
        }
        
        // Init Freemius.
        gt_fs();
        // Signal that SDK was initiated.
        do_action( 'gt_fs_loaded' );
        gt_fs()->add_action( 'after_uninstall', 'gt_fs_uninstall_cleanup' );
    }

}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GENEALOGICAL_TREE_VERSION', '1.0.1' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-genealogical-tree-activator.php
 */
function activate_genealogical_tree()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-genealogical-tree-activator.php';
    Genealogical_Tree_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-genealogical-tree-deactivator.php
 */
function deactivate_genealogical_tree()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-genealogical-tree-deactivator.php';
    Genealogical_Tree_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_genealogical_tree' );
register_deactivation_hook( __FILE__, 'deactivate_genealogical_tree' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-genealogical-tree.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_genealogical_tree()
{
    $plugin = new Genealogical_Tree();
    $plugin->run();
}

run_genealogical_tree();