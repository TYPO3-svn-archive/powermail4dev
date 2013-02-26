<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
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



require_once( PATH_tslib . 'class.tslib_pibase.php' );
require_once( t3lib_extMgm::extPath( 'powermail4dev' ) . 'lib/userfunc/class.tx_powermail4dev_userfunc.php'); // file for div functions

/**
 * Plugin 'Login' for the 'powermail4dev' extension.
 *
 * @author    Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage  powermail4dev
 *
 * @version 0.0.1
 * @since 0.0.1
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   68: class tx_powermail4dev_pi1 extends tslib_pibase
 *
 *              SECTION: Main Process
 *  142:     public function main( $content, $conf )
 *
 *              SECTION: DRS - Development Reporting System
 *  203:     private function initAccessByIp( )
 *  232:     private function initDrs( )
 *
 *              SECTION: Flexform
 *  316:     private function initFlexform( )
 *  335:     private function initFlexformSheetSdef( )
 *
 *              SECTION: SOAP
 *  407:     private function soapUpdate( $content )
 *
 *              SECTION: Session
 *  537:     private function sessionSetForPowermail( $content )
 *
 * TOTAL FUNCTIONS: 7
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */
class tx_powermail4dev_pi1 extends tslib_pibase
{

 /**
  * Class name
  *
  * @var string
  */
  public $prefixId  = 'tx_powermail4dev_pi1';

 /**
0  * Path to this script relative to the extension directory
  *
  * @var string
  */
  public $scriptRelPath  = 'pi1/class.tx_powermail4dev_pi1.php';

 /**
  * Extension key
  *
  * @var string
  */
  public $extKey = 'powermail4dev';

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
  * Current TypoScript configuration
  *
  * @var array
  */
  public $conf;

 /**
  * Flexform value config.csvAllowedIp
  *
  * @var string
  */
  private $ffConfigIp;

 /**
  * Flexform value prompts.gpvar
  *
  * @var boolean
  */
  private $ffPromptsGpvar;

 /**
  * Flexform value prompts.session
  *
  * @var boolean
  */
  private $ffPromptsSession;


 /**
  * Flexform value powermail.confirm
  *
  * @var boolean
  */
  private $pmConfirm;

 /**
  * Title of the powermail plugin
  *
  * @var string
  */
  private $pmTitle;

 /**
  * tt_content uid of powermail record / flexform
  *
  * @var integer
  */
  private $pmUid;

 /**
  * The version of the current powermail extension
  *
  * @var integer
  */
  public $pmIntVersion = null;
  
 /**
  * The version of the current powermail extension
  *
  * @var string
  */
  public $pmStrVersion = null;






  /***********************************************
   *
   * Main Process
   *
   **********************************************/



/**
 * main( ): Main method of your PlugIn
 *
 * @param    string        $content: The content of the PlugIn
 * @param    array        $conf: The PlugIn Configuration
 * @return    string        The content that should be displayed on the website
 * @version 0.0.1
 * @since   0.0.1
 */
  public function main( $content, $conf )
  {
      // Globalise TypoScript configuration
    $this->conf = $conf;
    
    $prompt = $this->init( );
    
      // RETURN : init failed
    if( $prompt )
    {
      $content = '
        <div style="border:.4em solid red;margin:0 0 1em 0;padding:1em;text-align:center;">
          ' . $prompt . '
        </div>' . PHP_EOL .
      $content;
      return $this->pi_wrapInBaseClass( $content );
    }
      // RETURN : init failed


      // Prompt : content for current IP only
    $prompt = '
      <div style="border:.4em solid darkBlue;margin:0 0 1em 0;padding:1em;text-align:center;">
        Debugging report isn\'t visible for other clients. It\'s visible for allowed IPs only.
      </div>';
    $content = $content . $prompt;
      // Prompt : content for current IP only
    
    $content = $content . $this->promptGpvar( );
    
    $content = $content . $this->promptSession( );

      // Wrap : grey border
    $content = '
      <div style="border:.4em solid grey;margin:1em 0;padding:1em 1em 0 1em;">
        ' . $content . '
      </div>';
      // Wrap : grey border
    
    return $this->pi_wrapInBaseClass( $content );
      // Display content for the current IP
  }









