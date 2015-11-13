<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
function getIP() {
  foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
     if (array_key_exists($key, $_SERVER) === true) {
        foreach (explode(',', $_SERVER[$key]) as $ip) {
           if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
              return $ip;
           }
        }
     }
  }
    //$ip = '117.196.1.11';
  //$ip = '212.58.244.20';
  //$ip   = '223.223.146.119';
  //$ip   = '208.91.198.52';
 //$ip = '66.249.73.190';
  //$ip    = '176.102.49.192'; // uk ip
  //$ip = '122.175.21.127';
  //$ip = '157.55.39.123';
  return $ip;
}
   
$json = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='. getIP()); 
$data = json_decode($json);
if($data->geobytesinternet == 'IN')
{
?>
<h3>Payment and Refunds</h3>
<div class="spacer"></div>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Authorization And Payment
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
          <p>&nbsp;&nbsp;Clients can place an Order for a Jyotishi(Vedic Astrology) based reading
              on <a href="http://www.astroisha.com/ask-question" target="_blank">Ask An Astrologer</a> Page. On submission of Question Form, client would be redirected to a third party Payment Gateway: <a href="http://www.ccavenue.com/" target="_blank" title="CCAvenue Home Page">CCAvenue</a>. There the client can pay using 
          one of the payment options provided to complete the Order. On successful completion of order a confirmation email would be provided 
          with Payment Details and Details Of Question/Questions. Clients are requested to keep the Confirmation Email until Order is Completed to avoid issues later.</p>
          <p>&nbsp;&nbsp;The money debited from Clients Bank Account is safe with Third Party Payment Gateway. Astro Isha does not 
          press for Remittance of Client Payment until the Client Query has been resolved.</p>
      </div>
    </div>
  </div>
  <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Order Cancellation And Refunds
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
          <p>&nbsp;&nbsp;If the Client has a change of mind and wishes to Cancel the Order he has 24 Working Hours to do so. Kindly notify us at <?php echo JHtml::_('email.cloak', 'admin@astroisha.com'); ?> 
              and also mention the token number or Unique Tracking ID or Bank Reference Number provided in the confirmation email. Astro Isha would Cancel the Order and money would be credited back to your account. 
              A confirmation of Cancellation of Order would be provided to Client. As money is still with Third Party Payment Gateway during this time it is 
              advised that the Client asks the concerned Payment Gateway about the duration of time before money is credited back into his/her account.
          </p>
          <p>&nbsp;&nbsp;After 24 Hours since order has been confirmed, Astro Isha reserves the right to proceed with the Order and Client Requests for Cancellation of Order 
          cannot be entertained.</p>
      </div>
    </div>
  </div>
  <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Order Processing and Confirmation
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
          <p>&nbsp;&nbsp;After 24 Hours Astro Isha would start processing the Order. The client query would be provided with a logical answer 
          and the answer would be provided as attachment in the email. Astro Isha would only ask for Remittance of Payment once 
          the client query has been resolved with a logical answer.</p>
      </div>
    </div>
  </div>
    <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingFour">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
          Order Failure and Refunds
        </a>
      </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div class="panel-body">
          <p>&nbsp;&nbsp;As mentioned earlier Astro Isha would only ask for Remittance once the Client is emailed 
          with Answer and Logical Solution to his Questions. This Order would likely be processed in 7-10 Working Days.</p>
          <p>&nbsp;&nbsp;In case there is a failure to give answer to clients query after 12 Days of Order Placement, the money is credited 
          directly back into the Account of Client.</p>
      </div>
    </div>
  </div>
</div>
<?php
}
else
{
?>
<h3>Payment and Refunds</h3>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<div class="spacer"></div>
    <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingFive">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
          Authorization And Payments
        </a>
      </h4>
    </div>
    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
      <div class="panel-body">
          <p>&nbsp;&nbsp;Clients can place an Order for a Jyotishi(Vedic Astrology) based reading
          on <a href="http://www.astroisha.com/ask-question" target="_blank">Ask An Astrologer</a> Page. On successful 
          completion of the Question Form, client would be redirected to <a href="http://www.paypal.com" targe="_blank" title="Paypal Home Page">Paypal</a> which is one of the most preferred and safe 
          payment gateway for International Transactions. There the client can pay using 
          one of the payment options provided by Paypal to complete the Order. On successful completion of order a confirmation email would be provided 
          with Payment Details and Question Details. Clients are requested to keep the Confirmation Email until Order is Completed to avoid issues later.</p>
          <p>&nbsp;&nbsp;The money debited from Clients Bank Account is safe with Paypal. Astro Isha does not 
          press for Remittance of Client Payment until the Client Query has been resolved.</p>
      </div>
    </div>
  </div>
    <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingNine">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
          Currencies Accepted
        </a>
      </h4>
    </div>
    <div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNine">
      <div class="panel-body">
          <table class="table table-condensed table-bordered">
              <tr>
              <th>Location</th>
              <th>Currency</th>
              <th>Symbol</th>
              </tr>
              <tr>
                  <td>United States</td>
                  <td>US Dollars</td>
                  <td>US &#36;</td>
              </tr>
              <tr>
                  <td>United Kingdom</td>
                  <td>British Sterling Pound</td>
                  <td>GBP &#8356;</td>
              </tr>
              <tr>
                  <td>Europe</td>
                  <td>Euro</td>
                  <td>&#8364;</td>
              </tr>
              <tr>
                  <td>Canada</td>
                  <td>Canadian Dollars</td>
                  <td>CAD &#36;</td>
              </tr>
              <tr>
                  <td>Australia</td>
                  <td>Australian Dollars</td>
                  <td>AUD &#36;</td>
              </tr>
              <tr>
                  <td>Singapore</td>
                  <td>Singapore Dollars</td>
                  <td>SGD &#36;</td>
              </tr>
              <tr>
                  <td>New Zealand</td>
                  <td>New Zealand Dollars</td>
                  <td>NZD &#36;</td>
              </tr>
              <tr>
                  <td>Rest Of The World</td>
                  <td>US Dollars</td>
                  <td>US &#36;</td>
              </tr>
          </table>
      </div>
    </div>
  </div>
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingSix">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
          Order Cancellation And Refunds
        </a>
      </h4>
    </div>
    <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSux">
      <div class="panel-body">
          <p>&nbsp;&nbsp;If the Client has a change of mind and wishes to Cancel the Order he has 24 Working Hours to do so. Kindly notify us at <?php echo JHtml::_('email.cloak', 'admin@astroisha.com'); ?> 
              and also mention the token number or Paypal Transaction ID or Paypal Order ID provided in the confirmation email. Astro Isha would Cancel the Order and money would be credited back to your account. 
              A confirmation of Cancellation of Order would be provided to Client. As money is still with Paypal during this time it is 
              advised that the Client asks Paypal about the duration of time before money is credited back into his/her account.
          </p>
          <p>&nbsp;&nbsp;After 24 Hours since order has been confirmed, Astro Isha reserves the right to proceed with the Order and Client Requests for Cancellation of Order 
          cannot be entertained.</p>
      </div>
    </div>
  </div>
  <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingSeven">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseThree">
          Order Processing and Confirmation
        </a>
      </h4>
    </div>
    <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
      <div class="panel-body">
          <p>&nbsp;&nbsp;After 24 Hours Astro Isha would start processing the Order. The client query would be provided with a logical answer 
          and the answer would be provided as attachment in the email. Astro Isha would only ask for Remittance of Payment once 
          the client query has been resolved with a logical answer.</p>
      </div>
    </div>
  </div>
    <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingEight">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
          Order Failure and Refunds
        </a>
      </h4>
    </div>
    <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
      <div class="panel-body">
          <p>&nbsp;&nbsp;As mentioned earlier Astro Isha would only ask for Remittance once the Client is emailed 
          with Answer and Logical Solution to his Questions. This Order would likely be processed in 7-10 Working Days.</p>
          <p>&nbsp;&nbsp;In case there is a failure to give answer to clients query after 29 Days of Order Placement, the money is credited 
          directly back into the Account of Client.</p>
      </div>
    </div>
  </div>
</div>
<?php
}
?>