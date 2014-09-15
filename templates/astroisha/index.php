<?php
    error_reporting(0);
    defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" 
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="msvalidate.01" content="E689BB58897C0A89BDC88E5DF8800B2F" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap-theme.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/jquery-ui/jquery-ui.min.css" type="text/css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css"/>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="body">
<div id="fb-root"></div>
    <div id="loadergif" class="loader"><img src="<?php echo $this->baseurl ?>/images/loader.gif" /></div>
<div class="main-header">
    <div class="header-logo">
        <a href="index.php"><img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/jv_logo.png" alt="Astro Isha" title="Navigate to Home Page" class="image-logo" /></a>
    </div>
    <div class="login-module" id="login-cred">
        <jdoc:include type="modules" name="userlogin" style="none" />
    </div>
    <div class="header-menu visible-desktop">
        <div class="home_icon">
            <a href="index.php"><img src="<?php echo $this->baseurl; ?>/images/home_logo.png" alt="Astro Isha" title="Navigate to Home Page" width="35px" height="35px" /></a>
        </div>
        <div class="navigation_menu">
            <ul class="nav nav-pills visible-md visible-lg">
                <li class="dropdown">
                    <jdoc:include type="modules" name="menu1" style="none" />
                </li>
                <li class="dropdown">
                    <jdoc:include type="modules" name="menu2" style="none" />
                </li>
                <li class="dropdown">
                    <jdoc:include type="modules" name="menu3" style="none" />
                </li>
                <li class="dropdown">
                    <jdoc:include type="modules" name="menu4" style="none" />
                </li>
                <li class="dropdown">
                    <jdoc:include type="modules" name="menu" style="none" />
                </li>
            </ul>
        </div>
     </div>
</div>
<div class="container-fluid">
    <div class="row">
    <div class="col-md-3">
        <div class="spacer"></div>
        <jdoc:include type="modules" name="sidebar" style="none" />
    </div>
    <div class="col-md-6">
        <div class="spacer"></div>
        <div class="breadcrumb">
            <jdoc:include type="modules" name="breadcrumbs" style="none" />
        </div>
        <div class="container-main">
            <jdoc:include type="modules" name="articleslider" style="none" />
            <div class="spacer"></div>
            <jdoc:include type="modules" name="articleslider2" style="none" />
            <jdoc:include type="component" />
            <jdoc:include type="message" />
        </div>
    </div>
    <div class="col-md-3 hidden-xs hidden-sm">
        <div class="spacer"></div>
        <jdoc:include type="modules" name="fblikeplugin" style="none" />
        <div class="spacer"></div>
        <jdoc:include type="modules" name="socialplugins" style="none" />
        <div class="spacer"></div>
        <jdoc:include type="modules" name="paypaldonate" style="none" />
    </div>
    </div>
</div>
<div class="footer">
    <jdoc:include type="modules" name="footer" style="none" />
</div>
<!--Scripts at the bottom of the Page -->
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery.min.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/js/bootstrap.min.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/common.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/jquery-ui/jquery-ui.min.js" language="javascript"></script>
<script>
  $(function() {
    $( "#datepicker" ).datepicker({ yearRange: "1900:2099" });
  });
</script>
<script>
  (function() {
    var cx = '006812877761787834600:kranbsbb5p8';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//www.google.com/cse/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=220390744824296&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- Place this tag after the last widget tag. -->
<script type="text/javascript">
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; 
po.src = 'https://apis.google.com/js/platform.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49809214-1', 'astroisha.com');
  ga('send', 'pageview');

</script>
</body>
</html>
