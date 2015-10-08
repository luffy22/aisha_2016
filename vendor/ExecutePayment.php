
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
use Paypal\Api\Transaction;
use Paypal\Api\Payer;
use PayPal\Api\Amount;
use Paypal\Api\Details;
use PayPal\Api\Authorization;


if (isset($_GET['success']) && $_GET['success'] == 'true') 
{
    $paymentId = $_GET['paymentId']; 
    //$payment = Payment::get($paymentId, $apiContext);
    // @return result Array of result which shows payment related information
    //echo $execution->getPayerId();
    try {
             // Get payment id, and then execute the payment request
             $payment               = Payment::get($paymentId, $apiContext);
             $payer_id              = $payment->getPayer()->getPayerInfo()->getPayerId();
             $transactions           = $payment->getTransactions();
             $transaction           = $transactions[0];
             $execution             = new PaymentExecution();
             $execution             ->setPayerId($payer_id);
             $execution             ->addTransaction($transaction);
             $result                = $payment->execute($execution, $apiContext);
             
             if($result)
             {
                 $transactions     = $payment->getTransactions();
                 $transaction      = $transactions[0];
                 $relatedResources  = $transaction->getRelatedResources();
                 $relatedResource   = $relatedResources[0];
                 $order             = $relatedResource->getOrder();
                 $authorization     = new Authorization();
                 $result            = $order->authorize($authorization, $apiContext);
             }
         }
         catch (Exception $ex) 
         {
            //header('Refresh: 2; URL=http://www.astroisha.com/quesconfirm?payment_success=false');
         }
        echo $relatedResources;exit;
       
        //header('Location:http://localhost/aisha/vendor/authorizePayment.php?pay_id='.$paymentId);
        
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
