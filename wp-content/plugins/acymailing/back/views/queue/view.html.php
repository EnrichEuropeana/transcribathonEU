<?php
defined('ABSPATH') or die('Restricted access');
?><?php

class QueueViewQueue extends acymView
{
    public function __construct()
    {
        parent::__construct();

        $this->steps = [
            'campaigns' => 'ACYM_MAILS',
            'detailed' => 'ACYM_QUEUE_DETAILED',
        ];
    }
}

