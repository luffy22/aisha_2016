<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
<h3><center>Forgot Password</center></h3>
<form enctype="application/x-www-form-urlencoded" method="post" class="form-horizontal"
      action="<?php echo JRoute::_('index.php?option=com_astrologin&task=process.forgotpwd'); ?>">
      <fieldset class="fieldscontent">
        <div class="control-group">
            <div class="control-label">Enter Email</div>
            <div class="controls"><input type="email" name="forgotemail" id="fr_email" placeholder="Enter your email" /></div>
        </div>
        <div class="control-group">
            <div class="controls"><button type="submit" name="forgotpwd" class="btn btn-primary">Submit</button></div>   
        </div>
      </fieldset>
</form>
</body>
</html>