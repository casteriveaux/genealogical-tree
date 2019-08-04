<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Genealogical_Tree_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GENEALOGICAL_TREE_VERSION' ) ) {
			$this->version = GENEALOGICAL_TREE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'genealogical-tree';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_api_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Genealogical_Tree_Loader. Orchestrates the hooks of the plugin.
	 * - Genealogical_Tree_i18n. Defines internationalization functionality.
	 * - Genealogical_Tree_Admin. Defines all hooks for the admin area.
	 * - Genealogical_Tree_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-genealogical-tree-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-genealogical-tree-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-genealogical-tree-admin.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'api/class-genealogical-tree-api.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-genealogical-tree-public.php';

		$this->loader = new Genealogical_Tree_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Genealogical_Tree_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Genealogical_Tree_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Genealogical_Tree_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		$this->loader->add_action( 'init', $plugin_admin, 'init_post_type_and_taxonomy' );
		$this->loader->add_filter( 'parent_file', $plugin_admin, 'set_family_group_current_menu' );
		
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes_member_info' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_boxes_family_info' );
		
		$this->loader->add_action( 'post_updated', $plugin_admin, 'update_meta_boxes_member_info');

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'family_group_validation_notice_handler');

		$this->loader->add_action( 'create_family_group', $plugin_admin, 'create_family_group_free');
	
		$this->loader->add_filter( 'manage_edit-member_columns', $plugin_admin, 'member_columns' );
		$this->loader->add_filter( 'manage_edit-member_sortable_columns', $plugin_admin, 'member_sortable_columns' );
		$this->loader->add_action( 'manage_member_posts_custom_column', $plugin_admin, 'member_posts_born_column', 10, 2  );
		$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'member_born_orderby', 10, 2  );


		

	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_api_hooks() {

		$plugin_api = new Genealogical_Tree_Api( $this->get_plugin_name(), $this->get_version() );


		$this->loader->add_action( 'rest_api_init', $plugin_api, 'register_rest_route_member_ind' );
		$this->loader->add_action( 'rest_api_init', $plugin_api, 'register_rest_route_family_fam' );
		$this->loader->add_action( 'rest_api_init', $plugin_api, 'register_rest_route_member_popover' );

		
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Genealogical_Tree_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'the_content', $plugin_public, 'data_in_single_page');
		
		add_shortcode( 'gt-tree', array( $plugin_public, 'display_tree' ));
		add_shortcode( 'gt-members', array( $plugin_public, 'display_members' ));


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Genealogical_Tree_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
