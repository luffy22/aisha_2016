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

    $paymentId = "PAY-7ME617039A989632WK3Z5KMI"; 
    //$payment = Payment::get($paymentId, $apiContext);
    // @return result Array of result which shows payment related information
    // Get payment id, and then execute the payment request
     $payment               = Payment::get($paymentId, $apiContext);
     $transactions          = $payment->getTransactions();
     $relatedResources      = $transactions[0]->getRelatedResources();
     $authorization         = $relatedResources[1]->getAuthorization();

     try {
        $amount = new Amount();
        $amount->setCurrency("GBP");
        $amount->setTotal("10.00");
    $authorization->setAmount($amount);

    $reAuthorization = $authorization->reauthorize($apiContext);
} catch (Exception $ex) {
                print_r($ex);
}