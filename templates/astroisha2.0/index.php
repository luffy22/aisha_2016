<?php
    error_reporting(0);       // uncomment on server
    defined( '_JEXEC' ) or die( 'Restricted access' );
    $app = JFactory::getApplication();$menu = $app->getMenu();
?>
<!DOCTYPE html>
<html lang="<?php echo $this->lang; ?>">
<?php
$doc = JFactory::getDocument(); $head_data = $doc->getHeadData();
?>
<title><?php echo $head_data['title']; ?></title>
<meta name="description" content="<?php echo $head_data['description']; ?>">
<meta name="keywords" content="<?php echo $head_data['metaTags']['standard']['keywords']; ?>">
<meta name="robots" content="index, follow" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="msvalidate.01" content="E689BB58897C0A89BDC88E5DF8800B2F" />
<link rel="shortcut icon" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" type="image/x-icon" />
<link rel="icon" href="<?php echo $this->baseurl ?>/logo.png" type="image/x-icon">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap-theme.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/jquery_ui/jquery-ui.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/jquery_ui/jquery-ui.structure.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/jquery_ui/jquery-ui.theme.min.css" type="text/css" />
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery.min.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/js/bootstrap.min.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/jquery_ui/jquery-ui.min.js" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/common.js" type="text/javascript" language="javascript"></script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '220390744824296',
      xfbml      : true,
      version    : 'v2.4'
    });
  };
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<script>
  (function() {
    var cx = '006812877761787834600:vf6wtd5lcuk';
    var gcse = document.createElement('script');
    gcse.type = 'text/javascript';
    gcse.async = true;
    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
        '//cse.google.com/cse.js?cx=' + cx;
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(gcse, s);
  })();
</script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
</head>
<body>
<?php
 // Get option and view
$option = JRequest::getVar('option');
$view = JRequest::getVar('view');
// Make sure it is a single article
if ($option == 'com_content' && $view == 'article'):
  $id = JRequest::getInt('id');
?>
<div id="<?php echo $id; ?>" class="accordion-id"></div>
<?php
endif;
include_once (JPATH_ROOT.DS.'analyticstracking.php');
?>
<div id="fb-root"></div>
<div id="loadergif" class="loader"><img src="<?php echo $this->baseurl ?>/images/loader.gif" /></div>
<jdoc:include type="modules" name="topmenu" />
<jdoc:include type="modules" name="jbanner" />
<div class="container-fluid">
    <div class="row">
    <div class="col-md-8">
    <gcse:search></gcse:search>
    <div class="spacer"></div>
    <div class="breadcrumb">
        <jdoc:include type="modules" name="breadcrumbs" style="none" />
    </div>
    <jdoc:include type="modules" name="articleslider" style="none" />
    <div class="spacer"></div>
    <jdoc:include type="component" />
    <div class="spacer"></div>
    <jdoc:include type="message" />
    <div class="spacer"></div>
    <jdoc:include type="modules" name="relatedarticles" style="none" />
    <div class="spacer"></div>
    </div>
    <div class="col-md-4">
        <jdoc:include type="modules" name="userlogin" style="none" />
        <div class="spacer"></div>
        <jdoc:include type="modules" name="sidebar" />
        <div class="spacer"></div>
        <jdoc:include type="modules" name="socialplugins" />
    </div>
    </div>
</div>
<jdoc:include type="modules" name="footer2" style="none" />
<jdoc:include type="modules" name="footer" style="none" />
<script>$(function() {
    $( "#datepicker" ).datepicker({ yearRange: "1950:2016",changeMonth: true,
      changeYear: true, dateFormat: "yy/mm/dd"  });
  });</script>

</body>
</html>
