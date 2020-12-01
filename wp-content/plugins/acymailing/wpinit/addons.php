<?php
defined('ABSPATH') or die('Restricted access');
?><?php

class acyAddons extends acyHook
{
    public function __construct()
    {
        acym_trigger('onAcymInitWordpressAddons');
    }
}

$acyPlugin = new acyAddons();

