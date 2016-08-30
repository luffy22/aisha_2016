<body onload="hideFields()">
<script>
function showFields()
{
    $('#profile_hidden1').show();
    document.getElementById("profile_hidden1").style.visibility = 'visible';
    $('#profile_hidden2').show();
    document.getElementById("profile_hidden2").style.visibility = 'visible';
}
function hideFields()
{
    $('#profile_hidden1').hide();
    document.getElementById("profile_hidden1").style.visibility = 'hidden';
    $('#profile_hidden2').hide();
    document.getElementById("profile_hidden2").style.visibility = 'hidden';
}
</script>
<?php
//print_r($this->msg);exit;
//defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
if($user->guest)
 {
    $location   = JURi::base()."login";
    echo header('Location: '.$location);
 }
else
{
    if(isset($_GET['terms'])&&($_GET['terms']=='no'))
    {
?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Kindly accept the Terms and Conditions if you wish to join as an Astrologer.</div>
<?php
    }

?>
<h1 class="display-3">Membership Type</h1>
<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Fields marked with asterix(*) are compulsory</div>
<div class="form-group"><label>Name:</label> <?php echo $this->msg['name']; ?></div>
<form enctype="application/x-www-form-urlencoded" method="post" action="<?php echo JRoute::_('index.php?option=com_extendedprofile&task=extendedprofile.registerAstro'); ?>">
<div class="form-group">
    <label for="astro_paid" class="control-label">Membership:</label>
         <input type="radio" name="astro_type" value="paid" id="astro_paid" onclick="javascript:showFields();" /> Paid
        <input type="radio" name="astro_type" value="free" id="astro_free" checked="checked" onclick="javscript:hideFields();"/> Free
</div>
<div id="profile_hidden1" class="form-group">
    <label for="astro_online" class="control-label">Payment Type:</label>
    <input type="radio" name="astro_pay" value="online" id="astro_online" checked="checked"  /> Online
    <input type="radio" name="astro_pay" value="cheque" id="astro_cheque" /> Cheque
    <input type="radio" name="astro_pay" value="transfer" id="astro_transfter" />Direct Transfer
</div>
<div id="profile_hidden2" class="form-group">
    <label for="astro_amount" class="control-label">Amount:</label>
    <?php echo "250 Rs" ?>
</div>
<div class="form-group">
    <input type="checkbox" name="condition_profile" value="yes" />
    <label for="condition_profile">Kindly Read and Accept the <a href="">Terms and Conditions</a> for Astrologers *</label>
</div>
<div class="form-group">
        <button type="submit" name="submit_profile" class="btn btn-primary" >Submit</button>
        <button type="reset" name="cancel" class="btn btn-navbar">Cancel</button>
    </div>
</form>
<?php
}
?>
</body>