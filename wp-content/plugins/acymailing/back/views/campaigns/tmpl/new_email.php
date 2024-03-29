<?php
defined('ABSPATH') or die('Restricted access');
?><form id="acym_form" action="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl')); ?>" method="post" name="acyForm">
	<div id="acym__email__new" class="cell grid-x grid-margin-y align-center acym__content margin-top-2 acym__selection">
		<h1 class="margin-top-1 margin-bottom-2 acym__selection__title"><?php echo acym_translation('ACYM_WHICH_KIND_OF_MAIL_CREATE'); ?></h1>
		<div class="cell grid-x grid-margin-x align-center">
			<div class="acym__selection__card cell large-2 medium-4 text-center" acym-data-link="<?php echo $data['campaign_link']; ?>">
				<i class="acymicon-bullhorn acym__selection__card__icon"></i>
				<h1 class="acym__selection__card__title"><?php echo acym_translation('ACYM_CLASSIC_CAMPAIGN'); ?></h1>
				<p class="acym__selection__card__description"><?php echo acym_translation('ACYM_CLASSIC_CAMPAIGN_DESC'); ?></p>
			</div>
			<div class="acym__selection__card acym__selection__select-card cell large-2 medium-4 text-center" acym-data-link="<?php echo $data['welcome_email_link']; ?>">
				<i class="acymicon-handshake-o acym__selection__card__icon"></i>
				<h1 class="acym__selection__card__title"><?php echo acym_translation('ACYM_WELCOME_EMAIL'); ?></h1>
				<p class="acym__selection__card__description"><?php echo acym_translation('ACYM_WELCOME_EMAIL_DESC'); ?></p>
                <?php echo acym_select(
                    $data['lists'],
                    'welcome_list_id',
                    null,
                    ['class' => 'acym__email__new__card__select']
                ); ?>
			</div>
			<div class="acym__selection__card acym__selection__select-card cell large-2 medium-4 text-center" acym-data-link="<?php echo $data['unsubscribe_email_link']; ?>">
				<i class="acymicon-hand-paper-o acym__selection__card__icon"></i>
				<h1 class="acym__selection__card__title"><?php echo acym_translation('ACYM_UNSUBSCRIBE_EMAIL'); ?></h1>
				<p class="acym__selection__card__description"><?php echo acym_translation('ACYM_UNSUBSCRIBE_EMAIL_DESC'); ?></p>
                <?php echo acym_select(
                    $data['lists'],
                    'unsubscribe_list_id',
                    null,
                    ['class' => 'acym__email__new__card__select']
                ); ?>
			</div>
		</div>
		<div class="cell grid-x grid-margin-x align-center">
			<div class="acym__selection__card <?php echo !acym_level(2) ? 'acym__selection__card__disabled' : ''; ?> cell large-2 medium-4 text-center" acym-data-link="<?php echo $data['campaign_auto_link']; ?>">
				<i class="acymicon-cog acym__selection__card__icon"></i>
				<h1 class="acym__selection__card__title"><?php echo acym_translation('ACYM_AUTOMATIC_CAMPAIGN'); ?></h1>
				<p class="acym__selection__card__description"><?php echo acym_translation('ACYM_AUTOMATIC_CAMPAIGN_DESC'); ?></p>
                <?php if (!acym_level(2)) echo '<div class="acym__selection__card__disabled__container cell">'.acym_translation('ACYM_ONLY_AVAILABLE_ENTERPRISE_VERSION').'</div>'; ?>
			</div>
			<div class="acym__selection__card <?php echo !acym_level(1) ? 'acym__selection__card__disabled' : ''; ?> cell large-2 medium-4 text-center" acym-data-link="<?php echo $data['campaign_scheduled_link']; ?>">
				<i class="acymicon-access_time acym__selection__card__icon"></i>
				<h1 class="acym__selection__card__title"><?php echo acym_translation('ACYM_SCHEDULED_CAMPAIGN'); ?></h1>
				<p class="acym__selection__card__description"><?php echo acym_translation('ACYM_SCHEDULED_CAMPAIGN_DESC'); ?></p>
                <?php if (!acym_level(1)) echo '<div class="acym__selection__card__disabled__container cell">'.acym_translation('ACYM_ONLY_AVAILABLE_ESSENTIAL_VERSION').'</div>'; ?>
			</div>
		</div>
		<button type="button" class="cell shrink button" id="acym__selection__button-select" disabled><?php echo acym_translation('ACYM_CREATE'); ?></button>
	</div>
    <?php acym_formOptions(); ?>
</form>

