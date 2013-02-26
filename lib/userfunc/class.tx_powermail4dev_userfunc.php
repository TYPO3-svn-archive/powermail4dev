<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* Class provides methods for the extension manager.
*
* @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
* @package    TYPO3
* @subpackage    powermail4dev
* @version  0.0.1
* @since    0.0.1
*/

  /**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   49: class tx_powermail4dev_userfunc
 *   67:     function promptCheckUpdate()
 *  102:     function promptCurrIP()
 *
 * TOTAL FUNCTIONS: 2
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_powermail4dev_userfunc
{
  
 /**
  * Configuration by the extension manager
  *
  * @var array
  */
  private $arr_extConf;
  
 /**
  * Current IP is met allowed IPs
  *
  * @var boolean
  */
  private $bool_accessByIP;

 /**
  * The parent object
  *
  * @var object
  */
  public $pObj;
  
 /**
  * The version of the current powermail extension
  *
  * @var integer
  */
  public $intVersion = null;
  
 /**
  * The version of the current powermail extension
  *
  * @var string
  */
  public $strVersion = null;
  
 /**
  * Uid of the current powermail plugin
  *
  * @var integer
  */
  private $pmUid = null;
  
 /**
  * Title of the current powermail plugin
  *
  * @var string
  */
  private $pmTitle = null;
  
 /**
  * Confirm mode of the current powermail plugin
  *
  * @var boolean
  */
  private $pmFfConfirm = null;
  



  /**
   * confCheckerDrs(  ):  ...
   *
   * @return    string        message wrapped in HTML
   * @version 0.1.0
   * @since   0.0.1
   */
  private function confCheckerDrs( $prompt )
  {

    switch( true )
    {
      case( $this->arr_extConf['drs_mode'] != 'Don\'t log anything' ):
        $prompt = $prompt . '
          <div class="typo3-message message-warning">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:powermail4dev/lib/locallang.xml:promptDrsWarn') . '
            </div>
          </div>';
        break;
      default:
        $prompt = $prompt . '
          <div class="typo3-message message-information">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:powermail4dev/lib/locallang.xml:promptDrsOk') . '
            </div>
          </div>';
        break;
    }
      // Debugging tip
    

    return $prompt;
  }



  /**
   * confCheckerCurrentIP(  ):  ...
   *
   * @return    string        message wrapped in HTML
   * @version 0.1.0
   * @since   0.0.1
   */
  private function confCheckerCurrentIP( $prompt )
  {
    switch( $this->bool_accessByIP )
    {
      case( false ):            
        $prompt = $prompt . '
          <div class="typo3-message message-error">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:powermail4dev/lib/locallang.xml:promptAccessByIpError') . '
            </div>
          </div>';
        break;
      default:
        $prompt = $prompt . '
          <div class="typo3-message message-ok">
            <div class="message-body">
              ' . $GLOBALS['LANG']->sL('LLL:EXT:powermail4dev/lib/locallang.xml:promptAccessByIpOk') . '
            </div>
          </div>';
        break;
    }

    return $prompt;
  }



/**
 * init_accessByIP( ):  Set the global $bool_accessByIP.
 *
 * @return    void
 * @version 0.1.0
 * @since   0.1.0
 */
  private function init_accessByIP( )
  {
      // No access by default
    $this->bool_accessByIP = false;

      // Get list with allowed IPs
    $csvIP      = $this->arr_extConf['allowedIPs'];
    $currentIP  = t3lib_div :: getIndpEnv( 'REMOTE_ADDR' );

      // Current IP is an element in the list
    $pos = strpos( $csvIP, $currentIP );
    if( ! ( $pos === false ) )
    {
      $this->bool_accessByIP = true;
    }
//var_dump( __METHOD__, __LINE__, $csvIP, $currentIP, $this->bool_accessByIP );    
      // Current IP is an element in the list
  }



  /**
   * promptCurrIP( ): Displays the IP of the current backend user
   *
   * @return    string        message wrapped in HTML
   * @version 0.0.1
   * @since   0.0.1
   */
  function promptCurrIP( )
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

      $prompt = null;

      $prompt = $prompt.'
<div class="typo3-message message-information">
  <div class="message-body">
    ' . $GLOBALS['LANG']->sL('LLL:EXT:powermail4dev/lib/locallang.xml:promptCurrIPBody') . ': ' . t3lib_div :: getIndpEnv('REMOTE_ADDR') . '
  </div>
