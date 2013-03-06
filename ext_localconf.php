<?php

if (!defined ('TYPO3_MODE'))  die ('Access denied.');



  ////////////////////////////////////////////////////
  //
  // Index
  //
  // addPItoST43
  



  ////////////////////////////////////////////////////
  //
  // addPItoST43
  
  // #i0001, 130306, dwildt;
$cached = true;
t3lib_extMgm::addPItoST43( $_EXTKEY, 'pi1/class.tx_powermail4dev_pi1.php','_pi1', 'list_type', $cached );
  // addPItoST43
?>
