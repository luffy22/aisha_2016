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
use Paypal\Api\Capture;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

    $paymentId = 'PAY-7ME617039A989632WK3Z5KMI';  // enter payment id here

      $paymentId = $_GET['paymentId']; 
    //$payment = Payment::get($paymentId, $apiContext);
    // @return result Array of result which shows payment related information
    //echo $execution->getPayerId();
    try {
             // Get payment id, and then execute the payment request
             $token                 = 'token_56f3d530d577e';
             $payment               = Payment::get($paymentId, $apiContext);
             $payer_id              = $payment->getPayer()->getPayerInfo()->getPayerId();
             $transactions           = $payment->getTransactions();
             $transaction           = $transactions[0];
             $execution             = new PaymentExecution();
             $execution             ->setPayerId($payer_id);
             $execution             ->addTransaction($transaction);
             $result                = $payment->execute($execution, $apiContext);
             $transactions          = $payment->getTransactions();
             $transaction           = $transactions[0];
             $relatedResources      = $transaction->getRelatedResources();
             $relatedResource       = $relatedResources[0];
             $order                 = $relatedResource->getOrder();
             $currency              = $transaction->getAmount()->getCurrency();
             $total                 = $transaction->getAmount()->getTotal();
             $authorization         = new Authorization();
             $authorization         ->setAmount(new Amount(
                                    '{
                                        "total":"'.$total.'",
                                        "currency":"'.$currency.'"
                                    }'));
             $result            = $order->authorize($authorization, $apiContext);
             $server            = "http://" . $_SERVER['SERVER_NAME'];
             //echo $server;exit;
             header('Location:'.$server.'/index.php?option=com_astrologin&task=astroask.confirmPayment&id='.$paymentId.'&order_id='.$result->getId().'&token='.$token);

?>
