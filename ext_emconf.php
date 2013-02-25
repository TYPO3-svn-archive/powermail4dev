<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Powermail for Developers',
    'description' => '...',
    'category' => 'plugin',
    'shy' => 0,
    'version' => '0.0.1',
    'dependencies' => 'powermail',
    'conflicts' => '',
    'priority' => '',
    'loadOrder' => '',
    'module' => '',
    'state' => 'alpha',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearcacheonload' => 0,
    'lockType' => '',
    'author' => 'Dirk Wildt (Die Netzmacher)',
    'author_email' => 'http://wildt.at.die-netzmacher.de',
    'author_company' => '',
    'CGLcompliance' => '',
    'CGLcompliance_note' => '',
    'constraints' => array(
        'depends' => array(
            'powermail' => '',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
            'devlog' => '',
        ),
    ),
    'suggests' => array(
        '0' => 'devlog',
    ),
);

?>