  /***********************************************
   *
   * Init
   *
   **********************************************/



/**
 * init( ): 
 *
 * @return    string        $prompt : A prompt in case of an error
 * @access: private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function init( )
  {
      // Init localisation
    $this->pi_loadLL();

      // Get the values from the localconf.php file
    $this->arr_extConf = unserialize( $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extKey] );

      // New instance for user functions
    $this->userfunc = t3lib_div::makeInstance('tx_powermail4dev_userfunc');
    $this->userfunc->pObj = $this;
            
      // Init DRS - Development Reporting System
    $this->initDrs( );
      // Init flexform values
    $this->initFlexform( );
      // Init access by IP
    $this->initAccessByIp( );

      // Don't display content, if current IP doesn't match list of allowed IPs
    if( ! $this->bool_accessByIP )
    {
      return;
    }
      // Don't display content, if current IP doesn't match list of allowed IPs
    
    $arrResult = $this->initPmVersion( );

    if( empty( $arrResult['int'] ) )
    {
      $prompt = 'Powermail doesn\'t seem to be installed!<br />
        Prompt by TYPO3 ' . $this->extKey;
      return $prompt;      
    }
    
    $this->pmIntVersion = $arrResult['int'];
    $this->pmStrVersion = $arrResult['str'];
    
    return;
  }

  /**
 * initAccessByIp( ):  Set the global $bool_accessByIP.
 *
 * @return    void
 * @version 0.0.1
 * @since   0.0.1
 */
  private function initAccessByIp( )
  {
      // No access by default
    $this->bool_accessByIP = false;

      // Get list with allowed IPs
    $csvIP      = $this->ffConfigIp;
    $currentIP  = t3lib_div :: getIndpEnv( 'REMOTE_ADDR' );

      // Current IP is an element in the list
    $pos = strpos( $csvIP, $currentIP );
    if( ! ( $pos === false ) )
    {
      $this->bool_accessByIP = true;
    }
      // Current IP is an element in the list
      
      // DRS
    if( ! $this->b_drs_flexform )
    {
      return;
    }
    switch( $this->bool_accessByIP )
    {
      case( true ):
        $prompt = 'Access: current IP matchs the list of allowed IP. Result will prompt to the frontend.';
        t3lib_div::devlog(' [OK/FLEXFORM] '. $prompt, $this->extKey, -1 );
        break;
      case( false ):
      default:
        $prompt = 'No access: current IP doesn\'t match the list of allowed IP. Result won\'t prompt to the frontend.';
        t3lib_div::devlog(' [WARN/FLEXFORM] '. $prompt, $this->extKey, 2 );
        break;
    }
      // DRS

    
  }



/**
 * initDrs( ): Set the booleans for Warnings, Errors and DRS - Development Reporting System
 *
 * @version 0.0.1
 * @since  0.0.1
 *
 * @return    void
 */
  private function initDrs( )
  {

      //////////////////////////////////////////////////////////////////////
      //
      // Set the DRS mode
    switch( true )
    {
      case( strtolower( $this->arr_extConf['drs_mode'] ) == 'all' ):
      case( strtolower( $this->arr_extConf['drs_mode'] ) == 'enabled' ):
        $this->b_drs_all        = true;
        $this->b_drs_error      = true;
        $this->b_drs_warn       = true;
        $this->b_drs_info       = true;
        $this->b_drs_flexform   = true;
        $this->b_drs_gpvar      = true;
        $this->b_drs_session    = true;
        $this->b_drs_sql        = true;
        $this->b_drs_update     = true;
        $prompt = 'DRS - Development Reporting System: ' . $this->arr_extConf['drs_mode'];
        t3lib_div::devlog('[INFO/DRS] '. $prompt, $this->extKey, 0);
        $prompt = 'tt_content.uid: ' . $this->cObj->data['uid'];
        t3lib_div::devlog('[INFO/DRS] '. $prompt, $this->extKey, 0);
        break;
      case( strtolower( $this->arr_extConf['drs_mode'] ) == 'flexform' ):
        $this->b_drs_error      = true;
        $this->b_drs_warn       = true;
        $this->b_drs_info       = true;
        $this->b_drs_flexform   = true;
        $prompt = 'DRS - Development Reporting System: ' . $this->arr_extConf['drs_mode'];
        t3lib_div::devlog('[INFO/DRS] '. $prompt, $this->extKey, 0);
        break;
      case( strtolower( $this->arr_extConf['drs_mode'] ) == 'session' ):
        $this->b_drs_error      = true;
        $this->b_drs_warn       = true;
        $this->b_drs_info       = true;
        $this->b_drs_session    = true;
        $prompt = 'DRS - Development Reporting System: ' . $this->arr_extConf['drs_mode'];
        t3lib_div::devlog('[INFO/DRS] '. $prompt, $this->extKey, 0);
        break;
      case( strtolower( $this->arr_extConf['drs_mode'] ) == 'update' ):
        $this->b_drs_error      = true;
        $this->b_drs_warn       = true;
        $this->b_drs_info       = true;
        $this->b_drs_sql        = true;
        $this->b_drs_update     = true;
        $prompt = 'DRS - Development Reporting System: ' . $this->arr_extConf['drs_mode'];
        t3lib_div::devlog('[INFO/DRS] '. $prompt, $this->extKey, 0);
        break;
      // Set the DRS mode

    }
  }



