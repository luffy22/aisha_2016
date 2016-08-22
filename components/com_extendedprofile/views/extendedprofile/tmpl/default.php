<body onload="hidefields()">
<script>
function showfields()
{
    $('#profile_hidden1').show();
    document.getElementById("profile_hidden1").style.visibility = 'visible';
    $('#profile_hidden2').show();
    document.getElementById("profile_hidden2").style.visibility = 'visible';
}
function hidefields()
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
<h1 class="display-3">User Type</h1>
<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Fields marked with asterix(*) are compulsory</div>
<div class="form-group"><label>Name:</label> <?php echo $this->msg['name']; ?></div>
<form enctype="application/x-www-form-urlencoded" method="post" action="<?php echo JRoute::_('index.php?option=com_extendedprofile&task=extendedprofile.registerAstro'); ?>">
<div class="form-group">
        <label for="inputGender" class="control-label">User Type:</label>
         <input type="radio" name="user_type" value="astrologer" id="lagna_gender1" onclick="javascript:showfields();" /> Astrologer
        <input type="radio" name="user_type" value="user" id="lagna_gender2" checked="checked" onclick="javscript:hidefields();"/> Normal User
    </div>
<div id="profile_hidden2">
    <div class="form-group">
    <label for="inputGender" class="control-label">Membership:</label>
         <input type="radio" name="astro_type" value="paid" id="astro_paid" onclick="javascript:paidMember();" /> Paid
        <input type="radio" name="astro_type" value="free" id="astro_free" checked="checked" onclick="javscript:freeMember();"/> Free
    </div>
</div>
<div id="profile_hidden1">
    <div class="form-group">
    <input type="checkbox" name="condition_profile" value="yes" />
    Kindly Read and Accept the <a href="">Terms and Conditions</a> for Astrologers *
    </div>
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