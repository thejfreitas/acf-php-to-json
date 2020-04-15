<?php
/*
Plugin Name: ACF PHP to JSON Converter
Description: Wordpress plugin responsible to convert Advanced Custom Fields migration in PHP to JSON format in order to provide a better experience to developers for legacy Wordpress websites.
Version: 0.0.1
Author: J. de Freitas
Author URI: https://jfreitas.dev
*/

if( ! defined( 'ABSPATH' ) ) exit;

require_once dirname( __FILE__ ) . '/acf-php-to-json.php';

function initPlugin() {
    $PhpToJson = new AcfPhpToJson;
    add_submenu_page('edit.php?post_type=' . $PhpToJson->post_type, $PhpToJson->page_title, $PhpToJson->menu_title, 'manage_options', $PhpToJson->menu_slug, function() use ($PhpToJson) {
        echo $PhpToJson->renderMainPage();
    });
}

add_action('admin_menu', 'initPlugin', 20);
