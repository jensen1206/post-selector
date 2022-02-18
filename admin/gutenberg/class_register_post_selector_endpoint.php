<?php

namespace Post\Selector;

use Post_Selector;
use stdClass;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * ADMIN Post-Selector Gutenberg ENDPOINT
 *
 * @link       https://wwdh.de
 * @since      1.0.0
 *
 * @package    Post_Selector
 * @subpackage Post_Selector/admin/gutenberg/
 */
defined( 'ABSPATH' ) or die();

class Register_Post_Selector_Endpoint {
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
	 * Store plugin main class to allow public access.
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

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_post_selector_routes()
	{
		$version = '2';
		$namespace = 'post-selector-endpoint/v' . $version;
		$base = '/';

		@register_rest_route(
			$namespace,
			$base . 'get-items/(?P<method>[\S]+)',

			array(
				'methods' => WP_REST_Server::EDITABLE,
				'callback' => array($this, 'post_selector_rest_endpoint_get_response'),
				'permission_callback' => array($this, 'permissions_check')
			)
		);
	}

	/**
	 * Get one item from the collection.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function post_selector_rest_endpoint_get_response(WP_REST_Request $request) {
		$method = (string)$request->get_param('method');
		if (!$method) {
			return new WP_Error(404, ' Method failed');
		}

		return $this->get_method_item($method);
	}

	/**
	 * GET Post Meta BY ID AND Field
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_method_item($method) {
		if (!$method) {
			return new WP_Error(404, ' Method failed');

		}
		$tempArr = [];
		$response = new stdClass();
		switch ( $method ) {
			case 'get_post_slider':

			break;
		}
	}

	/**
	 * Check if a given request has access.
	 *
	 * @return bool
	 */
	public function permissions_check(): bool
	{
		return current_user_can('edit_posts');
	}
}