<?php
return array(
    'name' => 'php-eval',
    'action' => Url::gear('dev_toolbar').'eval',
    'elements' => array(
        'body' => array(
            'type' => 'textarea',
            'label' => t('Code'),
        ),
        'submit' => array(
            'type' => 'submit',
            'label' => t('Eval PHP'),
        )
    ),
);