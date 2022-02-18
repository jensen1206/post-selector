<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wwdh.de
 * @since      1.0.0
 *
 * @package    Post_Selector
 * @subpackage Post_Selector/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Post_Selector
 * @subpackage Post_Selector/admin
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Post_Selector_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $basename    The ID of this plugin.
	 */
	private string $basename;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private string $version;

	/**
	 * Store plugin main class to allow public access.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var Post_Selector $main The main class.
	 */
	private  Post_Selector $main;

	/**
	 * License Config of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var  object $config License Config.
	 */
	private object $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name The name of this plugin.
	 * @param string $version    The version of this plugin.
	 *
	 *@since    1.0.0
	 */
	public function __construct( string $plugin_name, string $version, Post_Selector $main, object $config) {

		$this->basename = $plugin_name;
		$this->version = $version;
		$this->main = $main;
		$this->config = $config;
	}


	/**
	 * Register the Update-Checker for the Plugin.
	 *
	 * @since    1.0.0
	 */
	public function set_post_selector_update_checker() {

		if(get_option("{$this->basename}_server_api")) {
			$postSelectorUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
				get_option("{$this->basename}_server_api")['update_url'],
				WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->basename . DIRECTORY_SEPARATOR . $this->basename . '.php',
				$this->basename
			);
			if (get_option("{$this->basename}_server_api")['update_type'] == '1') {
				$postSelectorUpdateChecker->getVcsApi()->enableReleaseAssets();
			}
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_Selector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_Selector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->basename, plugin_dir_url( __FILE__ ) . 'css/post-selector-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_Selector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_Selector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->basename, plugin_dir_url( __FILE__ ) . 'js/post-selector-admin.js', array( 'jquery' ), $this->version, true );

	}

}
