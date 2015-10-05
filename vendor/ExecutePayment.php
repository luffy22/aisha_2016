
<?php
header('Content-type: application/json');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('bootstrap.php');
use PayPal\Api\ExecutePayment; 
use PayPal\Api\Payment; 
use PayPal\Api\PaymentExecution;

if (isset($_GET['success']) && $_GET['success'] == 'true') 
{
    $paymentId = $_GET['paymentId']; 
    //$payment = Payment::get($paymentId, $apiContext);
    // @return result Array of result which shows payment related information
    //echo $execution->getPayerId();
    try {
             // Get payment id, and then execute the payment request
             $payment = Payment::get($paymentId, $apiContext);
             $execution = new PaymentExecution();
             $execution->setPayerId($_GET['paymentId']);
             $result = $payment->execute($execution, $apiContext);  // executing the payment requests
         }
         catch (Exception $ex) 
         {
            header('Refresh: 2; URL=http://www.astroisha.com/quesconfirm?payment_success=false');
         }

    echo $result;exit;

   // $info	= json_decode($payment);
    //$id         = $info->id;
    //$server     = 'http://'.$_SERVER['SERVER_NAME'];
   // header('Location:'.$server.'/aisha/index.php?option=com_astrologin&task=astroask.confirmPayment&id='.$id);
    
	//echo $info->invoice_number;
}
else if(isset($_GET['success']) && $_GET['success'] == 'false')
{   
    header('Refresh: 2; URL=http://www.astroisha.com/quesconfirm?payment_success=false');
}
?>
