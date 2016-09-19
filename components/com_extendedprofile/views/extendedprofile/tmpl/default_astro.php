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
if(isset($_GET['image'])&&($_GET['image']=='false'))
    {
?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> File must be an image file with jpg, png or gif extension.</div>
<?php
    }
if(isset($_GET['image'])&&($_GET['image']=='size'))
    {
?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> File must be less then 2 MB(Mega Byte).</div>
<?php
    }
//print_r($this->msg);
?>
<h1 class="display-3">Enter Details</h1>
<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Fields marked with asterix(*) are compulsory</div>
<div class="form-group"><label>Name:</label> <?php echo $user->name;; ?></div>
<?php 
    if(count($this->msg) > 0)
    {
?>
    <form enctype="multipart/form-data" method="post" action="<?php echo JRoute::_('index.php?option=com_extendedprofile&task=extendedprofile.updateAstro'); ?>">
 <?php
    }
    else
    {
 ?>
<form enctype="multipart/form-data" method="post" action="<?php echo JRoute::_('index.php?option=com_extendedprofile&task=extendedprofile.saveAstro'); ?>">
<?php
    }
?>
<input type="hidden" value="<?php echo $this->msg['UserId'] ?>" name="profile_id" />
<div class="form-group">
    <label for="astro_address1">Profile Picture*: </label>
    <?php if(empty($this->msg['img_1_id'])) { ?>
    <input type="file" class="form-control" name="astro_img" id="astro_img"  />
    <?php } else{ ?>
    <img src="<?php echo JURI::base() ?>images/profiles/<?php echo $this->msg['img_1_id'] ?>" height="100px" width="100px" />
    <?php
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_address1">Address Line 1: </label>
    <?php if(empty($this->msg['addr_1'])) { ?>
    <input type="text" class="form-control" name="astro_address1" id="astro_addr1" placeholder="Enter Address of Office or Home" />
    <?php } else{ ?>
    <input type="text" class="form-control" name="astro_address1" id="astro_addr1" value="<?php echo $this->msg['addr_1'] ?>" />
    <?php
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_address2">Address Line 2: </label>
    <?php if(empty($this->msg['addr_1'])) { ?>    
    <input type="text" class="form-control" name="astro_address2" id="astro_addr2" placeholder="Enter Related Address Details" />
    <?php } else{ ?>
    <input type="text" class="form-control" name="astro_address2" id="astro_addr2" value="<?php echo $this->msg['addr_2'] ?>" />
    <?php
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_city">City*: </label>
    <?php if(empty($this->msg['city'])) { ?>    
    <input type="text" class="form-control" name="astro_city" id="astro_city" placeholder="Enter City Name" required />
    <?php } else{ ?>
    <input type="text" class="form-control" name="astro_city" id="astro_city" value="<?php echo $this->msg['city'] ?>" required />
    <?php
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_state">State/Province/Region: </label>
    <?php if(empty($this->msg['state'])) { ?>  
    <input type="text" class="form-control" name="astro_state" id="astro_state" placeholder="Enter Name of State/Province/Region" />
    <?php } else{ ?>
    <input type="text" class="form-control" name="astro_state" id="astro_state" value="<?php echo $this->msg['state'] ?>"  />
    <?php
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_state">Country*: </label>
    <?php if(empty($this->msg['state'])) { ?>  
    <input type="text" class="form-control" name="astro_country" id="astro_country" placeholder="Enter Name of Country" required/>
    <?php } else{ ?>
    <input type="text" class="form-control" name="astro_country" id="astro_country" value="<?php echo $this->msg['country'] ?>" required />
    <?php
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_pcode">Pin Code: </label>
    <?php if(empty($this->msg['postcode'])) { ?>  
    <input type="text" class="form-control" name="astro_pcode" id="astro_pcode" placeholder="Enter Pin Code of your place" />
    <?php } else{ ?>
    <input type="text" class="form-control" name="astro_pcode" id="astro_pcode" value="<?php echo $this->msg['postcode'] ?>"  />
    <?php
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_phone">Phone: </label>
    <?php 
    if(empty($this->msg['postcode'])) { ?>  
    <input type="number" class="form-text" name="astro_code" id="astro_code" placeholder="area code" /> -
    <input type="number" class="form-text2" name="astro_phone" id="astro_phone" placeholder="phone number" /> Example 22-xxxxxxxx for Mumbai, India
    <?php } else{ 
          $phone    = explode("-",$this->msg['phone']);
        ?>
    <input type="number" class="form-text" name="astro_code" id="astro_code" value="<?php echo $phone[0]; ?>" /> -
    <input type="number" class="form-text2" name="astro_phone" id="astro_phone" value="<?php echo $phone[1]; ?>" /> Example 22-xxxxxxxx for Mumbai, India
    <?php 
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_mobile">Mobile(No Country Code): </label>
    <?php if(empty($this->msg['mobile'])) { ?>  
    <input type="number" class="form-text2" name="astro_mobile" id="astro_mobile" placeholder="Enter Mobile Number" /> 
    <input type="checkbox" name="astro_whatsapp" value="yes" /> Check if available on Whatsapp
    <?php } else{ ?>
    <input type="number" class="form-text2" name="astro_mobile" id="astro_mobile" value="<?php echo $this->msg['mobile'] ?>" /> 
    <?php if($this->msg['whatsapp'] == "yes"){ ?>
    <input type="checkbox" name="astro_whatsapp" value="yes" checked /> Check if available on Whatsapp
    <?php } else{ ?>
    <input type="checkbox" name="astro_whatsapp" value="yes" /> Check if available on Whatsapp
    <?php
            }
    }
    ?>
</div> 
<div class="form-group">
    <label for="astro_web">Website/Blog: </label>
    <?php if(empty($this->msg['website'])) { ?>  
    <input type="text" class="form-control" name="astro_web" id="astro_web" placeholder="Enter Website/Blog if Available" />
    <?php } else{ ?>
    <input type="text" class="form-control" name="astro_web" id="astro_web" value="<?php echo $this->msg['website'] ?>" />
    <?php
    }
    ?>
</div>
<div class="form-group">
    <label for="astro_info">Describe About Yourself(1500 Character Max)*: </label>
    <?php if(empty($this->msg['website'])) { ?> 
    <textarea rows="7" class="form-control" name="astro_info" id="astro_info" placeholder="Describe a little about your Astrological Expertise. It should be short, meaningful and helpful for your clients to understand" maxlength="1500" required></textarea>
    <span id="display_count"></span>.
    <?php } else{ ?>
     <textarea rows="7" class="form-control" name="astro_info" id="astro_info" maxlength="1500" required><?php echo $this->msg['info'] ?></textarea>
     <span id="display_count"></span>.
    <?php
    }
    ?>
</div>
<div class="form-group">
    <?php 
        if(count($this->msg) > 0)
        {
    ?>
    <button type="submit" name="update_profile" class="btn btn-primary" >Update</button>
    <?php
        }
       else
       {
   ?>
     <button type="submit" name="submit_profile" class="btn btn-primary" >Submit</button>
  <?php
       }
  ?>
    <button type="reset" name="cancel" class="btn btn-navbar">Cancel</button>
</div>
</form>