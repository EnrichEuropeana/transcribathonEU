<?php
defined('ABSPATH') or die('Restricted access');
?><?php

abstract class acyHook
{
    public function loadAcyLibrary()
    {
        if (function_exists('acym_get')) return true;
        $helperFile = rtrim(dirname(__DIR__), DS).DS.'back'.DS.'helpers'.DS.'helper.php';
        if (!file_exists($helperFile) || !include_once $helperFile) return false;

        return true;
    }

    public function addRegistrationFields($externalPluginConfig = '')
    {
        if (!$this->loadAcyLibrary()) return;

        $config = acym_config();

        $displayOnExternalPlugin = true;
        if (!empty($externalPluginConfig)) $displayOnExternalPlugin = $config->get($externalPluginConfig, 0) == 1;

        if (!$config->get('regacy', 0) || !$displayOnExternalPlugin) return;

        $regacyHelper = acym_get('helper.regacy');
        if (!$regacyHelper->prepareLists(['formatted' => true])) return;

        ?>
		<div class="acym__regacy">
			<label class="acym__regacy__label"><?php echo $regacyHelper->label; ?></label>
			<div class="acym__regacy__values"><?php echo $regacyHelper->lists; ?></div>
		</div>
        <?php
    }
}