  /***********************************************
   *
   * Flexform
   *
   **********************************************/

/**
 * initFlexform( ):
 *
 * @version 0.0.1
 * @since  0.0.1
 *
 * @return    void
 */
  private function initFlexform( )
  {
      // Init methods for pi_flexform
    $this->pi_initPIflexForm();

      // Sheet SDEF
    $this->initFlexformSheetSdef( );

      // Sheet config
    $this->initFlexformSheetConfig( );

      // Sheet prompts
    $this->initFlexformSheetPrompts( );

  }

/**
 * initFlexformSheetConfig( ):
 *
 * @version 0.0.1
 * @since  0.0.1
 *
 * @return    void
 */
  private function initFlexformSheetConfig( )
  {
    $arr_piFlexform = $this->cObj->data['pi_flexform'];
    $sheet          = 'config';

      // Field csvAllowedIp
    $field = 'csvAllowedIp';
      // Set the global pmUid
    $this->ffConfigIp = $this->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
      // DRS
    if( $this->b_drs_flexform )
    {
      $prompt = 'config.csvAllowedIp is ' . $this->ffConfigIp;
      t3lib_div::devlog(' [INFO/FLEXFORM] '. $prompt, $this->extKey, 0 );
    }
      // DRS
      // Field csvAllowedIp
  }

/**
 * initFlexformSheetPrompts( ):
 *
 * @version 0.0.1
 * @since  0.0.1
 *
 * @return    void
 */
  private function initFlexformSheetPrompts( )
  {
    $arr_piFlexform = $this->cObj->data['pi_flexform'];
    $sheet          = 'prompts';

      // Field gpvar
    $field = 'gpvar';
      // Set the global pmUid
    $this->ffPromptsGpvar = $this->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
    if( $this->ffPromptsGpvar === null )
    {
      $this->ffPromptsGpvar = true;
    }
      // DRS
    if( $this->b_drs_flexform )
    {
      $prompt = 'prompts.gpvar is ' . $this->ffPromptsGpvar;
      t3lib_div::devlog(' [INFO/FLEXFORM] '. $prompt, $this->extKey, 0 );
    }
      // DRS
      // Field gpvar
      
      // Field session
    $field = 'session';
      // Set the global pmUid
    $this->ffPromptsSession = $this->pi_getFFvalue($arr_piFlexform, $field, $sheet, 'lDEF', 'vDEF');
    if( $this->ffPromptsSession === null )
    {
      $this->ffPromptsSession = true;
    }
      // DRS
    if( $this->b_drs_flexform )
    {
      $prompt = 'prompts.session is ' . $this->ffPromptsSession;
      t3lib_div::devlog(' [INFO/FLEXFORM] '. $prompt, $this->extKey, 0 );
    }
      // DRS
      // Field session
  }

/**
 * initFlexformSheetSdef( ):
 *
 * @version 0.0.1
 * @since  0.0.1
 *
 * @return    void
 */
  private function initFlexformSheetSdef( )
  {
      // Get values for the globals pmUid, pmTitle, pmConfirm
    $arrResult = $this->userfunc->sqlPowermail( $this->cObj->data );

      // Set the globals pmUid, pmTitle, pmConfirm
    $this->pmUid     = $arrResult['uid'];
    $this->pmTitle   = $arrResult['title'];
    $this->pmConfirm = $arrResult['ffConfirm'];

      // DRS
    if( $this->b_drs_flexform )
    {
      $prompt = 'SDEF powermail.uid: ' . $this->pmUid;
      t3lib_div::devlog(' [INFO/FLEXFORM] '. $prompt, $this->extKey, 0 );
      $prompt = 'SDEF powermail.title: ' . $this->pmTitle;
      t3lib_div::devlog(' [INFO/FLEXFORM] '. $prompt, $this->extKey, 0 );
      $prompt = 'SDEF powermail.confirm: ' . $this->pmConfirm;
      t3lib_div::devlog(' [INFO/FLEXFORM] '. $prompt, $this->extKey, 0 );
    }
      // DRS
  }

/**
 * initPmVersion( ): 
 *
 * @return    string        $prompt : A prompt in case of an error
 * @access private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function initPmVersion( )
  {
    return $this->userfunc->extMgmVersion( 'powermail' );
  }










  /***********************************************
   *
   * SQL
   *
   **********************************************/



