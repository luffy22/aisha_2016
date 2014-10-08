<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
function submitForm()
{
    alert("Calls");
}
</script>
</head>
<body>
<?php
    if(isset($_GET['ref'])&&(isset($_GET['auth'])))
    {
?>
    <form name="authref" onload="javascript:submitForm();" enctype="application/x-www-form-urlencoded" method="post" class="form-horizontal"
          action="<?php echo JRoute::_('index.php?option=com_astrologin&task=process.ConfirmUser'); ?>">
        <input type="hidden" name="emailref" id="emailref" value="<?php echo $_GET['ref']; ?>"/>
        <input type="hidden" name="authref" id="authref" value="<?php echo $_GET['auth']; ?>" />
    </form>
<?php
    }
?>
</body>
</html>