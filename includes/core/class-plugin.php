<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

namespace CustomUserRoleManager\Core;

class Plugin {
    private static $instance = null;
    private $loader;

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->loadDependencies();
        $this->initHooks();
    }

    private function loadDependencies() {
        require_once CURM_PLUGIN_DIR . 'includes/core/class-loader.php';
        require_once CURM_PLUGIN_DIR . 'includes/admin/class-admin-menu.php';
        require_once CURM_PLUGIN_DIR . 'includes/admin/class-role-manager.php';
        require_once CURM_PLUGIN_DIR . 'includes/frontend/class-access-controller.php';

        $this->loader = new Loader();
    }

    private function initHooks() {
        // Initialize admin menu
        $admin_menu = new \CustomUserRoleManager\Admin\AdminMenu();
        $this->loader->addAction('admin_menu', $admin_menu, 'addMenuPages');
        $this->loader->addAction('admin_enqueue_scripts', $admin_menu, 'enqueueAssets');

        // Initialize role manager
        $role_manager = new \CustomUserRoleManager\Admin\RoleManager();
        $this->loader->addAction('init', $role_manager, 'registerCustomRoles');
        $this->loader->addAction('admin_init', $role_manager, 'registerSettings');

        // Initialize access controller
        $access_controller = new \CustomUserRoleManager\Frontend\AccessController();
        $this->loader->addAction('template_redirect', $access_controller, 'checkPageAccess');
        $this->loader->addAction('add_meta_boxes', $access_controller, 'addPageRestrictionsMetaBox');
        $this->loader->addAction('save_post', $access_controller, 'savePageRestrictions');

        $this->loader->run();
    }
}