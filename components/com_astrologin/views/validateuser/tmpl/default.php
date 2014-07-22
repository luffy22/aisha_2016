<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<body>
    <p>Given User is not authenticated.<br/>Please confirm your email to authenticate</p>
    <p>Alternatively ask for re-authentication by clicking button below</p>
    <form enctype="application/x-www-form-urlencoded" method="post" class="form-horizontal"
      action="<?php echo JRoute::_('index.php?option=com_astrologin&task=process.resendauth'); ?>">
    <fieldset class="fieldscontent">
        <?php 
            if(!isset($_GET['email']))
            {
        ?>
        <div class="control-group">
            <div class="control-label">Email</div>
            <div class="controls">
                <input type="email" name="emailauth" id="emailauth_1" placeholder="Enter Valid Email" />
            </div>
        </div>
        <?php
            }
            else
            {
        ?>
                <input type="hidden" name="emailauth" id="emailauth_1" placeholder="Enter Valid Email" value="<?php echo $_GET['email']; ?>" />
        <?php
            }
        ?>
        <div class="control-group">
            <div class="controls">
                <button type="submit" name="reauth" class="btn btn-primary">Resend Authetication</button>
                
            </div>
        </div>
    </fieldset>
    </form>
</body>
</html>