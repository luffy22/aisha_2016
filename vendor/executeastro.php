<?php
header('Content-type: application/json');
include_once('bootstrap.php');
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\Sale;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;


if (isset($_GET['success']) && $_GET['success'] == 'true') 
{
    $token                  = $_GET['uniq_id'];
    $uid                    = $_GET['user_id'];
    $paymentId              = $_GET['paymentId'];
    $email                  = $_GET['email'];
    $payment                = Payment::get($paymentId, $apiContext);
    $payer_id               = $payment->getPayer()->getPayerInfo()->getPayerId();
    $transactions           = $payment->getTransactions();
    $transaction            = $transactions[0];
    $execution              = new PaymentExecution();
    $execution              ->setPayerId($payer_id);
    $execution              ->addTransaction($transaction);
    $result                 = $payment->execute($execution, $apiContext);
    
    $payment                = Payment::get($paymentId, $apiContext);
    $transactions           = $payment->getTransactions();
    $relatedResources       = $transactions[0]->getRelatedResources();
    $sale                   = $relatedResources[0]->getSale();
    $saleId = $sale->getId();
    $server                 = "http://" . $_SERVER['SERVER_NAME'];
    try 
    {
        $sale = Sale::get($saleId, $apiContext);
        if($sale->state == "completed")
        {
            $sale_id         = $sale->id;
             //echo $server;exit;
            header('Location:'.$server.'aisha/index.php?option=com_extendedprofile&task=dashboard.confirmPayment&uid='.$uid.'&status=success&sale_id='.$sale_id.'&token='.$token.'&email='.$email);
        } 
         else {
             header('Location:'.$server.'aisha/index.php?option=com_extendedprofile&task=dashboard.confirmPayment&uid='.$uid.'&status=fail&token='.$token.'&email='.$email);

        }
    }
    catch(Exception $e)
    {
        echo $e->getError();
    }
}
else if(isset($_GET['success']) && $_GET['success'] == 'false')
{
    $token                  = $_GET['uniq_id'];
    $uid                    = $_GET['user_id'];
    $email                  = $_GET['email'];
    header('Location:'.$server.'aisha/index.php?option=com_extendedprofile&task=dashboard.confirmPayment&uid='.$uid.'&status=fail&token='.$token.'&email='.$email);
}
?>
