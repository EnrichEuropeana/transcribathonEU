<?php
defined('ABSPATH') or die('Restricted access');
?><?php
include ACYM_CONTROLLER.'lists.php';

class FrontlistsController extends ListsController
{
    public function __construct()
    {
        if (!acym_level(2)) {
            acym_redirect(acym_rootURI(), 'ACYM_ONLY_AVAILABLE_ENTERPRISE_VERSION', 'warning');
        }
        $this->authorizedFrontTasks = ['countNumberOfRecipients', 'ajaxCreateNewList'];
        $this->urlFrontMenu = 'index.php?option=com_acym&view=frontlists&layout=listing';
        parent::__construct();
    }

    protected function prepareListsListing(&$data)
    {
        $listsPerPage = $data['pagination']->getListLimit();
        $page = acym_getVar('int', 'lists_pagination_page', 1);
        $idCurrentUser = acym_currentUserId();

        if (empty($idCurrentUser)) return;

        $matchingLists = $this->getMatchingElementsFromData(
            [
                'search' => $data['search'],
                'tag' => $data['tag'],
                'ordering' => $data['ordering'],
                'ordering_sort_order' => $data['orderingSortOrder'],
                'elementsPerPage' => $listsPerPage,
                'offset' => ($page - 1) * $listsPerPage,
                'status' => $data['status'],
                'creator_id' => acym_currentUserId(),
            ],
            $data['status'],
            $page
        );
        $data['pagination']->setStatus($matchingLists['total'], $page, $listsPerPage);

        $data['lists'] = $matchingLists['elements'];
        $data['listNumberPerStatus'] = $matchingLists['status'];
    }

    protected function prepareToolbar(&$data)
    {
        $toolbarHelper = acym_get('helper.toolbar');
        $toolbarHelper->addSearchBar($data['search'], 'lists_search', 'ACYM_SEARCH');
        $toolbarHelper->addButton(acym_translation('ACYM_CREATE_NEW_LIST'), ['data-task' => 'settings'], '', true);

        $data['toolbar'] = $toolbarHelper;
    }

    protected function prepareWelcomeUnsubData(&$data)
    {
        $data['tmpls'] = [];
        if (empty($data['listInformation']->id)) return;

        $mailClass = acym_get('class.mail');

        foreach (['welcome' => 'welcome', 'unsubscribe' => 'unsub'] as $full => $short) {
            $mailId = acym_getVar('int', $short.'mailid', 0);
            if (empty($data['listInformation']->{$full.'_id'}) && !empty($mailId)) {
                $data['listInformation']->{$full.'_id'} = $mailId;
                $listInfoSave = clone $data['listInformation'];
                unset($listInfoSave->subscribers);
                if (!$this->currentClass->save($listInfoSave)) acym_enqueueMessage(acym_translation('ACYM_ERROR_SAVE_LIST'), 'error');
            }

            $returnLink = acym_completeLink('frontlists&task=settings&id='.$data['listInformation']->id.'&edition=1&'.$short.'mailid={mailid}');
            if (empty($data['listInformation']->{$full.'_id'})) {
                $data['tmpls'][$short.'TmplUrl'] = acym_completeLink('frontmails&task=edit&step=editEmail&type='.$full.'&type_editor=acyEditor&return='.urlencode(base64_encode($returnLink)));
            } else {
                $data['tmpls'][$short.'TmplUrl'] = acym_completeLink('frontmails&task=edit&id='.$data['listInformation']->{$full.'_id'}.'&type='.$full.'&return='.urlencode(base64_encode($returnLink)));
            }

            $data['tmpls'][$full] = !empty($data['listInformation']->{$full.'_id'}) ? $mailClass->getOneById($data['listInformation']->{$full.'_id'}) : '';
        }
    }
}

