<?php

header('Content-type: application/json');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('bootstrap.php');
$payment = require __DIR__ . '/ExecutePayment.php';

use PayPal\Api\Payment; 
use PayPal\Api\Authorization;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Api\Capture;

if(isset($_GET['pay_id']))
{
    $pay_id = $_GET['pay_id'];
    
    try{
            $payment            = Payment::get($pay_id, $apiContext);
            //$transactions       = $payment->getTransactions();
            //$transaction        = $transactions[0];
            //$relatedResources   = $transaction->getRelatedResources();
            //$relatedResource    = $relatedResources[0];
            //$order              = $relatedResource->getOrder();
            //$authorization      = new Authorization();
            //$result             = $order->authorize($authorization, $apiContext);
       
    }catch(Exception $ex)
    {
        echo "Unable to Authorize payment. Please try again at later time...";
        //header('Refresh: 2; URL=http://www.astroisha.com/quesconfirm?authorize=false');
    }
   echo $payment;exit;
}
?>
