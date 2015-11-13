<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="container-fluid">
    <div class="alert alert-success alert-dismissible" role="alert" data-dismiss="alert" >
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
        <span class="sr-only">Success:</span>
        Question Form Submitted. Please check your email for confirmation.
    </div>
  <div class="alert alert-warning alert-dismissible" role="alert" data-dismiss="alert">
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        <span class="sr-only">Success:</span>   
        Please ensure <?php echo JHtml::_('email.cloak', 'admin@astroisha.com'); ?> is added to your safe senders list to ensure receiving of emails from Astro Isha.
</div>
<?php
    if(isset($_GET['payment'])&&($_GET['payment']=='ccavenue'))
    {
    ?>
  <h3 style="align:center">Confirmation Of Payment</h3>
  <p>&nbsp;&nbsp;&nbsp;This is to confirm that your online payment has been received. We would process your request in 7 Working Days.</p>
  <?php
    }
    else if((isset($_GET['payment']))&&($_GET['payment']=='paypal'))
    {
    ?>
  <h3 style="align:center">Confirmation Of Payment</h3>
  <p>&nbsp;&nbsp;&nbsp;This is to confirm that your online payment has been received. We would process your request in 7 Working Days.</p>
  <?php
    }
    else if((isset($_GET['payment']))&&($_GET['payment']=='false'))
    {
   ?>
    <h3 style="align:center">Cancellation Of Payment</h3>
    <p>&nbsp;&nbsp;&nbsp;The order was cancelled. Please try asking your question again.</p>
  <?php
    }
    else if((isset($_GET['payment_success']))&&($_GET['payment_success']=='false'))
    {
  ?>
    <h3 style="align:center">Cancellation Of Payment</h3>
    <p>&nbsp;&nbsp;&nbsp;The order was cancelled. Please try asking your question again.</p>
    <?php
    }
    ?>
  <a href="<?php echo JURI::base().'ask-question'; ?>">
  <button type="button" class="btn btn-primary" aria-label="Left Align">
  <span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span> Go Back
  </button></a><a href="<?php echo JURI::base(); ?>">
  <button type="button" class="btn btn-primary" aria-label="Left Align">
  <span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home Page
</button></a>
</div>
</div>