<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$session        =& JFactory::getSession();
$sessuser       = $session->get('username');
$sessemail      = $session->get('email');

if(empty($sessuser))
{
	// The user is not logged in or needs to provide a password.
	echo $this->loadTemplate('login');
}
else
{
	// The user is already logged in.
	$app = JFactory::getApplication();
        $app->redirect(JRoute::_('index.php', false));
}
