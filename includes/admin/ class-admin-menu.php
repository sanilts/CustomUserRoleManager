<?php
namespace CustomUserRoleManager\Admin;

class AdminMenu {
    public function addMenuPages() {
        add_menu_page(
            __('User Role Manager', 'custom-user-role-manager'),
            __('Role Manager', 'custom-user-role-manager'),
            'manage_options',
            'user-role-manager',
            array($this, 'displayMainPage'),
            'dashicons-groups'
        );
    }

    public function displayMainPage() {
        require_once CURM_PLUGIN_DIR . 'includes/admin/views/role-manager.php';
    }

    public function enqueueAssets($hook) {
        if ('toplevel_page_user-role-manager' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'curm-admin',
            CURM_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            CURM_VERSION
        );

        wp_enqueue_script(
            'curm-admin',
            CURM_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            CURM_VERSION,
            true
        );
    }
}