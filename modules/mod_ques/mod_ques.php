<?php
 // no direct access
defined('_JEXEC') or die;
require_once( dirname(__FILE__) . '/helper.php' );
//$toprated 	= modTopContentHelper::gettoprated();
$quesmenu		= modQuesHelper::getQuesMenu();		
require( JModuleHelper::getLayoutPath('mod_ques'));

?>
