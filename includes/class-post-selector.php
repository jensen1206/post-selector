<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wwdh.de
 * @since      1.0.0
 *
 * @package    Post_Selector
 * @subpackage Post_Selector/includes
 */

use Hupa\License\Register_Api_WP_Remote;
use Hupa\License\Register_Product_License;


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
 * @package    Post_Selector
 * @subpackage Post_Selector/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */


class Post_Selector {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Post_Selector_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected Post_Selector_Loader $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected string $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected string $version = '';

	/**
	 * The current database version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $db_version The current database version of the plugin.
	 */
	protected string $db_version;

	/**
	 * Store plugin main class to allow public access.
	 *
	 * @since    1.0.0
	 * @var object The main class.
	 */
	public object $main;

	/**
	 * The plugin Slug Path.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_slug plugin Slug Path.
	 */
	private string $plugin_slug;

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



		$this->plugin_name = POST_SELECTOR_BASENAME;
		$this->plugin_slug = POST_SELECTOR_SLUG_PATH;
		$this->main        = $this;


		$plugin = get_file_data(plugin_dir_path( dirname( __FILE__ ) ) . $this->plugin_name . '.php', array('Version' => 'Version'), false);
		if(!$this->version){
			$this->version = $plugin['Version'];
		}

		if ( defined( 'POST_SELECTOR_PLUGIN_DB_VERSION' ) ) {
			$this->db_version = POST_SELECTOR_PLUGIN_DB_VERSION;
		} else {
			$this->db_version = '1.0.0';
		}

		$this->check_dependencies();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_wp_remote_api_license_class();
		$this->define_product_license_class();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Post_Selector_Loader. Orchestrates the hooks of the plugin.
	 * - Post_Selector_i18n. Defines internationalization functionality.
	 * - Post_Selector_Admin. Defines all hooks for the admin area.
	 * - Post_Selector_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-selector-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-selector-i18n.php';

		/**
		 * Update-Checker-Autoload
		 * Git Update for Theme|Plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/update-checker/autoload.php';


		/**
		 * The class responsible for defining all WP_Remote actions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/license/admin/class_register_api_wp_remote.php';


		/**
		 * // JOB The class responsible for defining all actions that occur in the license area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/license/class_register_product_license.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		if(is_file(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-post-selector-admin.php')){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-post-selector-admin.php';
		}


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-post-selector-public.php';

		$this->loader = new Post_Selector_Loader();

	}

	/**
	 * Check PHP and WordPress Version
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function check_dependencies(): void {
		global $wp_version;
		if ( version_compare( PHP_VERSION, POST_SELECTOR_MIN_PHP_VERSION, '<' ) || $wp_version < POST_SELECTOR_MIN_WP_VERSION ) {
			$this->maybe_self_deactivate();
		}
	}

	/**
	 * Self-Deactivate
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function maybe_self_deactivate(): void {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		deactivate_plugins( $this->plugin_slug );
		add_action( 'admin_notices', array( $this, 'self_deactivate_notice' ) );
	}

	/**
	 * Self-Deactivate Admin Notiz
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function self_deactivate_notice(): void {
		echo sprintf( '<div class="notice notice-error is-dismissible" style="margin-top:5rem"><p>' . __( 'This plugin has been disabled because it requires a PHP version greater than %s and a WordPress version greater than %s. Your PHP version can be updated by your hosting provider.', 'hupa-teams' ) . '</p></div>', POST_SELECTOR_MIN_PHP_VERSION, POST_SELECTOR_MIN_WP_VERSION );
		exit();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Post_Selector_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Post_Selector_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_wp_remote_api_license_class() {

		global $license_wp_remote;
		$license_wp_remote = new Register_Api_WP_Remote( $this->get_plugin_name(), $this->get_version(), $this->get_license_config(), $this->main );
		$this->loader->add_action('plugin_loaded', $license_wp_remote, 'init_register_license_wp_remote_api');

	}

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_product_license_class() {

		if(!get_option('hupa_server_url')){
			update_option('hupa_server_url', $this->get_license_config()->api_server_url);
		}
		global $product_license;
		$product_license = new Register_Product_License( $this->get_plugin_name(), $this->get_version(), $this->get_license_config(), $this->main );
		$this->loader->add_action( 'init', $product_license, 'license_site_trigger_check' );
		$this->loader->add_action( 'template_redirect', $product_license, 'license_callback_site_trigger_check' );
	}

	/**
	 * Register all the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		//if(is_file(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-post-selector-admin.php') && get_option( $this->plugin_name . '/product_install_authorize' )) {
			$plugin_admin = new Post_Selector_Admin( $this->get_plugin_name(), $this->get_version(), $this->main, $this->get_license_config() );
			$this->loader->add_action( 'init', $plugin_admin, 'set_post_selector_update_checker' );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		//}
	}

	/**
	 * Register all the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Post_Selector_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all the hooks with WordPress.
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
	public function get_plugin_name(): string {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Post_Selector_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader(): Post_Selector_Loader {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version(): string {
		return $this->version;
	}

	/**
	 * Retrieve the database version number of the plugin.
	 *
	 * @return    string    The database version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_db_version(): string {
		return $this->db_version;
	}

	/**
	 * License Config for the plugin.
	 *
	 * @return    object License Config.
	 * @since     1.0.0
	 */
	public function get_license_config():object {
		$config_file = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/license/config.json';

		return json_decode(file_get_contents($config_file));
	}

}
