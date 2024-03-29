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

class acltableType extends acymailingClass{
	function __construct(){
		parent::__construct();

		if(!ACYMAILING_J16){
			$acl = JFactory::getACL();
			$this->groups = $acl->get_group_children_tree(null, 'USERS', false);
		}else{
			$this->groups = acymailing_getGroups();
			foreach($this->groups as $id => $group){
				if(isset($this->groups[$group->parent_id])){
					$this->groups[$id]->level = intval(@$this->groups[$group->parent_id]->level) + 1;
					$this->groups[$id]->text = str_repeat('- - ', $this->groups[$id]->level).$this->groups[$id]->text;
				}
			}
		}
		$this->choice = array();
		$this->choice[] = acymailing_selectOption('all', acymailing_translation('ACY_ALL'));
		$this->choice[] = acymailing_selectOption('special', acymailing_translation('ACY_CUSTOM'));

		$this->config = acymailing_config();

		$js = "function updateACLTable(cat,action){
			choice = eval('document.adminForm.acl_'+cat);
			choiceValue = 'special';
			for (var i=0; i < choice.length; i++){
				 if (choice[i].checked){
					 choiceValue = choice[i].value;
				}
			}

			if(choiceValue == 'all'){
				document.getElementById('div_acl_'+cat).style.display = 'none';
			}else{
				document.getElementById('div_acl_'+cat).style.display = 'block';
				finalValue = '';
				for(i=0;i<allGroups.length;i++){
					var myvar = document.getElementById('acl_'+cat+'_'+allGroups[i]+'_'+action);
					if(myvar && myvar.checked){
							 finalValue += myvar.value+',';
					}
				}
				document.getElementById('acl_'+cat+'_'+action).value = finalValue;
			}
		}
		function updateGroup(cat,groupid,actions){
			for(i=0;i<actions.length;i++){
				var myvar = document.getElementById('acl_'+cat+'_'+groupid+'_'+actions[i]);
				if(!myvar) return;
				myvar.checked = 1 - myvar.checked;
				updateACLTable(cat,actions[i]);
			}
		}
		function updateAction(cat,action){
			for(i=0;i<allGroups.length;i++){
				var myvar = document.getElementById('acl_'+cat+'_'+allGroups[i]+'_'+action);
				if(myvar) myvar.checked = 1 - myvar.checked;
			}
			updateACLTable(cat,action);
		}
		var allGroups = new Array(";
		foreach($this->groups as $oneGroup){
			$js .= "'".$oneGroup->value."',";
		}
		$js = rtrim($js, ',').");";

		acymailing_addScript(true, $js);
	}

	function display($category, $actions){

		$oneAction = reset($actions);
		$acltable = '<table class="acymailing_smalltable"><thead><tr><th></th>';
		foreach($actions as $action){
			$trans = acymailing_translation('ACY_'.strtoupper($action));
			if($trans == 'ACY_'.strtoupper($action)) $trans = acymailing_translation(strtoupper($action));
			$acltable .= '<th><span style="cursor:pointer" onclick="updateAction(\''.$category.'\',\''.$action.'\')">'.$trans.'</span><input type="hidden" name="config[acl_'.$category.'_'.$action.']" id="acl_'.$category.'_'.$action.'" value="'.$this->config->get('acl_'.$category.'_'.$action, 'all').'"/></th>';
		}
		$acltable .= '</tr></thead><tbody>';
		$custom = false;
		foreach($this->groups as $oneGroup){
			$acltable .= '<tr class="aclline"><td valign="top" class="groupname"><span style="cursor:pointer" onclick="updateGroup(\''.$category.'\',\''.$oneGroup->value.'\',new Array(\''.implode("','", $actions).'\'))">'.$oneGroup->text.'</span></td>';
			foreach($actions as $action){
				$acltable .= '<td class="checkfield">';
				$value = $this->config->get('acl_'.$category.'_'.$action, 'all');
				if(ACYMAILING_J16 || !in_array($oneGroup->value, array(29, 30))){
					if(acymailing_isAllowed($value, $oneGroup->value)){
						$checked = 'checked="checked"';
					}else{
						$custom = true;
						$checked = '';
					}
					$acltable .= '<input type="checkbox" id="acl_'.$category.'_'.$oneGroup->value.'_'.$action.'" onclick="updateACLTable(\''.$category.'\',\''.$action.'\');" value="'.$oneGroup->value.'" '.$checked.' />';
				}
				$acltable .= '</td>';
			}
			$acltable .= '</tr>';
		}

		$acltable .= '</tbody></table>';
		$openDiv = acymailing_radio($this->choice, "acl_$category", 'onclick="updateACLTable(\''.$category.'\',\''.$oneAction.'\');"', 'value', 'text', ($custom ? 'special' : 'all'));
		$openDiv .= '<input type="hidden" name="aclcat[]" value="'.$category.'"/><div id="div_acl_'.$category.'"'.($custom ? ' style="display:block"' : ' style="display:none"').'>';
		$return = $openDiv.$acltable.'</div>';

		return $return;
	}
}
