<?php
//print_r($this->msg);exit;
defined('_JEXEC') or die('Restricted access');
$user       = JFactory::getUser();
   if(isset($_GET['terms'])&&($_GET['terms']=='no'))
    {
?>
        <div class="alert alert-danger alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Kindly accept the Terms and Conditions if you wish to join as an Astrologer.</div>
<?php
    }

?>
<h1 class="display-3">Payment Details</h1>
<div class="alert alert-warning alert-dismissible fade in" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button> Fields marked with asterix(*) are compulsory</div>
<div class="form-group"><label>Name:</label> <?php echo $user->name; ?></div>
<form enctype="application/x-www-form-urlencoded" method="post" action="<?php echo JRoute::_('index.php?option=com_extendedprofile&task=finance.saveDetails'); ?>">
<div class="form-group">
    <label for="astro_paid" class="control-label">Account Name:</label>
    <input type ="text" placeholder="Provide Account Name" name="acc_bank" id="acc_bank" class="form-control" />
</div>
<div class="form-group">
    <label for="astro_paid" class="control-label">Account Number:</label>
    <input type ="text" placeholder="Provide Account Number" name="acc_number" id="acc_number" class="form-control" />
</div>
<div class="form-group">
    <label for="astro_paid" class="control-label">Bank Name:</label>
    <input type ="text" placeholder="Provide Name Of Bank" name="acc_bank_name" id="acc_bank_name" class="form-control" />
</div>
<div class="form-group">
    <label for="astro_paid" class="control-label">Bank Address:</label>
    <input type ="text" placeholder="Provide Bank Account Address" name="acc_bank_addr" id="acc_bank_addr" class="form-control" />
</div>
<div class="form-group">
    <label for="astro_paid" class="control-label">IBAN Number:</label>
    <input type ="text" placeholder="Provide IBAN(Internation Bank Account Number)" name="acc_iban" id="acc_iban" class="form-control" />
</div>
<div class="form-group">
    <label for="astro_paid" class="control-label">Swift Code:</label>
    <input type ="text" placeholder="Provide Account Swift Code" name="acc_swift" id="acc_swift" class="form-control" />
</div>
<div class="form-group">
    <label for="astro_paid" class="control-label">IFSC Code(Indian Banks Only):</label>
    <input type ="text" placeholder="Provide IFSC Code" name="acc_ifsc" id="acc_ifsc" class="form-control" />
</div>
<div class="form-group">
    <label for="astro_paid" class="control-label">Paypal Id/Email:</label>
    <input type ="text" placeholder="Provide Paypal ID Or Email" name="acc_paypal" id="acc_paypal" class="form-control" />
</div>
<div class="form-group">
        <button type="submit" name="bank_submit" class="btn btn-primary" >Submit</button>
        <button type="reset" name="cancel" class="btn btn-warning">Reset</button>
        <a href="<?php echo JURI::base() ?>dashboard" class="btn btn-danger">Cancel</a>
    </div>
</form>
</body>
