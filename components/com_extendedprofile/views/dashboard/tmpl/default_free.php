<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
defined('_JEXEC') or die;
//print_r($this->msg);
?>
<div class="spacer"></div>
<h3>Astrologer: Free Account</h3>
<div class="text-right"><span class="glyphicon glyphicon-user"></span> Profile  |  <a href="<?php echo JURI::base() ?>details" title="Edit Details"><span class="glyphicon glyphicon-pencil"></span> Details</a></div>
<div class="panel-group" id="free_astro" role="tablist" aria-multiselectable="true">
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="free_astro1">
        <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#free_astro" href="#astro_data1" aria-expanded="true" aria-controls="astro_data1">
          Basic Details
        </a>
      </h4>
    </div>
    <div id="astro_data1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="free_astro1">
      <div class="panel-body">
          <div class="row">
              <div class="col-md-3">
                  <?php if(empty($this->msg['img_1_id'])){ ?>
                  <img src="<?php echo JURI::base() ?>images/blank-profile.png" alt="blank photo" 
                       class="img-responsive img-thumbnail" title="Please Upload Your Photo..." />
                  <?php
                  }
                  else
                  {
                  ?>
                  <img src="<?php echo JURI::base() ?>images/profiles/<?php echo $this->msg['img_1_id'] ?>" alt="<?php echo $this->msg['name'] ?> image" 
                       class="img-responsive img-thumbnail" title="<?php echo $this->msg['name']; ?>" /><?php } ?>
              </div>
              <div class="col-md-5">
                  <div class="table-responsive">
                  <table class="table table-hover table-bordered">
                      <tr><th>Username: </th><td><?php echo $this->msg['username']; ?></td></tr>
                      <tr><th>Name: </th><td><?php echo $this->msg['name']; ?></td></tr>
                      <tr><th>Profile Status: </th><td><?php echo ucfirst($this->msg['profile_status']); ?></td></tr>
                  </table>
                  </div>
              </div>
          </div>
          <div class="spacer"></div>
          <p class="lead">Little About Me:</p>
            <p class="text-left"><?php echo $this->msg['info']; ?></p>
         </div>
    </div>    
</div>
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="free_astro2">
        <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#free_astro" href="#astro_data2" aria-expanded="true" aria-controls="astro_data2">
          Location Details
        </a>
      </h4>
    </div>
    <div id="astro_data2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="free_astro2">
      <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-hover table-bordered">
              <tr><th><span class="glyphicon glyphicon-home"></span> Address Line 1: </th><td><?php if(empty($this->msg['addr_1'])){echo "Not Provided"; }else{ echo $this->msg['addr_1'];} ?></td></tr>
              <tr><th><span class="glyphicon glyphicon-road"></span> Address Line 2: </th><td><?php if(empty($this->msg['addr_2'])){echo "Not Provided"; }else{ echo $this->msg['addr_2'];} ?></td></tr>
              <tr><th>City: </th><td><?php echo $this->msg['city']; ?></td></tr>
              <tr><th>State: </th><td><?php if(empty($this->msg['state'])){echo "Not Provided"; }else{ echo $this->msg['state'];} ?></td></tr>
              <tr><th>Country: </th><td><?php echo $this->msg['country']; ?></td></tr>
              <tr><th>Postcode: </th><td><?php echo $this->msg['postcode']; ?></td></tr>
          </table>
          </div>
    </div>
    </div>
</div>
    <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="free_astro3">
        <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#free_astro" href="#astro_data3" aria-expanded="true" aria-controls="astro_data3">
          Contact Details
        </a>
      </h4>
    </div>
    <div id="astro_data3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="free_astro3">
      <div class="panel-body">
          <div class="table-responsive">
          <table class="table table-hover table-bordered">
              <tr><th><span class="glyphicon glyphicon-envelope"></span> Email: </th><td><?php echo $this->msg['email']; ?></td></tr>
              <tr><th><span class="glyphicon glyphicon-phone-alt"></span> Phone: </th><td><?php echo $this->msg['phone']; ?></td></tr>
              <tr><th><span class="glyphicon glyphicon-phone"></span> Mobile: </th><td><?php if(empty($this->msg['mobile'])){echo "Not Provided"; }else{ echo $this->msg['mobile'];} ?></td></tr>
              <tr><th><img src="<?php echo JURI::base() ?>images/whatsapp.png" alt="whatsapp logo" title="Whether Astrologer Uses Whatsapp" height="25px" width="25px" /> Available On Whatsapp: </th><td><?php echo ucfirst($this->msg['whatsapp']); ?></td></tr>
              <tr><th><span class="glyphicon glyphicon-globe"></span> Website/Blog: </th><td><?php if(empty($this->msg['website'])){echo 'Not Provided'; }else{ echo $this->msg['website']; } ?></td></tr>
          </table>
          </div>
    </div>
    </div>
</div>
</div>