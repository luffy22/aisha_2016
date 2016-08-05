<body onload="hidefields()">
<script>
function showfields()
{
    $('#profile_hidden').show();
    document.getElementById("profile_hidden").style.visibility = 'visible';
}
function hidefields()
{
    $('#profile_hidden').hide();
    document.getElementById("profile_hidden").style.visibility = 'hidden';
    //alert("calls");
}
</script>
<?php
//defined('_JEXEC') or die('Restricted access');
$user = JFactory::getUser();
if($user->guest)
 {
    $location   = JURi::base()."login";
    echo header('Location: '.$location);
 }
else
{
    if(isset($_GET['upload_pic'])&&($_GET['upload_pic']=='none'))
    {
?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Kindly Upload a Picture If You Wish To Register as an Astrologer.</div>
<?php
    }

?>
<h1 class="display-3">Extended Profile</h1>
<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Fields marked with asterix(*) are compulsory</div>
<div class="form-group"><label>Name:</label> <?php echo $this->msg['name']; ?></div>
<form enctype="multipart/form-data" method="post" action="<?php echo JRoute::_('index.php?option=com_extendedprofile&task=extendedprofile.registerAstro'); ?>">
<div class="form-group">
        <label for="inputGender" class="control-label">Gender:</label>
         <input type="radio" name="gender_profile" value="male" id="lagna_gender1" /> Male
        <input type="radio" name="gender_profile" value="female" id="lagna_gender2" checked="checked" /> Female
    </div>
<div class="form-group">
    <label for="dob_profile">Date Of Birth: </label>
    <input type="date" class="form-control" name="dob_profile" id="dob_profile" placeholder="Enter your date of birth" min="1910-01-01" max="2050-12-31"/>
</div>   
<div class="form-group">
    <label for="tob_profile">Time Of Birth(Hour:Minute:Second): </label>
    <select class="select2" name="tob_profile_hr">
    <?php
    for($i=0;$i<12;$i++)
    {
        if($i < 10)
        {
    ?>
        <option value="<?php echo "0".$i ?>"><?php echo "0".$i ?></option>
    <?php
        }
        else
        {
    ?>
        <option value="<?php echo $i ?>"><?php echo $i; ?></option>
    <?php
        }
    }
    ?>
    </select>
    <select class="select2" name="tob_profile_min">
    <?php
    for($i=0;$i<60;$i++)
    {
        if($i < 10)
        {
    ?>
        <option value="<?php echo "0".$i ?>"><?php echo "0".$i ?></option>
    <?php
        }
        else
        {
    ?>
        <option value="<?php echo $i ?>"><?php echo $i; ?></option>
    <?php
        }
    }
    ?>
    </select>
    <select class="select2" name="tob_profile_sec">
    <?php
    for($i=0;$i<60;$i++)
    {
        if($i < 10)
        {
    ?>
        <option value="<?php echo "0".$i ?>"><?php echo "0".$i ?></option>
    <?php
        }
        else
        {
    ?>
        <option value="<?php echo $i ?>"><?php echo $i; ?></option>
    <?php
        }
    }
    ?>
    </select>
    <select class="select2" name="tob_profile_ampm">
        <option value="am">am</option>
        <option value="pm">pm</option>
    </select>
</div>
<div class="form-group">
    <label for="pob_profile">Place Of Birth: </label>
    <input type="text" name="pob_profile" id="pob_profile" placeholder="Enter Your Place Of Birth" class="form-control" />
</div>
<div class="form-group">
        <label for="astroyes" class="control-label">Are You An Astrologer:</label>
         <input type="radio" name="astro_confirm" value="yes" id="astro_yes" onclick="javascript:showfields();" /> Yes
        <input type="radio" name="astro_confirm" value="no" id="astro_no" checked="che" onclick="javscript:hidefields();" /> No
    </div>
<div id="profile_hidden">
    <div class="form-group">
    <input type="checkbox" name="condition_profile" value="yes" />
    Kindly Read and Accept the <a href="">Terms and Conditions</a> for Astrologers *
    </div>
    <div class="form-group">
        <label for="profile_pic">Upload Picture *:</label>
        <input type="file" name="profile_pic" id="profile_pic" />
    </div>
</div>
<div class="form-group">
        <button type="submit" name="submit_profile" class="btn btn-primary" >Update</button>
        <button type="reset" name="cancel" class="btn btn-navbar">Cancel</button>
    </div>
</form>
<?php
}
?>
</body>