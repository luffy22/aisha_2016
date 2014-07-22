<?php
defined('_JEXEC') or die;
?>
<html>
<head>
<title>Astro Isha Login</title>
<!--<script type="text/javascript">
    window.fbAsyncInit = function() {
  FB.init({
    appId      : '220390744824296',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.0' // use version 2.0
  });
</script>
<script type="text/javascript" language="javascript">
function checkLoginState() 
{
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
}
// This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      var uid = response.authResponse.userID;
    var accessToken = response.authResponse.accessToken;
    var dirname = '/jvidya/components/com_astrologin/controllers/process.php';
    var data    = "fblogin";
    jQuery.ajax({
        type: "POST",
        url : dirname,
        data: "uid="+uid+"&accesstoken="+accessToken+"&fblogin="+data,
        dataType: 'text',
        
    }).done(function(data){alert(data)}).fail(function(){alert("fail")});
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }
</script>-->
</head>
<body>
<!--<div class="social-group">
    <div class="fb-label">
        <fb:login-button scope="public_profile,email" data-size="large" onlogin="checkLoginState();">
        </fb:login-button>

        <div id="status">
        </div>
    </div>
    <div class="google-login">
        
    </div>
</div>-->
<?php
    if(isset($_GET['confirm'])&&($_GET['confirm']=='yes'))
    {
        echo "User Confirmation Successful. Please Login in";
    }
    else if(isset($_GET['reset']))
    {
        echo "Password successfully reset. Please Login in";
    }
    if(isset($_GET['sendmail'])&&($_GET['sendmail']=='success')): ?>
    <div class="error" id="error-msg">Fail to add data. Please try again</div>
<?php endif; ?>
<?php
    if(isset($_GET['failure'])&&($_GET['failure']=='fail')): ?>
    <div class="error" id="error-msg">Fail to add data. Please try again</div>
<?php endif; ?>

<form enctype="application/x-www-form-urlencoded" method="post" class="form-horizontal"
      action="<?php echo JRoute::_('index.php?option=com_astrologin&task=process.userlogin'); ?>">
    <fieldset class="fieldscontent">
        <div class="control-group">
            <div class="control-label2">Enter Username/Email</div>
            <div class="controls2"><input type="text" name="username" id="al_uname" placeholder="Enter your username or email" /></div>
        </div>
        <div class="control-group">
            <div class="control-label2">Enter Password</div>
            <div class="controls2"><input type="password" name="password" id="al_passwd" placeholder="Enter your password" /></div>
        </div>
        <div  class="control-group">
                <div class="control-label2"><label>Remember Me</label></div>
                <div class="controls2"><input id="al_remember" type="checkbox" name="remember" class="inputbox" value="yes"/></div>
        </div>
        <div class="control-group">
            <div class="controls2">
                <button type="submit" name="login" class="btn btn-primary" onclick="javascript:validateLogin();return false;">Login</button>
                <button type="reset" name="cancel" class="btn btn-navbar">Cancel</button>
            </div>
        </div>
    </fieldset>
</form>
<div class="control-group">
    <div class="controls2"><a href="index.php?option=com_astrologin&view=forgot">Forgot Password</a></div>
    <div class="controls2"><a href="index.php?option=com_astrologin&view=astroregister">Register With Us</a></div>
</div>
</body>
</html>
