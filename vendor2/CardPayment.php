<?php

/************************************************************
 This is the main web page for the DoDirectPayment sample.
This page allows the user to enter name, address, amount,
and credit card information. It also accept input variable
paymentType which becomes the value of the PAYMENTACTION
parameter.

************************************************************/
// clearing the session before starting new API Call
session_unset();
$file = dirname(dirname(__FILE__));
//echo $file;exit;
?>
<html>
<head>
<title>Debit Card/Credit Card Processing</title>
<link href="/aisha/templates/astroisha/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="/aisha/templates/astroisha/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="/aisha/templates/astroisha/css/style.css" rel="stylesheet" type="text/css" />
<link href="/aisha/templates/astroisha/css/template.css" rel="stylesheet" type="text/css" />
</head>
<body>
    
<div class="col-xs-12 col-md-4">
    <div class="spacer"></div>
<img src="https://devtools-paypal.com/image/bdg_payments_by_pp_2line.png">
<div class="container-fluid">
<form method="POST" action="process.php"
    name="DoDirectPaymentForm">
    <div class="form-group">
    <input type="hidden" name="paymentType" value="order" />
    </div>
    <div class="form-group">
        <label>Your Name As It Appears On The Card</label>
        <input class="form-control" type="text" name="card_name" placeholder="For example: Peter H. Sanders"/>
    </div>
    <div class="form-group">
        <label>Card type</label>
        <?php
            if((isset($_GET['curr']))&&($_GET['curr']=='USD'||$_GET['curr']=='GBP'))
            {
        ?>
	<select class="form-control" name="creditCardType">
            <option value="Visa" selected="selected">Visa</option>
            <option value="MasterCard">MasterCard</option>
            <option value="Discover">Discover</option>
            <option value="Amex">American Express</option>
        </select>
        <?php
            }
            else
            {
        ?>
        <select class="form-control" name="creditCardType">
            <option value="Visa" selected="selected">Visa</option>
            <option value="MasterCard">MasterCard</option>
        </select>
        <?php
            }
        ?>
    </div>
    <div class="form-group">
        <label>Card number: </label>
         <input class="form-control" type="number" name="creditCardNumber" maxlength="19" />
    </div>
    <div class="form-group">
        <label>Expiry date: </label>
            <select class="select2" name="expDateMonth">
                <?php
                $date = new DateTime();
                $currmonth = $date->format('M');
                for($i=0;$i<12;$i++)
                {
                ?>
                <option value="<?php echo $currmonth ?>"><?php echo $currmonth; ?></option>
                <?php
                $date->add(new DateInterval('P1M'));
                $currmonth = $date->format('M');
                }
                ?>
            </select>
            <select class="select2" name="expDateYear">
            <?php
                    $curryear = $date ->format('Y');
                    for($i=0;$i<8;$i++)
                    {
            ?>
                   <option value="<?php echo $curryear;  ?>"><?php echo $curryear; ?></option>
                   <?php
                   $date->add(new DateInterval('P1Y'));
                    $curryear = $date->format('Y');
                    }
                    
                   ?>
            </select>
        </div>
        <div class="form-group">
            <label>CVV</label>
            <input class="form-control" type="number" name="cvv2Number"  />
            <a href="https://www.cvvnumber.com/cvv.html" target="_blank" style="font-size:11px">What is my CVV code?</a>
        </div>
				<div class="params">
					<div class="param_name">Amount</div>
					<div class="param_value">
						 <input type="text" size="5" maxlength="7" name="amount" value="1.00"> USD
					</div>
				</div>
				<div class="params">
					<div class="param_name">IPN listener URL</div>
					<div class="param_value">
						<input type="text" size="80" maxlength="200" name="notifyURL" value="">
					</div>
				</div>	
				<div class="submit">
					<input type="submit" name="DoDirectPaymentBtn"
						value="DoDirectPayment" />
				</div>	
</form>
</div>			
</div>
</body>
</html>

