<?php
/*
Plugin Name: AcyMailing
Description: Manage your contact lists and send newsletters from your site.
Author: AcyMailing Newsletter Team
Author URI: https://www.acyba.com
License: GPLv3
Version: 6.10.3
Text Domain: acymailing
Domain Path: /language
*/
defined('ABSPATH') || die('Restricted Access');

// Load defines
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);


include_once __DIR__.DS.'wpinit'.DS.'init.php';
include_once __DIR__.DS.'wpinit'.DS.'activation.php';
include_once __DIR__.DS.'wpinit'.DS.'update.php';
include_once __DIR__.DS.'wpinit'.DS.'widget.php';
include_once __DIR__.DS.'wpinit'.DS.'router.php';
include_once __DIR__.DS.'wpinit'.DS.'menu.php';
include_once __DIR__.DS.'wpinit'.DS.'usersynch.php';
include_once __DIR__.DS.'wpinit'.DS.'woocommerce.php';
include_once __DIR__.DS.'wpinit'.DS.'message.php';
include_once __DIR__.DS.'wpinit'.DS.'elementor.php';
include_once __DIR__.DS.'wpinit'.DS.'ultimatemember.php';
