<?php
defined('ABSPATH') or die('Restricted access');
?><div class="grid-x grid-margin-x margin-top-3">

    <?php
    $campaignType = empty($data['campaign_type']) ? '' : $data['campaign_type'];
    if (empty($data['allMails']) && empty($data['search']) && empty($data['tag']) && empty($data['status'])) { ?>
		<div class="grid-x cell text-center">
			<h1 class="acym__listing__empty__title cell"><?php echo acym_translation('ACYM_YOU_DONT_HAVE_ANY_TEMPLATE'); ?></h1>
			<h1 class="acym__listing__empty__subtitle cell"><?php echo acym_translation('ACYM_CREATE_CAMPAIGN_EMPTY_TEMPLATE'); ?></h1>
			<div class="medium-4"></div>
			<div class="medium-4 small-12 cell">
				<a
						href="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl').'&task=edit&step=editEmail&from=-1&type_editor=acyEditor&id='.acym_escape($data['campaignID']).'&campaign_type='.$campaignType); ?>"
						class="button expanded"
						id="acym__templates__choose__create__empty">
                    <?php echo acym_translation('ACYM_CREATE_EMPTY_TEMPLATE'); ?>
				</a>
			</div>
			<div class="medium-4"></div>
		</div>
    <?php } else { ?>
		<div class="cell grid-x margin-bottom-2 grid-margin-x">
			<div class="medium-auto cell">
                <?php echo acym_filterSearch($data['search'], 'mailchoose_search', 'ACYM_SEARCH'); ?>
			</div>

			<div class="medium-auto cell">
                <?php
                $allTags = new stdClass();
                $allTags->name = acym_translation('ACYM_ALL_TAGS');
                $allTags->value = '';
                array_unshift($data['allTags'], $allTags);

                echo acym_select(
                    $data['allTags'],
                    'mailchoose_tag',
                    acym_escape($data["tag"]),
                    'class="acym__templates__filter__tags acym__select"',
                    'value',
                    'name'
                ); ?>
			</div>
			<div class="xxlarge-3 xlarge-2 large-1 hide-for-medium-only hide-for-small-only cell"></div>
			<div class="grid-x medium-shrink text-center cell acym__templates__choose__type-templates">
				<a
						href="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl').'&task=edit&step=editEmail&from=-1&type_editor=acyEditor&id='.intval($data['campaignID']).'&campaign_type='.$campaignType); ?>"
						class="button"
						id="acym__templates__choose__create__empty">
                    <?php echo acym_translation('ACYM_START_FROM_EMPTY_TEMPLATE'); ?>
				</a>
			</div>
		</div>
        <?php if (empty($data['allMails'])) { ?>
			<h1 class="cell acym__listing__empty__search__title text-center"><?php echo acym_translation('ACYM_NO_RESULTS_FOUND'); ?></h1>
        <?php } else { ?>
			<div class="grid-x grid-padding-x grid-padding-y grid-margin-x grid-margin-y xxlarge-up-6 large-up-4 medium-up-3 small-up-1 cell">
                <?php
                foreach ($data['allMails'] as $oneTemplate) {
                    ?>
					<div class="cell grid-x acym__templates__oneTpl acym__listing__block">
						<input type="hidden" class="acym__templates__oneTpl__choose" value="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl').'&task=edit&step=editEmail&from='.$oneTemplate->id.'&id='.intval($data['campaignID']).'&campaign_type='.$campaignType); ?>" />
						<div class="cell acym__templates__pic text-center">
							<img src="<?php echo acym_getMailThumbnail($oneTemplate->thumbnail); ?>" alt="<?php echo acym_escape($oneTemplate->name); ?>" />
                            <?php
                            echo '<div class="acym__templates__choose__ribbon '.($oneTemplate->drag_editor ? 'acyeditor' : 'htmleditor').'">'.($oneTemplate->drag_editor ? 'AcyEditor' : 'HTML Editor').'</div>';
                            ?>
						</div>
						<div class="cell grid-x acym__templates__footer text-center">
							<div class="cell acym__templates__footer__title" title="<?php echo acym_escape($oneTemplate->name); ?>">
                                <?php
                                if (strlen($oneTemplate->name) > 55) {
                                    $oneTemplate->name = substr($oneTemplate->name, 0, 50).'...';
                                }
                                echo acym_escape($oneTemplate->name);
                                ?>
							</div>
							<div class="cell"><?php echo acym_date($oneTemplate->creation_date, 'M. j, Y'); ?></div>
						</div>
					</div>
                <?php } ?>
			</div>
            <?php echo $data['pagination']->display('mailchoose'); ?>
        <?php } ?>
    <?php } ?>
</div>
<?php

