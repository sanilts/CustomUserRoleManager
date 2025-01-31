<?php
namespace CustomUserRoleManager\Frontend;

class AccessController {
    public function checkPageAccess() {
        if (!is_page()) {
            return;
        }

        $page_id = get_queried_object_id();
        $restricted_roles = get_post_meta($page_id, '_curm_restricted_roles', true);

        if (!is_array($restricted_roles) || empty($restricted_roles)) {
            return;
        }

        if (!$this->userHasAccess($restricted_roles)) {
            $this->handleRedirect($page_id);
        }
    }

    private function userHasAccess($restricted_roles) {
        $current_user = wp_get_current_user();
        if (!$current_user->exists()) {
            return false;
        }

        foreach ($current_user->roles as $role) {
            if (!in_array($role, $restricted_roles)) {
                return true;
            }
        }

        return false;
    }

    private function handleRedirect($current_page_id) {
        $redirect_page_id = get_option('curm_redirect_page');
        
        if ($redirect_page_id && $redirect_page_id != $current_page_id) {
            wp_safe_redirect(get_permalink($redirect_page_id));
        } else {
            wp_safe_redirect(home_url());
        }
        exit;
    }

    public function addPageRestrictionsMetaBox() {
        add_meta_box(
            'curm-page-restrictions',
            __('Page Access Restrictions', 'custom-user-role-manager'),
            array($this, 'displayMetaBox'),
            'page',
            'side'
        );
    }

    public function displayMetaBox($post) {
        require CURM_PLUGIN_DIR . 'includes/admin/views/page-restrictions.php';
    }

    public function savePageRestrictions($post_id) {
        if (!$this->canSaveRestrictions($post_id)) {
            return;
        }

        $restricted_roles = isset($_POST['curm_restricted_roles']) 
            ? array_map('sanitize_text_field', $_POST['curm_restricted_roles']) 
            : array();

        update_post_meta($post_id, '_curm_restricted_roles', $restricted_roles);
    }

    private function canSaveRestrictions($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }

        if (!current_user_can('edit_page', $post_id)) {
            return false;
        }

        if (!isset($_POST['curm_page_restrictions_nonce']) || 
            !wp_verify_nonce($_POST['curm_page_restrictions_nonce'], 'curm_save_restrictions')) {
            return false;
        }

        return true;
    }
}