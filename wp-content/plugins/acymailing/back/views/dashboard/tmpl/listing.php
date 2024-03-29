<?php
defined('ABSPATH') or die('Restricted access');
?><div id="acym__dashboard">
	<div class="acym__dashboard__card cell grid-x large-up-3 grid-margin-x grid-margin-y medium-up-2 small-up-1 margin-right-0 margin-bottom-2">
		<div class="cell acym__content acym__dashboard__one-card text-center grid-x">
			<div class="cell acym__dashboard__card__picto__audience acym__dashboard__card__picto"><i class="acymicon-insert_chart acym__dashboard__card__icon__audience"></i></div>
			<h1 class="cell acym__dashboard__card__title"><?php echo acym_translation('ACYM_AUDIENCE'); ?></h1>
			<hr class="cell small-10">
			<a class="acym__dashboard__card__link" href="<?php echo acym_completeLink('lists'); ?>"><?php echo acym_translation('ACYM_VIEW_ALL_LISTS'); ?></a>
			<a class="acym__dashboard__card__link" href="<?php echo acym_completeLink('lists&task=edit&step=settings'); ?>"><?php echo acym_translation('ACYM_CREATE_LIST'); ?></a>
			<a class="acym__dashboard__card__link" href="#"><?php echo acym_tooltip(acym_translation('ACYM_CREATE_SEGMENT'), '<span class="acy_coming_soon"><i class="acymicon-new_releases acy_coming_soon_icon"></i>'.acym_translation('ACYM_COMING_SOON').'</span>', 'acym__dashboard__card__link__unclickable'); ?></a>
		</div>
		<div class="cell acym__content acym__dashboard__one-card text-center grid-x">
			<div class="acym__dashboard__card__picto__campaigns acym__dashboard__card__picto"><i class="acymicon-email acym__dashboard__card__icon__campaigns"></i></div>
			<h1 class="acym__dashboard__card__title"><?php echo acym_translation('ACYM_EMAILS'); ?></h1>
			<hr class="cell small-10">
			<a class="acym__dashboard__card__link" href="<?php echo acym_completeLink('campaigns'); ?>"><?php echo acym_translation('ACYM_VIEW_ALL_EMAILS'); ?></a>
			<a class="acym__dashboard__card__link" href="<?php echo acym_completeLink('campaigns&task=edit&step=chooseTemplate'); ?>"><?php echo acym_translation('ACYM_CREATE_NEW_EMAIL'); ?></a>
			<a class="acym__dashboard__card__link" href="<?php echo acym_completeLink('mails&task=edit&type_editor=acyEditor'); ?>"><?php echo acym_translation('ACYM_CREATE_TEMPLATE'); ?></a>
		</div>
		<div class="cell acym__content acym__dashboard__one-card text-center grid-x">
			<div class="acym__dashboard__card__picto__automation acym__dashboard__card__picto"><i class="acymicon-cog acym__dashboard__card__icon__automation"></i></div>
			<h1 class="acym__dashboard__card__title"><?php echo acym_translation('ACYM_AUTOAMTION'); ?></h1>
			<hr class="cell small-10">
			<a class="acym__dashboard__card__link" href="<?php echo acym_completeLink('automation&task=listing'); ?>"><?php echo acym_translation('ACYM_VIEW_ALL_AUTOMATIONS'); ?></a>
			<a class="acym__dashboard__card__link" href="<?php echo acym_completeLink('automation&task=edit&step=info'); ?>"><?php echo acym_translation('ACYM_NEW_AUTOMATION'); ?></a>
			<a class="acym__dashboard__card__link" href="<?php echo acym_completeLink('automation&task=edit&step=filter'); ?>"><?php echo acym_translation('ACYM_NEW_MASS_ACTION'); ?></a>
		</div>
	</div>

	<div id="acym_stats">
        <?php include ACYM_VIEW.'stats'.DS.'tmpl'.DS.'global_stats.php'; ?>
	</div>

	<div class="cell acym__dashboard__active-campaigns acym__content">
		<h1 class="acym__dashboard__active-campaigns__title"><?php echo acym_translation('ACYM_CAMPAIGNS_SCHEDULED'); ?></h1>
		<div class="acym__dashboard__active-campaigns__listing">
            <?php if (empty($data['campaignsScheduled'])) { ?>
				<h1 class="acym__dashboard__active-campaigns__none"><?php echo acym_translation('ACYM_NONE_OF_YOUR_CAMPAIGN_SCHEDULED_GO_SCHEDULE_ONE'); ?></h1>
            <?php } else { ?>
                <?php
                $nbCampaigns = count($data['campaignsScheduled']);
                $i = 0;
                foreach ($data['campaignsScheduled'] as $campaign) {
                    $i++;
                    ?>
					<div class="cell grid-x acym__dashboard__active-campaigns__one-campaign">
						<a class="acym__dashboard__active-campaigns__one-campaign__title medium-4 small-12" href="<?php echo acym_completeLink('campaigns&task=edit&step=editEmail&id=').$campaign->id; ?>"><?php echo $campaign->name; ?></a>
						<div class="acym__dashboard__active-campaigns__one-campaign__state medium-2 small-12 acym__background-color__blue text-center"><span><?php echo acym_translation('ACYM_SCHEDULED').' : '.acym_getDate($campaign->sending_date, 'M. j, Y'); ?></span></div>
						<p id="<?php echo intval($campaign->id); ?>" class="medium-6 small-12 acym__dashboard__active-campaigns__one-campaign__action acym__color__dark-gray"><?php echo acym_translation('ACYM_CANCEL_SCHEDULING'); ?></p>
					</div>
                    <?php if ($i < $nbCampaigns) { ?>
						<hr class="cell small-12">
                    <?php }
                }
            } ?>
		</div>
	</div>
</div>

