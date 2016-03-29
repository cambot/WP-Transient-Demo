<?php
/**
 * Plugin Name: WP Transient Demo
 * Plugin URI: https://github.com/cambot/wp-transient-demo
 * Description: Getting to know WordPress Transients.
 * Version: 0.1
 * Author: Cameron Macintosh
**/

defined( 'ABSPATH' ) or die( 'No script kiddies please!');

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
			'dashicons-admin-site',
			75
		);
	}

	function page() {
		echo "<h1>My Little Transient</h1>";
		echo "<p>Trying out WordPress Transients.</p>";
	}
}

new transientDemo(); 
