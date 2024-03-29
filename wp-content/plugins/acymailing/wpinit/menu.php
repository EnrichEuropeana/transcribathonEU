<?php
defined('ABSPATH') or die('Restricted access');
?><?php

class acyMenu extends acyHook
{
    var $router;

    public function __construct($router)
    {
        $this->router = $router;

        if (defined('WP_ADMIN') && WP_ADMIN) {
            add_action('admin_menu', [$this, 'addMenus'], 99);
        }
    }

    public function addMenus()
    {
        $capability = 'read';

        add_submenu_page(
            null,
            'front',
            'front',
            $capability,
            ACYM_COMPONENT.'_front',
            [$this->router, 'frontRouter']
        );

        $config = acym_config();
        $allowedGroups = explode(',', $config->get('wp_access', 'administrator'));
        $userGroups = acym_getGroupsByUser();

        $allowed = false;
        foreach ($userGroups as $oneGroup) {
            if ($oneGroup == 'administrator' || in_array($oneGroup, $allowedGroups)) {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) return;


        $pluginClass = acym_get('class.plugin');
        $nbPluginNotUptodate = $pluginClass->getNotUptoDatePlugins();

        $svg = acym_fileGetContent(ACYM_IMAGES.'loader.svg');
        add_menu_page(
            acym_translation('ACYM_DASHBOARD'),
            'AcyMailing',
            $capability,
            ACYM_COMPONENT.'_dashboard',
            [$this->router, 'router'],
            'data:image/svg+xml;base64,'.base64_encode(
                $svg
            ),
            42
        );

        $menus = [
            'ACYM_USERS' => 'users',
            'ACYM_CUSTOM_FIELDS' => 'fields',
            'ACYM_LISTS' => 'lists',
            'ACYM_EMAILS' => 'campaigns',
            'ACYM_TEMPLATES' => 'mails',
            'ACYM_AUTOMATION' => 'automation',
            'ACYM_QUEUE' => 'queue',
            'ACYM_STATISTICS' => 'stats',
            'ACYM_BOUNCE_HANDLING' => 'bounces',
            empty($nbPluginNotUptodate) ? 'ACYM_ADD_ONS' : acym_translation_sprintf('ACYM_ADD_ONS_X', $nbPluginNotUptodate) => 'plugins',
            'ACYM_SUBSCRIPTION_FORMS' => 'forms',
            'ACYM_CONFIGURATION' => 'configuration',
        ];
        foreach ($menus as $title => $ctrl) {
            add_submenu_page(ACYM_COMPONENT.'_dashboard', acym_translation($title), acym_translation($title), $capability, ACYM_COMPONENT.'_'.$ctrl, [$this->router, 'router']);
        }

        $controllers = ['dynamics', 'file', 'language'];
        foreach ($controllers as $oneCtrl) {
            add_submenu_page(null, $oneCtrl, $oneCtrl, $capability, ACYM_COMPONENT.'_'.$oneCtrl, [$this->router, 'router']);
        }

        global $submenu;
        if (isset($submenu[ACYM_COMPONENT.'_dashboard'])) {
            $submenu[ACYM_COMPONENT.'_dashboard'][0][0] = acym_translation('ACYM_DASHBOARD');
        }
    }
}

$acyMenu = new acyMenu($acyRouter);