 /**
  * feuserSqlSelect( ):
  *
  * @return    boolean        true:
  * @version  0.0.1
  * @since    0.0.1
  */
  private function feuserSqlSelect( )
  {
    $loginUser = $GLOBALS['TSFE']->loginUser;
    if( ! $loginUser )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'Abort: $GLOBALS[TSFE]->loginUser isn\'t true!';
        t3lib_div::devlog(' [ERROR/UPDATE] '. $prompt, $this->extKey, 3 );
      }
      return false;
    }
    
    $uid = $GLOBALS['TSFE']->fe_user->user['uid'];
    if( empty( $uid ) )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'Abort: $GLOBALS[TSFE]->fe_user->user[uid] is empty!';
        t3lib_div::devlog(' [ERROR/UPDATE] '. $prompt, $this->extKey, 3 );
      }
      return false;
    }
    
      // Pid of fe_users
    $pid      = ( int ) $this->arr_extConf['fe_usersPid'];

      // Query
    $select_fields  = '*';
    $from_table     = 'fe_users';
    $where_clause   = "pid = " . $pid . " AND uid = " . $uid;
    $groupBy        = '';
    $orderBy        = '';
    $limit          = '';
      // Query

      // DRS
    if( $this->b_drs_sql )
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
      t3lib_div::devlog(' [INFO/SQL] '. $prompt, $this->extKey, 0 );
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

      // Handle result
    $this->feuserRecord =  $GLOBALS['TYPO3_DB']->sql_fetch_assoc( $res );

      // RETURN: no row
    if( empty( $this->feuserRecord ) )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'Abort. SQL query is empty!';
        t3lib_div::devlog(' [ERROR/SQL] '. $prompt, $this->extKey, 3 );
      }
      return false;
    }
      // RETURN: no row
      // Handle result

    return true;
  }









  /***********************************************
   *
   * Session
   *
   **********************************************/



