<?php
/*
* @link https://github.com/juniormfreitas/acf-php-to-json
* @since 0.1.0
*
* Plugin Name: ACF PHP to JSON Converter
* Description: Convert Advanced Custom Fields Pro migration file from PHP to JSON format in order to provide a better experience to developers in legacy Wordpress websites.
* Version: 0.1.1
* Author: Jose de Freitas
* Author URI: https://jfreitas.dev
* Plugin URI: https://github.com/juniormfreitas/acf-php-to-json
* Text Domain: acf-php-to-json
* Domain Path: /languages/
*/

if( ! defined( 'ABSPATH' ) ) exit;

if (! defined('ACF_PHP_TO_JSON_VERSION') ) {
    define('ACF_PHP_TO_JSON_VERSION', '0.1.1');
}

if (! defined('ACF_PHP_TO_JSON_NAME') ) {
    define('ACF_PHP_TO_JSON_NAME', 'ACF PHP to JSON Converter');
}

if (! defined('ACF_PHP_TO_JSON_PAGE_TITLE') ) {
    define('ACF_PHP_TO_JSON_PAGE_TITLE', 'ACF - Convert PHP migration fields to JSON');
}

if (! defined('ACF_PHP_TO_JSON_SLUG') ) {
    define('ACF_PHP_TO_JSON_SLUG', 'acf-php-to-json');
}

if (! defined('ACF_PHP_TO_JSON_POST_TYPE') ) {
    define('ACF_PHP_TO_JSON_POST_TYPE', 'acf-field-group');
}

if (! defined('ACF_PHP_TO_JSON_MENU_TITLE') ) {
    define('ACF_PHP_TO_JSON_MENU_TITLE', 'Convert PHP to JSON');
}

if (! defined('ACF_PHP_TO_JSON_BASENAME') ) {
    define('ACF_PHP_TO_JSON_BASENAME', plugin_basename( __FILE__ ));
}

if (! defined('ACF_PHP_TO_JSON_PLUGIN_DIR') ) {
    define('ACF_PHP_TO_JSON_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
}

function acf_php_to_json_scripts() {
    wp_enqueue_media();
    wp_enqueue_style( 'acf-php-to-json', plugins_url('/admin/css/acf-php-to-json.css', __FILE__), array(), null);
    wp_enqueue_script( 'acf-php-to-json', plugins_url('/admin/js/acf-php-to-json.js', __FILE__), array(), '1.0', true );
}
add_action( 'admin_enqueue_scripts', 'acf_php_to_json_scripts' );

require_once dirname( __FILE__ ) . '/admin/acf-php-to-json-admin.php';

function initPlugin() {
    $PhpToJson = new AcfPhpToJson;
    add_submenu_page('edit.php?post_type=' . $PhpToJson->post_type, $PhpToJson->page_title, $PhpToJson->menu_title, 'manage_options', $PhpToJson->menu_slug, function() use ($PhpToJson) {
        echo $PhpToJson->renderMainPage();
    });
}

add_action('admin_menu', 'initPlugin', 20);
