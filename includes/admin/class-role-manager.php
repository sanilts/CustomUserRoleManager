<?php
namespace CustomUserRoleManager\Admin;

class RoleManager {
    public function registerCustomRoles() {
        $custom_roles = get_option('curm_custom_roles', array());
        
        foreach ($custom_roles as $role) {
            $role_name = sanitize_title($role['name']);
            if (!$this->roleExists($role_name)) {
                add_role($role_name, $role['name'], $role['capabilities'] ?? array());
            }
        }
    }

    public function registerSettings() {
        register_setting('curm_settings', 'curm_custom_roles');
        register_setting('curm_settings', 'curm_redirect_page');
    }

    private function roleExists($role_name) {
        return wp_roles()->is_role($role_name);
    }

    public function getAllCapabilities() {
        global $wp_roles;
        $capabilities = array();
        
        foreach ($wp_roles->roles as $role) {
            if (isset($role['capabilities']) && is_array($role['capabilities'])) {
                $capabilities = array_merge($capabilities, array_keys($role['capabilities']));
            }
        }
        
        return array_unique($capabilities);
    }
}