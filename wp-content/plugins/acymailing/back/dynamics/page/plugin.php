<?php
defined('ABSPATH') or die('Restricted access');
?><?php

class plgAcymPage extends acymPlugin
{
    public function __construct()
    {
        parent::__construct();
        $this->cms = 'WordPress';

        $this->pluginDescription->name = acym_translation('ACYM_PAGE');
        $this->pluginDescription->icon = '<div class="wp-menu-image dashicons-before dashicons-admin-page"></div>';
        $this->pluginDescription->icontype = 'raw';
    }

    public function getPossibleIntegrations()
    {
        return $this->pluginDescription;
    }

    public function insertionOptions($defaultValues = null)
    {
        $this->defaultValues = $defaultValues;

        $displayOptions = [
            [
                'title' => 'ACYM_DISPLAY',
                'type' => 'checkbox',
                'name' => 'display',
                'options' => [
                    'title' => ['ACYM_TITLE', true],
                    'image' => ['ACYM_FEATURED_IMAGE', true],
                    'content' => ['ACYM_CONTENT', true],
                ],
            ],
            [
                'title' => 'ACYM_CLICKABLE_TITLE',
                'type' => 'boolean',
                'name' => 'clickable',
                'default' => true,
            ],
            [
                'title' => 'ACYM_TRUNCATE',
                'type' => 'intextfield',
                'isNumber' => 1,
                'name' => 'wrap',
                'text' => 'ACYM_TRUNCATE_AFTER',
                'default' => 0,
            ],
            [
                'title' => 'ACYM_DISPLAY_PICTURES',
                'type' => 'pictures',
                'name' => 'pictures',
            ],
        ];

        $zoneContent = $this->getFilteringZone(false).$this->prepareListing();
        echo $this->displaySelectionZone($zoneContent);
        echo $this->pluginHelper->displayOptions($displayOptions, $this->name, 'individual', $this->defaultValues);
    }

    public function prepareListing()
    {
        $this->querySelect = 'SELECT page.ID, page.post_title, page.post_date, page.post_content ';
        $this->query = 'FROM #__posts AS page ';
        $this->filters = [];
        $this->filters[] = 'page.post_type = "page"';
        $this->filters[] = 'page.post_status = "publish"';
        $this->searchFields = ['page.ID', 'page.post_title'];
        $this->pageInfo->order = 'page.ID';
        $this->elementIdTable = 'page';
        $this->elementIdColumn = 'ID';

        parent::prepareListing();

        $rows = $this->getElements();
        foreach ($rows as $i => $row) {
            if (str_replace(['wp:core-embed', 'wp:shortcode'], '', $row->post_content) !== $row->post_content) {
                $rows[$i]->post_title = acym_tooltip('<i class="acymicon-exclamation-triangle"></i>', acym_translation('ACYM_SPECIAL_CONTENT_WARNING')).$rows[$i]->post_title;
            }
        }

        $listingOptions = [
            'header' => [
                'post_title' => [
                    'label' => 'ACYM_TITLE',
                    'size' => '7',
                ],
                'post_date' => [
                    'label' => 'ACYM_DATE_CREATED',
                    'size' => '4',
                    'type' => 'date',
                ],
                'ID' => [
                    'label' => 'ACYM_ID',
                    'size' => '1',
                    'class' => 'text-center',
                ],
            ],
            'id' => 'ID',
            'rows' => $rows,
        ];

        return $this->getElementsListing($listingOptions);
    }

    public function replaceContent(&$email)
    {
        $this->replaceOne($email);
    }

    public function replaceIndividualContent($tag)
    {
        $query = 'SELECT page.*
                    FROM #__posts AS page
                    WHERE page.post_type = "page" 
                        AND page.post_status = "publish"
                        AND page.ID = '.intval($tag->id);

        $element = $this->initIndividualContent($tag, $query);

        if (empty($element)) return '';

        $varFields = $this->getCustomLayoutVars($element);

        $link = get_permalink($element->ID);
        $varFields['{link}'] = $link;

        $title = '';
        if (in_array('title', $tag->display)) $title = $element->post_title;

        $afterTitle = '';

        $imagePath = '';
        if (in_array('image', $tag->display)) {
            $imageId = get_post_thumbnail_id($tag->id);
            if (!empty($imageId)) {
                $imagePath = get_the_post_thumbnail_url($tag->id);
            }
        }

        $contentText = '';
        if (in_array('content', $tag->display)) $contentText .= $element->post_content;

        $customFields = [];

        $format = new stdClass();
        $format->tag = $tag;
        $format->title = $title;
        $format->afterTitle = $afterTitle;
        $format->afterArticle = '';
        $format->imagePath = $imagePath;
        $format->description = $contentText;
        $format->link = empty($tag->clickable) ? '' : $link;
        $format->customFields = $customFields;
        $result = '<div class="acymailing_content">'.$this->pluginHelper->getStandardDisplay($format).'</div>';

        return $this->finalizeElementFormat($result, $tag, $varFields);
    }
}

