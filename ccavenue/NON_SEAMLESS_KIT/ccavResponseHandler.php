<?php include('Crypto.php')?>
<?php

	error_reporting(0);
	
	$workingKey='143063E52AFFE0A6170B547A9E7CEAE1';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
	echo "<center>";

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
	}

	if($order_status==="Success")
	{
		echo "<br>Your Order with Astro Isha is successful. Please check your email for confirmation.";
		
	}
	else if($order_status==="Aborted")
	{
		echo "<br>Your order is not successful. Please try again in order to complete the order.<br/>"; 
	?>
		<a href="http://www.astroisha.com/ask-question">Retry</a>
		<a href="http://www.astroisha.com">Home Page</a>
	<?php	
	}
	else if($order_status==="Failure")
	{
		echo "<br>Your transaction was declined. Please retry or navigate to Home Page";
	?>
		<a href="http://www.astroisha.com/ask-question">Retry</a>
		<a href="http://www.astroisha.com">Home Page</a>
	<?php	
	}
	else
	{
		echo "<br>Security Error. Illegal access detected";
	
	}

	echo "<br><br>";

	echo "<table cellspacing=4 cellpadding=4>";
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
	    	echo '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
	}

	echo "</table><br>";
	echo "</center>";
?>