/**
 * session( ): Main method of your PlugIn
 *
 * @param    string        $content: The content of the PlugIn
 * @param    array        $conf: The PlugIn Configuration
 * @return    string        The content that should be displayed on the website
 * @version 0.0.1
 * @since   0.0.1
 */
  private function session( $content )
  {
      // RETURN: Error with SQL query
    if( ! $this->feuserSqlSelect( ) )
    {
      if( $this->b_drs_error )
      {
        $prompt = 'Abort: Error with SQL query.';
        t3lib_div::devlog(' [ERROR/SESSION] '. $prompt, $this->extKey, 3 );
      }
      $content = $content . '
        <div style="border:.4em solid red;margin:0 0 1em 0;padding:1em;text-align:center;">
          ERROR with SQL query. Please take a look into the DRS - Development Reporting System.
        </div>';
      return $content;
    }
      // RETURN: Error with SQL query
    
      // Reset Powermail session data
    $this->sessionPowermailReset( );
    
      // Set DAT user session data
    $this->sessionDatusersSet( );
    
      // DRS
    if( $this->b_drs_session )
    {
      $prompt = 'Session data of datusers: ' . print_r( $GLOBALS['TSFE']->fe_user->getKey( 'ses', 'datusers' ), true );
      t3lib_div::devlog(' [INFO/SESSION] '. $prompt, $this->extKey, 0 );
    }
      // DRS
      
      // RETURN: Session data are empty
    $sessionData = $GLOBALS['TSFE']->fe_user->getKey( 'ses', 'datusers' );
    if( empty ( $sessionData ) )
    {
      if( $this->b_drs_session )
      {
        $prompt = 'Session data are empty!';
        t3lib_div::devlog(' [ERROR/SESSION] '. $prompt, $this->extKey, 3 );
      }
      $content = $content . '
        <div style="border:.4em solid red;margin:0 0 1em 0;padding:1em;text-align:center;">
          ERROR: Session data datusers are empty!
        </div>';
      return $content;
    }
      // RETURN: Session data are empty
    
      // Session data are set successful
    $content = $content . '
      <div style="border:.4em solid green;margin:0 0 1em 0;padding:1em;text-align:center;">
        Session data datusers are set.
      </div>';
    return $content;
      // Session data are set successful
  }



/**
 * sessionDatusersSet( ):
 *
 * @return    void
 * @version 0.0.1
 * @since   0.0.1
 */
  private function sessionDatusersSet( )
  { 
      // Array for session data
    $newSessionData = array( );

      // Get current session data
    $currSessionData = $GLOBALS['TSFE']->fe_user->getKey( 'ses', 'datusers' );

      // Add client number to session data
    $newSessionData['datusers_clientno'] = $currSessionData['datusers_clientno'];

      // Add fe_user record elements to session data
    foreach( $this->feuserRecord as $key => $value )
    {
      switch( $key )
      {
        case( 'uc' ):
          continue 2;
          break;
        case( 'username' ):
          $newSessionData['username'] = $value;
          break;
      }
      $newSessionData[ 'fe_users.' . $key ] = $value;
    }
      // Add fe_user record elements to session data
      
      // Set session data
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', 'datusers', $newSessionData );
    $GLOBALS["TSFE"]->storeSessionData();
  }



/**
 * sessionPowermailReset( ):
 *
 * @return    void
 * @version 0.0.1
 * @since   0.0.1
 */
  private function sessionPowermailReset( )
  {
    $arrParamPowermail = t3lib_div::_GP( 'tx_powermail_pi1' );

      // RETURN: there are Powermail parameter
    if( ! empty( $arrParamPowermail ) )
    {
      return;
    }
      // RETURN: there are Powermail parameter

      // Remove Powermail session data
      // Powermail plugin uid
    $uid = $this->pmUid; 
      // DRS
    if( $this->b_drs_session )
    {
      $tmpData = $GLOBALS['TSFE']->fe_user->getKey( 'ses', 'powermail_' . $uid );
      if( ! empty ( $tmpData ) )
      {
        $prompt = 'Powermail uid seems to be Ok: ' . $uid;
        t3lib_div::devlog(' [OK/SESSION] '. $prompt, $this->extKey, -1 );
      }
    }
      // DRS
      // Remove Powermail session data
    $GLOBALS['TSFE']->fe_user->setKey( 'ses', 'powermail_' . $uid, array( ) );
      // DRS
    if( $this->b_drs_session )
    {
      $prompt = 'Session data powermail_' . $uid .' are removed.';
      t3lib_div::devlog(' [INFO/SESSION] '. $prompt, $this->extKey, 0 );
    }
      // DRS
      // Remove Powermail session data
  }









  /***********************************************
   *
   * prompts
   *
   **********************************************/



