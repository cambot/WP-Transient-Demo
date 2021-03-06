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
			var_dump($response);
			return false;
		}
		return array();
	}	
}

class transientDemo {
	function __construct() {
		add_action( 'init', array( &$this, 'init'));
		add_action( 'admin_init', array( &$this, 'settings'));
	}

	function init() {
		add_action( 'admin_menu', array( &$this, 'admin_menu'));
	}
	function settings() {
		register_setting( 'wptd-settings-group', 'urlname');
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

	/*
	* @see https://codex.wordpress.org/Transients_API
	*/
	function page() {
		$old_urlname = esc_attr( get_option('urlname') );
?>
<h1>My Little Transient</h1>
<p>Trying out WordPress Transients.</p>
<form method="post" action="options.php">
	<?php settings_fields( 'wptd-settings-group' ); ?>
	<?php do_settings_sections( 'wptd-settings-group' ); ?>
	<label>Meetup Group urlname: <input type="text" name="urlname" value="<?= $old_urlname ?>" /></label>
	<?php submit_button(); ?>
</form>

<h2>The Response</h2>
<?php

		$transient_id = "my-demo-transient";
		$transient_value = get_transient($transient_id);
		if ( false === $transient_value ) {
			echo "<p>Transient is currently empty and was reloaded.</p>";
			$btownrb_meetup = new meetup_api($old_urlname);
			$transient_value = $btownrb_meetup->get_events();
			set_transient($transient_id, $transient_value, 30);
		}
		var_dump($transient_value);
	}

}

new transientDemo(); 
