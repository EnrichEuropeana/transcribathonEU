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

class DashboardController extends acymailingController{

	var $aclCat = 'dashboard';

	function __construct($config = array()){
		parent::__construct($config);

		$this->registerTask('listing', 'display');

		$this->registerDefaultTask('listing');
	}

	function display($cachable = false, $urlparams = false){
		if(!empty($this->aclCat) AND !$this->isAllowed($this->aclCat, 'manage')) return;
		return parent::display($cachable, $urlparams);
	}
}
