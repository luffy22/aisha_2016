<script>
 $(document).ready(function() {
    var text_max = 1500;
    $('#display_count').html(text_max + ' characters remaining');

    $('#astro_info').keyup(function() {
        var text_length = $('#astro_info').val().length;
        var text_remaining = text_max - text_length;

        $('#display_count').html(text_remaining + ' characters remaining');
    });
});
</script>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$user = JFactory::getUser();
if($user->guest)
{
    $location   = JURi::base()."login";
    echo header('Location: '.$location);
}
?>
<h1 class="display-3">Enter Details</h1>
<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Fields marked with asterix(*) are compulsory</div>
<div class="form-group"><label>Name:</label> <?php echo $this->msg['name']; ?></div>
<form enctype="multipart/form-data" method="post" action="<?php echo JRoute::_('index.php?option=com_extendedprofile&task=extendedprofile.saveAstro'); ?>">
    <input type="hidden" value="<?php echo $this->msg['UserId'] ?>" name="profile_id" />
<div class="form-group">
    <label for="astro_address1">Profile Picture*: </label>
    <input type="file" class="form-control" name="astro_img" id="astro_img" />
</div>
<div class="form-group">
    <label for="astro_address1">Address Line 1: </label>
    <input type="text" class="form-control" name="astro_address1" id="astro_addr1" placeholder="Enter Address of Office or Home" />
</div>
<div class="form-group">
    <label for="astro_address2">Address Line 2: </label>
    <input type="text" class="form-control" name="astro_address2" id="astro_addr2" placeholder="Enter Related Address Details" />
</div>
<div class="form-group">
    <label for="astro_city">City*: </label>
    <input type="text" class="form-control" name="astro_city" id="astro_city" placeholder="Enter City Name" required />
</div>
<div class="form-group">
    <label for="astro_state">State/Province/Region: </label>
    <input type="text" class="form-control" name="astro_state" id="astro_state" placeholder="Enter Name of State/Province/Region" />
</div>
<div class="form-group">
    <label for="astro_state">Country*: </label>
    <input type="text" class="form-control" name="astro_country" id="astro_country" placeholder="Enter Name of Country" required/>
</div>
<div class="form-group">
    <label for="astro_pcode">Pin Code: </label>
    <input type="text" class="form-control" name="astro_pcode" id="astro_pcode" placeholder="Enter Pin Code of your place" />
</div>
<div class="form-group">
    <label for="astro_phone">Phone: </label>
    <input type="number" class="form-text" name="astro_code" id="astro_code" placeholder="area code" /> -
    <input type="number" class="form-text2" name="astro_phone" id="astro_phone" placeholder="phone number" /> Example 22-xxxxxxxx for Mumbai, India
</div>
<div class="form-group">
    <label for="astro_mobile">Mobile(No Country Code): </label>
    <input type="number" class="form-text2" name="astro_mobile" id="astro_mobile" placeholder="Enter Mobile Number" /> 
    <input type="checkbox" name="astro_whatsapp" value="yes" /> Check if available on Whatsapp
</div> 
<div class="form-group">
    <label for="astro_web">Website/Blog: </label>
    <input type="text" class="form-control" name="astro_web" id="astro_web" placeholder="Enter Website/Blog if Available" />
</div>
<div class="form-group">
    <label for="astro_info">Describe About Yourself(1500 Character Max)*: </label>
    <textarea rows="7" class="form-control" name="astro_info" id="astro_info" placeholder="Describe a little about your Astrological Expertise. It should be short, meaningful and helpful for your clients to understand" maxlength="1500" required></textarea>
    <span id="display_count"></span>.
</div>
<div class="form-group">
    <button type="submit" name="submit_profile" class="btn btn-primary" >Submit</button>
    <button type="reset" name="cancel" class="btn btn-navbar">Cancel</button>
</div>
</form>