<?php
/**
 * Plugin Name: Custom User Role Manager
 * Description: Manages custom user roles and page restrictions
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: custom-user-role-manager
 */

if (!defined('ABSPATH')) {
    exit;
}

// Plugin Constants
define('CURM_VERSION', '1.0.0');
define('CURM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CURM_PLUGIN_URL', plugin_dir_url(__FILE__));

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'CustomUserRoleManager\\';
    $base_dir = CURM_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize Plugin
if (class_exists('CustomUserRoleManager\\Core\\Plugin')) {
    function curm_init() {
        return CustomUserRoleManager\Core\Plugin::getInstance();
    }
    curm_init();
}