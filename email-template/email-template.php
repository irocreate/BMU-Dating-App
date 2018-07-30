<?php

/**
 * Class Wpdating_email_template
 * This class is used for sending emails through wpdating
 *
 * @since 5.9.1
 */
class Wpdating_email_template {

	protected static $instance = null;

	public function __construct() {

	}

	/**
	 * This function is used to add filter before is sent.
	 *
	 * @since 5.9.1
	 */
	public static function add_filter() {
		add_filter( 'wp_mail_content_type', array( __CLASS__, 'set_content_type' ), 102 );
	}

	/**
	 * This function is used to set the content type.
	 *
	 * @since 5.9.1
	 * @param $content_type
	 *
	 * @return string
	 */
	public static function set_content_type( $content_type ) {
		if ( $content_type == 'text/plain' ) {
			return 'text/html';
		}
	}

	/**
	 * This function is used to send email
	 *
	 * @since 5.9.1
	 * @param $to
	 * @param $subject
	 * @param $message
	 * @param string $headers
	 * @param array $attachments
	 *
	 * @return bool
	 */
	public static function send_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
		self::add_filter();
		$result = wp_mail( $to, $subject, $message, $headers, $attachments );
		self::remove_filter();

		return $result;
	}

	/**
	 * This function is used to remove the filter after email is sent
	 */
	public static function remove_filter() {
		remove_filter( 'wp_mail_content_type', array( __CLASS__, 'set_content_type' ,102) );
	}

	public static function get_instance() {

		if ( self::$instance === null ) {
			self::$instance = new Wpdating_email_template();
		}

		return self::$instance;
	}

}
