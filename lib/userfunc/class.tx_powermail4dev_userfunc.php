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
   * Flexform Sheet Powermail
   *
   **********************************************/

  /**
 * ffPowermailUid: 
 * Tab [General/sDEF]
 *
 * @param    array        $arr_pluginConf : Current plugin/flexform configuration
 * @return    array       $arrResult      : uid, ffConfirm
 * @access public
 * @version 0.0.1
 * @since 0.0.1
 */
  public function ffPowermailUid( $arr_pluginConf )
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
//          ffPowermailUid: row is empty<br />
//          Method: ' . __METHOD__ . '::' . __LINE__ . '<br />
//          TYPO3 extension: powermail4dev';
//        die( $prompt );
//    }
    
    $arrResult = $this->sqlPowermail( $row );
//    echo '<pre>' . var_dump( $arrResult ) . '</pre>'; 
    
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
    
    $prompt = 'This plugin handles the powermail form "' . $arrResult['title']. '" 
      (uid ' . $arrResult['uid']. '). Powermail mode confirm is ' . $pmFfConfirm . '.';
    
    return $arrResult;
  }
  
  
  
  /***********************************************
   *
   * Flexform Sheet SDEF
   *
   **********************************************/



  /**
 * ffSdefInfo: Get data query (and andWhere) for all list views of the current plugin.
 * Tab [General/sDEF]
 *
 * @param    array        $arr_pluginConf: Current plugin/flexform configuration
 * @return    array        with the names of the views list
 * @version 0.0.1
 * @since 0.0.1
 */
  public function ffSdefInfo( $arr_pluginConf )
  {
    $dummy = $arr_pluginConf;
    return $GLOBALS['LANG']->sL('LLL:EXT:powermail4dev/pi1/locallang_flexform.xml:sDEF.info') . '</h1>';
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
  * @return    integer     $powermailUid : uid of the powermail form
  * @access public
  * @version 0.0.1
  * @since 0.0.1
  */
  public function sqlPowermail( $row )
  {
    $arrReturn = null; 
    
    if( $this->pmUid )
    {
      $arrReturn['uid']       = $this->pmUid;
      $arrReturn['title']     = $this->pmTitle;
      $arrReturn['ffConfirm'] = $this->pmFfConfirm;
      return $arrReturn;
    }
    
      // Page uid
    $pid              = $row['pid'];
      // and where enable fields
    $andEnableFields  = $this->pObj->cObj->enableFields( 'tt_content' );
    
      // Query
    $select_fields  = '*';
    $from_table     = 'tt_content';
    $where_clause   = "pid = " . $pid . " AND list_type = 'powermail_pi1'" . $andEnableFields;
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
      
    $this->pmUid        = $pmRecord['uid'];  
    $this->pmTitle      = $pmRecord['header'];  
    $pmFlexform         = t3lib_div::xml2array( $pmRecord['pi_flexform'] );
    $this->pmFfConfirm  = $pmFlexform['data']['main']['lDEF']['settings.flexform.main.form']['vDEF'];
//    var_export( $pmFfConfirm );
//    var_export( $pmFlexform );

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