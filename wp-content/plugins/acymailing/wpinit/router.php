<?php
defined('ABSPATH') or die('Restricted access');
?><?php

class acyRouter extends acyHook
{
    var $activation;

    public function __construct($activation)
    {
        $this->activation = $activation;

        add_action('wp_ajax_acymailing_router', [$this, 'router']);
        add_action('wp_ajax_acymailing_frontrouter', [$this, 'frontRouter']);
        add_action('wp_ajax_nopriv_acymailing_frontrouter', [$this, 'frontRouter']);

        $pages = ['automation', 'bounces', 'campaigns', 'configuration', 'dashboard', 'dynamics', 'fields', 'file', 'language', 'lists', 'mails', 'queue', 'stats', 'users'];
        $headerPages = ['automation', 'bounces', 'campaigns', 'dashboard', 'fields', 'lists', 'mails', 'users', 'stats'];
        foreach ($pages as $page) {
            if (in_array($page, $headerPages)) {
                add_action('load-acymailing_page_acymailing_'.$page, [$this, 'waitHeaders']);
            }
            add_action('admin_print_scripts-acymailing_page_acymailing_'.$page, [$this, 'disableJsBreakingPages']);
            add_action('admin_print_styles-acymailing_page_acymailing_'.$page, [$this, 'removeCssBreakingPages']);
        }
        add_action('admin_print_scripts-toplevel_page_acymailing_dashboard', [$this, 'disableJsBreakingPages']);
        add_action('admin_print_styles-toplevel_page_acymailing_dashboard', [$this, 'removeCssBreakingPages']);
    }

    public function waitHeaders()
    {
        ob_start();
    }

    public function disableJsBreakingPages()
    {
        remove_action('admin_print_scripts', 'print_emoji_detection_script');

        wp_dequeue_script('select2.js');
        wp_dequeue_script('select2_js');
    }

    public function removeCssBreakingPages()
    {
        wp_dequeue_style('saswp-main-css');
    }

    public function frontRouter()
    {
        $this->router('_front');
    }

    public function router($suffix = '')
    {
        if (empty($suffix)) auth_redirect();

        $this->loadAcyLibrary();

        if (is_multisite()) {
            $currentBlog = get_current_blog_id();
            $sites = function_exists('get_sites') ? get_sites() : wp_get_sites();

            foreach ($sites as $site) {
                if (is_object($site)) {
                    $site = get_object_vars($site);
                }
                switch_to_blog($site['blog_id']);
                acym_config(true);
                $this->activation->updateAcym();
            }

            switch_to_blog($currentBlog);
        } else {
            $this->activation->updateAcym();
        }

        if (file_exists(ACYM_FOLDER.'update.php')) {
            unlink(ACYM_FOLDER.'update.php');
        }

        $config = acym_config(true);

        $ctrl = acym_getVar('cmd', 'ctrl', '');
        $task = acym_getVar('cmd', 'task', '');

        $needToMigrate = $config->get('migration') == 0 && acym_existsAcyMailing59() && acym_getVar('string', 'task') != 'migrationDone';

        if ((($needToMigrate || $config->get('walk_through') == 1) && !(defined('DOING_AJAX') && DOING_AJAX)) && 'dynamics' != $ctrl) {
            $ctrl = 'dashboard';
            acym_setVar('ctrl', $ctrl);
        }

        if (empty($ctrl)) {
            $ctrl = str_replace(ACYM_COMPONENT.'_', '', acym_getVar('cmd', 'page', ''));

            if (empty($ctrl)) {
                echo 'Page not found';

                return;
            }

            acym_setVar('ctrl', $ctrl);
        }

        if (!file_exists(constant('ACYM_CONTROLLER'.strtoupper($suffix)).$ctrl.'.php')) {
            echo 'Controller not found: '.$ctrl;

            return;
        }

        $controller = acym_get('controller'.$suffix.'.'.$ctrl);
        if (empty($task)) {
            $task = acym_getVar('cmd', 'defaulttask', $controller->defaulttask);
        }

        if (('frontusers' === $ctrl && 'unsubscribe' === $task) || (!defined('DOING_AJAX') || !DOING_AJAX)) {
            acym_loadAssets(acym_isAdmin() ? 'back' : 'front', $ctrl, $task);
            $controller->loadScripts($task);
        }

        $controller->$task();
    }
}

$acyRouter = new acyRouter($acyActivation);

