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
if(isset($_GET['payment'])&&($_GET['payment']=='dd'))
{
?>
    <h3>Our Bank Details are:</h3>
    <div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Canara Bank</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Bank Of Baroda</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
        <br/>
        <div><strong>Payable To:</strong><code>&nbsp;</code>ROHAN Y DESAI</div>
        <div><strong>Account No:</strong><code>&nbsp;</code>0175101023581<br/>
        1st Floor, Sashwat Complex, Kankaria Road,<br/> Ahmedabad - 380022
        </div>
        <div><strong>IFSC Code:</strong><code>&nbsp;</code>CNRB0000175</div>
        <div><strong>MICR Code:</strong><code>&nbsp;</code>380015008</div>
        <div><strong>Swift Code:</strong><code>&nbsp;</code>CNRBINBBAFD</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="profile">
        <br/>
        <div><strong>Payable To:</strong><code>&nbsp;</code>ROHAN YATINKUMAR DESAI</div>
        <div><strong>Account No:</strong><code>&nbsp;</code>03290100012275<br/>
        Gita Mandir, Bhulabhai Cross Road,<br/> Ahmedabad - 380022
        </div>
        <div><strong>IFSC Code:</strong><code>&nbsp;</code>BARBOGITAMA</div>
        <div><strong>MICR Code:</strong><code>&nbsp;</code>380012014</div>
        <div><strong>Swift Code:</strong><code>&nbsp;</code>BARBINBBBHD</div>
    </div>
  </div>
  <br/>
  <?php
    }
    else if((isset($_GET['payment']))&&($_GET['payment']=='paypal'))
    {
    ?>
  <h3 style="align:center">Confirmation Of Payment</h3>
  <p>&nbsp;&nbsp;&nbsp;This is to confirm that your online payment has been received. We would process your request in 7 Working Days.</p>
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