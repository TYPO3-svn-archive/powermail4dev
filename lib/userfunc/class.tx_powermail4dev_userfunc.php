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
 * @param    array        $arr_pluginConf: Current plugin/flexform configuration
 * @return    array        with the names of the views list
 * @version 0.0.1
 * @since 0.0.1
 */
  public function ffPowermailUid( $arr_pluginConf )
  {
    echo '<pre>' .  var_dump( $arr_pluginConf ) . '</pre>';
    $prompt = $prompt . '<hr />';
    $prompt = $prompt . 2680;
    return $prompt;
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








}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail4dev/lib/class.tx_powermail4dev_userfunc.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail4dev/lib/class.tx_powermail4dev_userfunc.php']);
}

?>