/**
 * promptVar( ):
 *
 * @return    string        The content that should be displayed on the website
 * @access  private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function promptGpvar(  )
  {
    $content = null; 
    
      // RETURN : gpvar should not displayed
    if( ! $this->ffPromptsGpvar )
    {
      return $content;
    }
      // RETURN : gpvar should not displayed
      
      // Get the Powermail Paramater
    $gpvar = t3lib_div::_GP( 'tx_powermail_pi1' );

      // RETURN: no parameter
    if( empty( $gpvar ) )
    {
      if( $this->b_drs_gpvar )
      {
        $prompt = 'There isn\'t any powermail GET-/POST-parameter!';
        t3lib_div::devlog(' [INFO/GPVAR] '. $prompt, $this->extKey, 0 );
      }
      $content = '
        <div style="border:.4em solid darkBlue;margin:0 0 1em 0;padding:1em;text-align:center;">
          ' . $prompt . '
        </div>';
      return $content;
    }
      // RETURN: no parameter
    
      // prompt with GET-/POST-parameter
    $prompt = '
      <h2>
        Powermail GET-/POST-parameter
      </h2>
      <pre>
        ' . var_export( $gpvar, true ) . '
      </pre>';
      // prompt with GET-/POST-parameter

      // wrap with dark blue border
    $content = '
      <div style="border:.4em solid darkBlue;margin:0 0 1em 0;padding:1em;text-align:center;">
        ' . $prompt . '
      </div>';
      // wrap with dark blue border

    return $content;
  }



/**
 * promptSession( ):
 *
 * @return    string        The content that should be displayed on the website
 * @access  private
 * @version 0.0.1
 * @since   0.0.1
 */
  private function promptSession(  )
  {
    $content = null; 
    
      // RETURN : gpvar should not displayed
    if( ! $this->ffPromptsSession )
    {
      return $content;
    }
      // RETURN : gpvar should not displayed
      
      // Get the Powermail session data
    $sessionData = $GLOBALS['TSFE']->fe_user->getKey( 'ses', 'powermail_' . $this->pmUid );

      // RETURN: no session data
    if( empty( $sessionData ) )
    {
      if( $this->b_drs_session )
      {
        $prompt = 'There isn\'t any powermail session data!';
        t3lib_div::devlog(' [INFO/GPVAR] '. $prompt, $this->extKey, 0 );
      }
      $content = '
        <div style="border:.4em solid darkBlue;margin:0 0 1em 0;padding:1em;text-align:center;">
          ' . $prompt . '
        </div>';
      return $content;
    }
      // RETURN: no parameter
    
      // prompt with session data
    $prompt = '
      <h2>
        Powermail session data
      </h2>
      <pre>
        ' . var_export( $sessionData, true ) . '
      </pre>';
      // prompt with session data

      // wrap with dark blue border
    $content = '
      <div style="border:.4em solid darkBlue;margin:0 0 1em 0;padding:1em;text-align:center;">
        ' . $prompt . '
      </div>';
      // wrap with dark blue border

    return $content;
  }



}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail4dev/pi1/class.tx_powermail4dev_pi1.php'])
{
  include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/powermail4dev/pi1/class.tx_powermail4dev_pi1.php']);
}

?>