<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$root_dir   = dirname(dirname(dirname(dirname(dirname(__DIR__)))));
// # Create Payment using PayPal as payment method
// This sample code demonstrates how you can process a 
// PayPal Account based Payment.
// API used: /v1/payments/payment
include_once($root_dir.'/vendor/bootstrap.php');
//require_once(__DIR__ . '/../bootstrap.php';
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

// ### Payer
// A resource representing a Payer that funds a payment
// For paypal account payments, set payment method
// to 'paypal'.
$payer = new Payer();
$payer->setPaymentMethod("paypal");

// ### Itemized information
// (Optional) Lets you specify item wise
// information
$item1 = new Item();
$item1->setName('Ground Coffee 40 oz')
    ->setCurrency('USD')
    ->setQuantity(1)
    ->setSku("123123") // Similar to `item_number` in Classic API
    ->setPrice(7.5);
$item2 = new Item();
$item2->setName('Granola bars')
    ->setCurrency('USD')
    ->setQuantity(5)
    ->setSku("321321") // Similar to `item_number` in Classic API
    ->setPrice(2);

$itemList = new ItemList();
$itemList->setItems(array($item1, $item2));

// ### Additional payment details
// Use this optional field to set additional
// payment information such as tax, shipping
// charges etc.
$details = new Details();
$details->setShipping(1.2)
    ->setTax(1.3)
    ->setSubtotal(17.50);

// ### Amount
// Lets you specify a payment amount.
// You can also specify additional details
// such as shipping, tax.
$amount = new Amount();
$amount->setCurrency("USD")
    ->setTotal(20)
    ->setDetails($details);

// ### Transaction
// A transaction defines the contract of a
// payment - what is the payment for and who
// is fulfilling it. 
$transaction = new Transaction();
$transaction->setAmount($amount)
    ->setItemList($itemList)
    ->setDescription("Payment description")
    ->setInvoiceNumber(uniqid());

// ### Redirect urls
// Set the urls that the buyer must be redirected to after 
// payment approval/ cancellation.
$baseUrl = getBaseUrl();
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true")
    ->setCancelUrl("$baseUrl/ExecutePayment.php?success=false");

// ### Payment
// A Payment Resource; create one using
// the above types and intent set to 'sale'
$payment = new Payment();
$payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));


// For Sample Purposes Only.
$request = clone $payment;

// ### Create Payment
// Create a payment by calling the 'create' method
// passing it a valid apiContext.
// (See bootstrap.php for more on `ApiContext`)
// The return object contains the state and the
// url to which the buyer must be redirected to
// for payment approval
try {
    $payment->create($apiContext);
} catch (Exception $ex) {
    // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 	ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
    exit(1);
}

// ### Get redirect url
// The API response provides the url that you must redirect
// the buyer to. Retrieve the url from the $payment->getApprovalLink()
// method
$approvalUrl = $payment->getApprovalLink();

// NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
 ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

return $payment;

?>
<!--
<div class="container-fluid">
    <div class="alert alert-success alert-dismissible" role="alert" data-dismiss="alert" >
        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
        <span class="sr-only">Success:</span>
        Question Form Submitted. Please check your email for confirmation.
    </div>
  <div class="alert alert-warning alert-dismissible" role="alert" data-dismiss="alert">
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
        <span class="sr-only">Success:</span>   
        Please ensure <?php //echo JHtml::_('email.cloak', 'admin@astroisha.com'); ?> is added to your safe senders list to ensure receiving of emails from Astro Isha.
</div>
    <h3>Our Bank Details are:</h3>
    <div>

  <!-- Nav tabs -->
  <!--<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Canara Bank</a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Bank Of Baroda</a></li>
  </ul>

  <!-- Tab panes -->
  <!--<div class="tab-content">
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
  <a href="<?php //echo JURI::base().'ask-question'; ?>">
  <button type="button" class="btn btn-primary" aria-label="Left Align">
  <span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span> Go Back
  </button></a><a href="<?php //echo JURI::base(); ?>">
  <button type="button" class="btn btn-primary" aria-label="Left Align">
  <span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home Page
</button></a>
</div>
</div>
-->