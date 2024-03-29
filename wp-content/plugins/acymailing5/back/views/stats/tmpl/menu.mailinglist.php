<?php
/**
 * @package	AcyMailing for WordPress
 * @version	5.10.12
 * @author	acyba.com
 * @copyright	(C) 2009-2020 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') or die('Restricted access');
?><fieldset class="acyheaderarea">
	<div class="acyheader icon-48-stats" style="float: left;"><?php echo $this->mailing->subject; ?></div>
	<div class="toolbar" id="toolbar" style="float: right;">
		<table>
			<tr>
				<td><a href="<?php echo acymailing_completeLink(acymailing_getVar('cmd', 'ctrl').'&task=mailinglist&export=1&mailid='.acymailing_getVar('int', 'mailid'), true); ?>"><span class="icon-32-acyexport" title="<?php echo acymailing_translation('ACY_EXPORT', true); ?>"></span><?php echo acymailing_translation('ACY_EXPORT'); ?></a></td>
				<td><a onclick="window.print(); return false;" href="#"><span class="icon-32-acyprint" title="<?php echo acymailing_translation('ACY_PRINT', true); ?>"></span><?php echo acymailing_translation('ACY_PRINT'); ?></a></td>
			</tr>
		</table>
	</div>
</fieldset>
