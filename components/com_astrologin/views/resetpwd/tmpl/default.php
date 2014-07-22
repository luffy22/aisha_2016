<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
<h3><center>Reset Password</center></h3>
<form enctype="application/x-www-form-urlencoded" method="post" class="form-horizontal"
      action="<?php echo JRoute::_('index.php?option=com_astrologin&task=process.resetpwd'); ?>">
      <fieldset class="fieldscontent">
        <div class="control-group">
            <div class="control-label">Enter Password</div>
            <div class="controls"><input type="password" name="forgotpwd" id="res_pw1" placeholder="Enter your password" /></div>
        </div>
        <div class="control-group">
            <div class="control-label">Re-enter Password</div>
            <div class="controls"><input type="password" name="repass" id="res_pw2" placeholder="Re-enter your password" /></div>
        </div>
        <div class="control-group">
            <input type="hidden" name="resetemail" id="res_email" value="<?php $_GET['email']; ?>" />
            <div class="controls"><button type="submit" name="resetpwd" class="btn btn-primary">Reset Password</button></div>   
        </div>
      </fieldset>
</form>
</body>
</html>