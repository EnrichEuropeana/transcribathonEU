<?php
/**
 * @package	AcyMailing for WordPress
 * @version	5.10.12
 * @author	acyba.com
 * @copyright	(C) 2009-2020 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') or die('Restricted access');
?><?php
	$resultUsers = acymailing_loadResult('SELECT count(*) FROM `#__vemod_news_mailer_users`');
	
	echo acymailing_translation_sprintf('USERS_IN_COMP',$resultUsers,'Vemod News Mailer');

