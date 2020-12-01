<?php
defined('ABSPATH') or die('Restricted access');
?><?php

class acyActivation extends acyHook
{
    public function __construct()
    {
        $entryPoint = dirname(__DIR__).'/index.php';
        register_activation_hook($entryPoint, [$this, 'install']);

        add_filter('plugin_action_links_'.plugin_basename($entryPoint), [$this, 'addPluginLinks']);
    }

    public function install()
    {
        $file_name = rtrim(dirname(__DIR__), DS).DS.'back'.DS.'tables.sql';
        $handle = fopen($file_name, 'r');
        $queries = fread($handle, filesize($file_name));
        fclose($handle);

        if (is_multisite()) {
            $currentBlog = get_current_blog_id();
            $sites = function_exists('get_sites') ? get_sites() : wp_get_sites();

            foreach ($sites as $site) {
                if (is_object($site)) {
                    $site = get_object_vars($site);
                }
                switch_to_blog($site['blog_id']);
                $this->_sampledata($queries);
            }

            switch_to_blog($currentBlog);
        } else {
            $this->_sampledata($queries);
        }

        if (file_exists(ACYM_FOLDER.'update.php')) {
            unlink(ACYM_FOLDER.'update.php');
        }
    }

    private function _sampledata($queries)
    {
        global $wpdb;
        $prefix = acym_getPrefix();

        $acytables = str_replace('#__', $prefix, $queries);
        $tables = explode('CREATE TABLE IF NOT EXISTS', $acytables);

        foreach ($tables as $oneTable) {
            $oneTable = trim($oneTable);
            if (empty($oneTable)) {
                continue;
            }
            $wpdb->query('CREATE TABLE IF NOT EXISTS'.$oneTable);
        }

        $this->updateAcym();
    }

    public function updateAcym()
    {
        $config = acym_config();
        if (!file_exists(ACYM_FOLDER.'update.php') && $config->get('installcomplete', 0) != 0) {
            return;
        }

        require_once ACYM_FOLDER.'install.class.php';

        if (!class_exists('acymInstall')) return;

        acym_increasePerf();

        $installClass = new acymInstall();
        $installClass->addPref();
        $installClass->updatePref();
        $installClass->updateSQL();

        $updateHelper = acym_get('helper.update');
        $updateHelper->fromLevel = $installClass->fromLevel;
        $updateHelper->fromVersion = $installClass->fromVersion;

        $languageFiles = acym_getFiles(ACYM_FOLDER.'language'.DS, '\.ini');
        acym_createFolder(ACYM_LANGUAGE);
        acym_createFolder(ACYM_UPLOAD_FOLDER_THUMBNAIL);
        foreach ($languageFiles as $oneFile) {
            acym_copyFile(ACYM_FOLDER.'language'.DS.$oneFile, ACYM_LANGUAGE.$oneFile);
        }

        $updateHelper->installLanguages();
        $updateHelper->installBounceRules();
        $updateHelper->installList();
        $updateHelper->installNotifications();
        if (!$installClass->update) {
            $updateHelper->installTemplates(true);
        }
        $updateHelper->installFields();
        $updateHelper->installAdminNotif();
        $updateHelper->installAddons();

        $newConfig = new stdClass();
        $newConfig->installcomplete = 1;
        $config->save($newConfig);

        acym_config(true);
    }

    public function addPluginLinks($links)
    {
        $settings_link = '<a href="admin.php?page='.ACYM_COMPONENT.'_configuration">'.__('Settings').'</a>';
        $links = array_merge([$settings_link], $links);

        return $links;
    }
}

$acyActivation = new acyActivation();

