<?php


namespace Post\Selector;

use Post_Selector;


/**
 * ADMIN Post-Selector Gellery Admin Option
 *
 * @link       https://wwdh.de
 * @since      1.0.0
 *
 * @package    Post_Selector
 * @subpackage Post_Selector/admin/gutenberg/
 */
defined( 'ABSPATH' ) or die();

class Post_Selector_Gallery {

	use Post_Selector_Defaults;

	/**
	 * The plugin Slug Path.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_dir plugin Slug Path.
	 */
	protected string $plugin_dir;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $basename The ID of this plugin.
	 */
	private string $basename;

	/**
	 * The Version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current Version of this plugin.
	 */
	private string $version;

	/**
	 * Store plugin main class to allow public access.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var Post_Selector $main The main class.
	 */
	private Post_Selector $main;

	/**
	 * Store plugin helper class.
	 *
	 * @param string $basename
	 * @param string $version
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @var Post_Selector $main
	 */
	public function __construct( string $basename, string $version,  Post_Selector $main ) {

		$this->basename   = $basename;
		$this->version    = $version;
		$this->main       = $main;

	}
}