</div>';

    return $prompt;
  }



  /**
   * promptExternalLinks(): Displays the quick start message.
   *
   * @return  string    message wrapped in HTML
   * @version 0.0.1
   * @since   0.0.1
   */
  function promptExternalLinks()
  {
//.message-notice
//.message-information
//.message-ok
//.message-warning
//.message-error

      $prompt = null;

      $prompt = $prompt.'
<div class="message-body">
  ' . $GLOBALS['LANG']->sL('LLL:EXT:powermail4dev/lib/locallang.xml:promptExternalLinksBody'). '
</div>';

    return $prompt;
  }
  
  /***********************************************
   *
   * Extension Management
   *
   **********************************************/

 /**
  * extMgmVersion: 
  *
  * @param    string        $_EXTKEY  : extension key
  * @return    integer      $version  : version of the given extension
  * @access public
  * @version 0.0.1
  * @since 0.0.1
  */
  public function extMgmVersion( $_EXTKEY )
  {
    $arrReturn = null;
    
    if( ! ( $this->intVersion === null ) )
    {
      $arrReturn['int'] = $this->intVersion;
      $arrReturn['str'] = $this->strVersion;
      return $arrReturn;
    }
    
    if( ! t3lib_extMgm::isLoaded( $_EXTKEY ) )
    {
      $this->intVersion = 0;
      $this->strVersion = 0;
      $arrReturn['int'] = $this->intVersion;
      $arrReturn['str'] = $this->strVersion;
      return $arrReturn;
    }

      // Do not use require_once!
    require( t3lib_extMgm::extPath( $_EXTKEY ) . 'ext_emconf.php');
    $this->strVersion = $EM_CONF[$_EXTKEY]['version'];

      // Set version as integer (sample: 4.7.7 -> 4007007)
    list( $main, $sub, $bugfix ) = explode( '.', $this->strVersion );
    $intVersion = ( ( int ) $main ) * 1000000;
    $intVersion = $intVersion + ( ( int ) $sub ) * 1000;
    $intVersion = $intVersion + ( ( int ) $bugfix ) * 1;
      // Set version as integer (sample: 4.7.7 -> 4007007)
    
    $this->intVersion = $intVersion;
    $arrReturn['int'] = $this->intVersion;
    $arrReturn['str'] = $this->strVersion;
    return $arrReturn;
  }
  
  
  
  /***********************************************
   *
   * Flexform
   *
   **********************************************/

  /**
 * ffPowermailForm: 
 * Tab [General/sDEF]
 *
 * @param    array        $arr_pluginConf : Current plugin/flexform configuration
 * @return    array       $arrResult      : uid, ffConfirm
 * @access public
 * @version 0.0.1
 * @since 0.0.1
 */
  public function ffPowermailForm( $arr_pluginConf )
  {
    $prompt = null;
//    $pObj   = $this->pObj;
    
//    switch( true )
//    {
//      case( $arr_pluginConf['row'] ):
        $row = $arr_pluginConf['row'];
//        break;
//      case( $pObj->cObj->data ):
//        $row = $pObj->cObj->data;
//        break;
//      default:
//        $prompt = 'ERROR: unexpected result<br />
//          ffPowermailForm: row is empty<br />
//          Method: ' . __METHOD__ . '::' . __LINE__ . '<br />
//          TYPO3 extension: powermail4dev';
//        die( $prompt );
//    }
        
    if( ! t3lib_extMgm::isLoaded( 'powermail' ) )
    {
      $prompt = 'Sorry, but powermail isn\'t loaded!';
      return $prompt;
    }
    $arrVersion = $this->extMgmVersion( 'powermail' );
    
    $arrResult = $this->sqlPowermail( $row );
//    echo '<pre>' . var_dump( $arrResult ) . '</pre>'; 
    
      // RETURN : no powermail plugin
    if( ! $arrResult['uid'] )
    {
      $prompt = 'There isn\'t any powermail plugin at the current page!';
      $prompt = $prompt . '<br />
        Please add a powermail plugin, if you want to work with Powermail for developers.';
      return $prompt;
    }
    
    switch( $arrResult['ffConfirm'] )
    {
      case( true ):
        $pmFfConfirm = 'enabled';
        break;
      case( false ):
      default:
        $pmFfConfirm = 'disabled';
        break;
    }
      // RETURN : no powermail plugin
    

    $prompt = 'This plugin handles the powermail form with the title "' . $arrResult['title']. '" 
      and the uid #' . $arrResult['uid']. '.';
    $prompt = $prompt . '<br />
      The powermail plugin has the confirm mode ' . $pmFfConfirm . '.';
    $prompt = $prompt . '<br />
      Powermail version: ' . $arrVersion['str'] . ' (internal ' . $arrVersion['int'] . ')';
    $prompt = $prompt . '<br /><br />
      <span style="color:red;font-weight:bold;">
        BE AWARE:
      </span>
      If you have more than one powermail form within the same page, you can get 
      unproper results. Even if all other powermail forms are hidden or if they have a deleted status.
      ';
    
    return $prompt;
  }
  
  
  
  /***********************************************
   *
   * SQL
   *
   **********************************************/

 /**
  * sqlPowermail: 
  * Tab [General/sDEF]
  *
  * @param    array        $row          : current row
  * @return    integer     $powermailUid : uid, title, ffConfirm of the powermail form
  * @access public
  * @version 0.0.1
  * @since 0.0.1
  */
  public function sqlPowermail( $row )
  {
    $arrReturn = null; 
    
    if( ! ( $this->pmUid === null ) )
    {
      $arrReturn['uid']       = $this->pmUid;
      $arrReturn['title']     = $this->pmTitle;
      $arrReturn['ffConfirm'] = $this->pmFfConfirm;
      return $arrReturn;
    }
    
    if( ! t3lib_extMgm::isLoaded( 'powermail' ) )
    {
      $prompt = 'Sorry, but powermail isn\'t loaded!';
      return $prompt;
    }
    $this->extMgmVersion( 'powermail' );

      // Page uid
    $pid              = $row['pid'];
    
    if( ! $pid )
    {
      $prompt = 'ERROR: unexpected result<br />
        pid is empty<br />
        Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
        TYPO3 extension: powermail4dev';
      die( $prompt );
    }

      // Query
    $select_fields  = '*';
    $from_table     = 'tt_content';
    $where_clause   = "pid = " . $pid . " AND hidden = 0 AND deleted = 0";
    switch( true )
    {
      case( $this->intVersion < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0: ' . $this->intVersion . '<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: powermail4dev';
        die( $prompt );
        break;
      case( $this->intVersion < 2000000 ):
        $where_clause = $where_clause . " AND CType = 'powermail_pi1'";
        break;
      case( $this->intVersion < 3000000 ):
      default:
        $where_clause = $where_clause . " AND list_type = 'powermail_pi1'";
        break;
    }
    $groupBy        = '';
    $orderBy        = 'sorting';
    $limit          = '1';
      // Query

      // DRS
    if( $this->pObj->b_drs_sql )
    {
      $query  = $GLOBALS['TYPO3_DB']->SELECTquery
                (
                  $select_fields,
                  $from_table,
                  $where_clause,
                  $groupBy,
                  $orderBy,
                  $limit
                );
      $prompt = $query;
      t3lib_div::devlog(' [INFO/SQL] '. $prompt, $this->pObj->extKey, 0 );
    }
      // DRS
      
      // Execute SELECT
    $res =  $GLOBALS['TYPO3_DB']->exec_SELECTquery
            (
              $select_fields,
              $from_table,
              $where_clause,
              $groupBy,
              $orderBy,
              $limit
            );
      // Execute SELECT

      // Current powermail record
    $pmRecord =  $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );

      // RETURN : no row
    if( empty( $pmRecord ) )
    {
      if( $this->pObj->b_drs_error )
      {
        $prompt = 'Abort. SQL query is empty!';
        t3lib_div::devlog(' [WARN/SQL] '. $prompt, $this->pObj->extKey, 2 );
      }
      return false;
    }
      // RETURN : no row
      
    $this->pmUid    = $pmRecord['uid'];  
    $this->pmTitle  = $pmRecord['header'];  
    switch( true )
    {
      case( $this->intVersion < 1000000 ):
        $prompt = 'ERROR: unexpected result<br />
          powermail version is below 1.0.0<br />
          Method: ' . __METHOD__ . ' (line ' . __LINE__ . ')<br />
          TYPO3 extension: powermail4dev';
        die( $prompt );
        break;
      case( $this->intVersion < 2000000 ):
        $this->pmFfConfirm  = $pmRecord['tx_powermail_confirm'];
        break;
      case( $this->intVersion < 3000000 ):
      default:
        $pmFlexform         = t3lib_div::xml2array( $pmRecord['pi_flexform'] );
        $this->pmFfConfirm  = $pmFlexform['data']['main']['lDEF']['settings.flexform.main.form']['vDEF'];
        break;
    }

    $arrReturn['uid']       = $this->pmUid;
    $arrReturn['title']     = $this->pmTitle;
    $arrReturn['ffConfirm'] = $this->pmFfConfirm;

    return $arrReturn;
  }








}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail4dev/lib/class.tx_powermail4dev_userfunc.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail4dev/lib/class.tx_powermail4dev_userfunc.php']);
}

?>