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
