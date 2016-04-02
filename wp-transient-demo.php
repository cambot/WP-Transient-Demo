<?php
/**
 * Plugin Name: WP Transient Demo
 * Plugin URI: https://github.com/cambot/wp-transient-demo
 * Description: Getting to know WordPress Transients.
 * Version: 0.1
 * Author: Cameron Macintosh
**/

defined( 'ABSPATH' ) or die( 'No script kiddies please!');

/**
 * @see www.meetup.com/meetup_api/docs/
 * Meetup api v3.
**/
class meetup_api {

	private $urlname;
	private $last_response;

	function __construct($urlname) {
		$this->urlname = $urlname;
	}

	public function get_urlname() {
		return $this->urlname;
	}

	public function get_last_response($response) {
		return $this->last_response;
	}
	public function set_last_response($response) {
		$this->last_response = $response;
		return $this;
	}

	public function get_events() {
		$url = "https://api.meetup.com/{$this->get_urlname()}/events";
		// $response is an array with 'body' and 'header' (or WP_Error instance)
		$response = wp_remote_get($url);
		if ( is_array($response) && !is_wp_error($response) ) {
			$this->set_last_response($response);
			return $response['body'];
		}
		if ( is_wp_error($response) ) {
			return false;
		}
		return array();
	}	
}

class transientDemo {
	function __construct() {
		add_action( 'init', array( &$this, 'init'));
	}

	function init() {
		add_action( 'admin_menu', array( &$this, 'admin_menu'));
	}

	function admin_menu() {
		add_menu_page( 'My Little Transient',
			'Transient Demo',
			'administrator',
			'transient-demo',
			array( &$this, 'page'),
			'dashicons-admin-site',  // @see https://developer.wordpress.org/resource/dashicons/
			75
		);
	}

	function page() {
		echo "<h1>My Little Transient</h1>";
		echo "<p>Trying out WordPress Transients.</p>";
	}
}

new transientDemo(); 
