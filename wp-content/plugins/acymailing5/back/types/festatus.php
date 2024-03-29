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

class festatusType extends acymailingClass{
	function __construct(){
		parent::__construct();
		$this->values = array();
		$this->values[0] = acymailing_selectOption('-1', acymailing_translation('JOOMEXT_NO'));
		$this->values[1] = acymailing_selectOption('1', acymailing_translation('JOOMEXT_YES'));
		$this->values[0]->class = 'btn-danger';
		$this->values[1]->class = 'btn-success';
	}

	function display($map,$value){
		static $i = 0;
		$value = (int) $value;
		$value = ($value >= 1) ? 1 : -1;
		return acymailing_radio($this->values, $map , 'class="radiobox" size="1"', 'value', 'text', (int) $value,'status'.$i++);
	}

}
