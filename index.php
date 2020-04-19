<?php
/*
Plugin Name: ACF PHP to JSON Converter
Description: Convert Advanced Custom Fields Pro migration file from PHP to JSON format in order to provide a better experience to developers in legacy Wordpress websites.
Version: 0.0.2
Author: Jose de Freitas
Author URI: https://jfreitas.dev
*/

if( ! defined( 'ABSPATH' ) ) exit;

function acf_php_to_json_scripts() {
    wp_enqueue_media();
    wp_enqueue_style( 'acf-php-to-json', plugins_url('css/acf-php-to-json.css', __FILE__), array(), null) ;
}
add_action( 'admin_enqueue_scripts', 'acf_php_to_json_scripts' );

require_once dirname( __FILE__ ) . '/acf-php-to-json.php';

function initPlugin() {
    $PhpToJson = new AcfPhpToJson;
    add_submenu_page('edit.php?post_type=' . $PhpToJson->post_type, $PhpToJson->page_title, $PhpToJson->menu_title, 'manage_options', $PhpToJson->menu_slug, function() use ($PhpToJson) {
        echo $PhpToJson->renderMainPage();
    });
}

add_action('admin_menu', 'initPlugin', 20);
