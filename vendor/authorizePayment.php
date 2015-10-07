<?php

header('Content-type: application/json');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('bootstrap.php');
use PayPal\Api\Payment; 
use PayPal\Api\Authorization;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Api\Capture;

if(isset($_GET['auth_id']))
{
    $auth_id = $_GET['auth_id'];
    
    try{
         $payment = Payment::get($auth_id, $apiContext);
       
    }catch(Exception $ex)
    {
        echo "Unable to Authorize payment. Please try again at later time...";
        //header('Refresh: 2; URL=http://www.astroisha.com/quesconfirm?authorize=false');
    }
   echo $payment;exit;
}
?>
