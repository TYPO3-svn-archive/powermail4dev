<?php

if (!defined ('TYPO3_MODE'))  die ('Access denied.');



    ///////////////////////////////////////////////////////////
    //
    // INDEX

    // tt_content
    // Plugin 1 configuration
    // Add pagetree icons
    // Enables the Include Static Templates



    ///////////////////////////////////////////////////////////
    //
    // tt_content

  t3lib_div::loadTCA('tt_content');
    // tt_content



    ///////////////////////////////////////////////////////////
    //
    // Plugin 1 configuration

  $TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages,recursive';
    // Remove the default tt_content fields layout, select_key, pages and recursive.
  $TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';
    // Display the field pi_flexform
  t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:'.$_EXTKEY.'/pi1/flexform.xml');
    // Register our file with the flexform structure
  t3lib_extMgm::addPlugin(array('LLL:EXT:powermail4dev/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1', 'EXT:powermail4dev/ext_icon.gif'),'list_type');
    // Add the Flexform to the Plugin List
    // Plugin 1 configuration



    ////////////////////////////////////////////////////////////////////////////
    //
    // Add pagetree icons

  $TCA['pages']['columns']['module']['config']['items'][] =
     array('Powermail for Developers', 'pm4dev', t3lib_extMgm::extRelPath($_EXTKEY).'ext_icon.gif');
  t3lib_SpriteManager::addTcaTypeIcon('pages', 'contains-pm4dev', '../typo3conf/ext/powermail4dev/ext_icon.gif');
    // Add pagetree icons



    ////////////////////////////////////////////////////////////////////////////
    //
    // Enables the Include Static Templates

  t3lib_extMgm::addStaticFile($_EXTKEY,'static/pi1/', 'Powermail for Developers');
    // Enables the Include Static Templates



?>