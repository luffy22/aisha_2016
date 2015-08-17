<?php
header('Content-type: application/json');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('bootstrap.php');
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\PayerInfo;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

if(isset($_GET['token']))
{

$name               = $_GET['name'];
$token              = $_GET['token'];
$email              = $_GET['email'];
$quantity           = (int)1;
$currency           = $_GET['curr'];
$fees               = $_GET['fees'];
$payer_info         = new PayerInfo();
$payer_info         ->setFirstName($name);

$payer = new Payer();
$payer->setPaymentMethod("paypal")
       ->setPayerInfo($payer_info);
$name = "Detailed Explanation";

$item = new Item();
$item->setName($name)
    ->setCurrency($currency)
    ->setQuantity($quantity)
    ->setSku($token)
    ->setPrice($fees);
    
$itemlist       = new ItemList();
$itemlist       ->setItems(array($item));

$details        = new Details();
$details        ->setFee($fees);

$amount         = new Amount();
$amount         ->setCurrency($currency)
                ->setTotal($fees)
                ->setDetails($details);
        
$transaction    = new Transaction();
$transaction    ->setAmount($amount)
                ->setItemList($itemlist)
                ->setDescription("Payment Description")
                ->setInvoiceNumber(uniqid());
                

$baseUrl = getBaseUrl();
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("$baseUrl/ExecutePayment.php?success=true")
    ->setCancelUrl("$baseUrl/ExecutePayment.php?success=false");

$payment = new Payment();
$payment->setIntent("order")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));

$request = clone $payment;
try {
    $payment->create($apiContext);
} catch (Exception $ex) {
    ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
    exit(1);
}
$approvalUrl    = $payment->getApprovalLink();
$payment_id     = $payment->id;

$conn           = new mysqli("localhost","root","desai1985","astroisha");
if(!$conn)
{
    echo "Error establishing connectiont to Database: ";
}
else
{

    $host   = "localhost";$user = "astroxou_admin";
    $pwd    = "*Jrp;F.=OKzG";$db   = "astroxou_jvidya";
    $mysqli = new mysqli($host,$user,$pwd,$db);
    /* check connection */
    if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
    }
    
    $query = "UPDATE jv_questions SET paypal_id='$payment_id' WHERE UniqueID='$token'";
    $result	= mysqli_query($mysqli, $query);
    
    if($result)
    {
        mysqli_close($myqli);
        header('Location:'.$approvalUrl);

    }
    else
    {
        echo "Unable to process requests";
    }
   
}
//header('Location:'.$approvalUrl);
//echo $approvalUrl;exit;
 //ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);


}
else
{
    echo "Crucial Information Mising. Please Try Again...";
}
?>
