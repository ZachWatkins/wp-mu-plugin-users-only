<?php
/**
 * ZWP Members Only
 *
 * @package      ZWP Members Only
 * @author       Zachary Watkins
 * @license      GPL-2.0+
 *
 * @zwp-members-only
 * Plugin Name:  ZWP Members Only
 * Plugin URI:   https://github.com/zwatkins2/zwp-members-only
 * Description:  A WordPress plugin for restricting a website to logged in users only.
 * Version:      0.1.0
 * Author:       Zachary Watkins
 * Author URI:   https://github.com/zachwatkins
 * Author Email: watkinza@gmail.com
 * Text Domain:  zwp-members-only
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
  die( 'We\'re sorry, but you can not directly access this file.' );
}

add_action( 'init', 'zwp_mo_forcelogin' );
function zwp_mo_forcelogin() {
  if (
    !is_user_logged_in()
    && !defined('DOING_AJAX')
    && !defined('DOING_CRON')
    && ( !defined('WP_CLI') || false === WP_CLI )
    && ( $_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_URI'] !== '/' )
  ) {
    $allowed_ports = array('80', '443');
    $url  = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
    $url .= '://' . $_SERVER['SERVER_NAME'];
    $url .= in_array( $_SERVER['SERVER_PORT'], $allowed_ports ) ? '' : ':' . $_SERVER['SERVER_PORT'];
    $url .= $_SERVER['REQUEST_URI'];
    // If we are not on the login page or the home page, redirect to the login page.
    if ( preg_replace( '/\?.*$/', '', $url ) !== preg_replace( '/\?.*$/', '', wp_login_url() ) ) {
      auth_redirect();
    }
  }
}

add_action( 'wp_head', 'zwp_mo_robots_nofollow' );
function zwp_mo_robots_nofollow() {
  ?><meta name='robots' content='noindex,follow' /><?php
}
