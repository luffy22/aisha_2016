<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
$session        =& JFactory::getSession();
$sessuser       = $session->get('username');
$sessemail      = $session->get('email');
if((empty($sessuser))&&(empty($sessemail)))
{
?>
<div id="login-link">
    <div class="link"><a href="#" onclick="javascrit:showLogin();">Login</a></div>
</div>
<div id="login-form">
    <div class="error" id="error-msg">Invalid Credentials</div>
<form class="form-inline">
    <div class="control-group">
        <div class="control-label">Enter Username or Email</div>
        <div class="controls"><input type="text" name="moduname" id="mod-uname" placeholder="Enter Username or Email" /></div>
    </div>
    <div class="control-group">
        <div class="control-label">Enter Password</div>
        <div class="controls"><input type="password" name="modpwd" id="mod-pwd" placeholder="Enter your password" /></div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="button" class="btn btn-primary" onclick="javascript:LoginUser()">Login</button>
            <button type="button" class="btn" onclick="hideLogin();">Cancel</button>
        </div>
    </div>
    <div class="control-group">
		<div class="controls"><a href="index.php?option=com_astrologin&view=forgot">Forgot Password?</a></div>
		<div class="controls"><a href="index.php?option=com_astrologin&view=astroregister">Register With Us</a></div>
    </div>
</form>
</div>
<?php
}
else
{
    echo trim($sessuser);?><a href="index.php?option=com_astrologin&task=process.userlogout&user=<?php echo $sessuser; ?>" class="btn btn-danger">Log Out</a>
<?php
}
?>
