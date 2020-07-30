<?php 
/*
 * Plugin Name:  Sam shipping class
 * Plugin URI: http://uikar.com
 * Description: Adding wordpress shipping method
 * Version: 1.0
 * Author: Saman Tohidian
 * Author URI: http://uikar.com
 * Text Domain: uikar-shipping-method
 * Domain Path: /languages/
 *
 */
define('UIKAR_SHIPPING_METHOD_DIR', plugin_dir_path(__FILE__));
define('UIKAR_SHIPPING_METHOD_URL', plugin_dir_url(__FILE__));

require_once(UIKAR_SHIPPING_METHOD_DIR.'includes/functions.php');

register_activation_hook(__FILE__, 'uikar_shipping_method_activation');

function uikar_shipping_method_activation() {
    
    uikar_add_shipping_method();
}