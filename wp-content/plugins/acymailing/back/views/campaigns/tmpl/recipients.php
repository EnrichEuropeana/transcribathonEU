<?php
defined('ABSPATH') or die('Restricted access');
?><form id="acym_form" action="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl').'&task='.acym_getVar('string', 'task').'&id='.acym_getVar('string', 'id')); ?>" method="post" name="acyForm" class="acym__form__campaign__edit">
	<input type="hidden" value="<?php echo !empty($data['campaignInformation']) ? acym_escape($data['campaignInformation']) : ''; ?>" name="id" id="acym__campaign__recipients__form__campaign">
	<input type="hidden" value="<?php echo !empty($data['showSelected']) ? $data['showSelected'] : ''; ?>" name="showSelected" id="acym__campaign__recipients__show-all-or-selected">
	<div id="acym__campaigns__recipients" class="grid-x">
		<div class="cell <?php echo $data['containerClass']; ?> float-center grid-x acym__content">
            <?php
            $workflow = acym_get('helper.workflow');
            echo $workflow->display($this->steps, $this->step);

            ?>
			<div class="acym__campaigns__recipients__modal">
                <?php if (!empty($data['currentCampaign']->sent && empty($data['currentCampaign']->active))) { ?>
					<div class="acym__hide__div"></div>
					<h3 class="acym__title__primary__color acym__middle_absolute__text text-center"><?php echo acym_translation('ACYM_CAMPAIGN_ALREADY_QUEUED'); ?></h3>
                <?php }
                $entityHelper = acym_get('helper.entitySelect');

                echo $entityHelper->entitySelect('list', ['join' => 'join_mail-'.$data['currentCampaign']->mail_id], $entityHelper->getColumnsForList('maillist.mail_id'));
                ?>
				<div class="cell grid-x acym__campaign__recipients__total-recipients acym__content acym_vcenter">
					<p class="cell medium-8"><?php echo acym_translation('ACYM_CAMPAIGN_SENT_TO'); ?></p>
					<div class="medium-4 acym__campaign__recipients__number-display cell grid-x align-right acym_vcenter">
                        <?php echo acym_loaderLogo(); ?>
						<div class="cell shrink margin-left-1"><span class="acym__campaign__recipients__number-recipients">0</span> <?php echo strtolower(acym_translation('ACYM_RECIPIENTS')); ?></div>
					</div>
				</div>
			</div>
			<div class="cell grid-x text-center acym__campaign__recipients__save-button cell">
				<div class="cell medium-shrink medium-margin-bottom-0 margin-bottom-1 text-left">
                    <?php echo acym_backToListing(); ?>
				</div>
				<div class="cell medium-auto grid-x text-right">
					<div class="cell medium-auto"></div>
                    <?php if (empty($data['campaignInformation'])) { ?>
						<button data-task="save" data-step="sendSettings" type="submit" class="cell medium-shrink button margin-bottom-0 acy_button_submit">
                            <?php echo acym_translation('ACYM_SAVE_CONTINUE'); ?><i class="acymicon-chevron-right"></i>
						</button>
                    <?php } else { ?>
						<button data-task="save" data-step="listing" type="submit" class="cell button-secondary medium-shrink button medium-margin-bottom-0 margin-right-1 acy_button_submit">
                            <?php echo acym_translation('ACYM_SAVE_EXIT'); ?>
						</button>
						<button data-task="save" data-step="sendSettings" type="submit" class="cell medium-shrink button margin-bottom-0 acy_button_submit" id="acym__campaign__recipients__save-continue">
                            <?php echo acym_translation('ACYM_SAVE_CONTINUE'); ?><i class="acymicon-chevron-right"></i>
						</button>
                    <?php } ?>
				</div>
			</div>
		</div>
	</div>
    <?php acym_formOptions(true, 'edit', 'recipients'); ?>
</form>

