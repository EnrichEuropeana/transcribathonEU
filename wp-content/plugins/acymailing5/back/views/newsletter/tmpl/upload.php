<?php
/**
 * @package	AcyMailing for WordPress
 * @version	5.10.12
 * @author	acyba.com
 * @copyright	(C) 2009-2020 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') or die('Restricted access');
?><div id="acy_content">
	<form action="<?php echo acymailing_completeLink('file', true); ?>" method="post" name="adminForm" id="adminForm" autocomplete="off" enctype="multipart/form-data">
		<div id="iframedoc"></div>
		<div style="text-align:center;padding-top:20px;"><input type="file" style="width:auto" name="uploadedfile"/><br />
			<?php echo (acymailing_translation_sprintf('MAX_UPLOAD', (acymailing_bytes(ini_get('upload_max_filesize')) > acymailing_bytes(ini_get('post_max_size'))) ? ini_get('post_max_size') : ini_get('upload_max_filesize'))); ?>
		</div>
		<?php acymailing_formOptions(); ?>
	</form>
</div>
