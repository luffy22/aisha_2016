<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
<div class="control-group">
    <div align="right">Already a member? <a href="index.php?option=com_astrologin&view=astrologin"><div class="btn btn-primary">Login in</div></a></div>
</div>
<?php if(isset($_GET['dupuname'])&&($_GET['dupuname']='duplicate')): ?>
    <div class="error">Username Already Exists. Please try another username</div>
<?php endif; ?>
<?php if(isset($_GET['dupemail'])&&($_GET['dupemail']='duplicate2')): ?>
    <div class="error">Email Already Exists. Please try another email</div>
<?php endif; ?>
<?php if(isset($_GET['failure'])&&($_GET['failure']='fail')): ?>
    <div class="error">Fail to add data. Please try again</div>
<?php endif; ?>
<?php if(isset($_GET['mail'])&&($_GET['mail']='sent')): ?>
    <div class="error">Fail to add data. Please try again</div>
<?php endif; ?>
<form enctype="application/x-www-form-urlencoded" method="post" class="form-horizontal"
      action="<?php echo JRoute::_('index.php?option=com_astrologin&task=process.userregister'); ?>">
    <fieldset class="fieldscontent">
        <div class="control-group">
            <div class="control-label2">Enter Username</div>
            <div class="controls2"><input type="text" name="username" id="ar_uname" placeholder="Enter your username" /></div>
        </div>
        <div class="control-group">
            <div class="control-label2">Enter Password</div>
            <div class="controls2"><input type="password" name="password" id="ar_passwd" placeholder="Enter your password" /></div>
        </div>
        <div class="control-group">
            <div class="control-label2">Confirm Password</div>
            <div class="controls2"><input type="password" name="cpassword" id="ar_cpasswd" placeholder="Re-enter your password" /></div>
        </div>
        <div class="control-group">
            <div class="control-label2">Enter Email</div>
            <div class="controls2"><input type="email" name="email" id="ar_email" placeholder="Enter Valid Email" /></div>
        </div>
        <div class="control-group">
            <div class="control-label2">Enter Image Value</div>
        </div>
        <div class="control-group">
            <div class="control-label2">
            <?php
                JPluginHelper::importPlugin('captcha');
                $dispatcher = JDispatcher::getInstance();
                $dispatcher->trigger('onInit','dynamic_recaptcha_1')
            ?>
            <div id="dynamic_recaptcha_1"></div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls2">
                <button type="submit" name="register" class="btn btn-primary" onclick="javascript:validateRegister();return false;">Register</button>
                <button type="reset" name="cancel" class="btn btn-navbar">Cancel</button>
            </div>
        </div>
    </fieldset>
</form>
</body>
</html>