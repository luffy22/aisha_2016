<?php
    error_reporting(0);       // uncomment on server
    defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html>
<html lang="<?php echo $this->lang; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap-theme.min.css" type="text/css" />
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery.min.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/js/bootstrap.min.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/common.js" type="text/javascript" language="javascript"></script>
</head>
<body>
<jdoc:include type="modules" name="topmenu" />
<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner"> <div class="container"> <div class="navbar-header"> <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-navbar" aria-controls="bs-navbar" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a href="../" class="navbar-brand">Bootstrap</a> </div> <nav id="bs-navbar" class="collapse navbar-collapse"> <ul class="nav navbar-nav"> <li> <a href="../getting-started/">Getting started</a> </li> <li class="active"> <a href="../css/">CSS</a> </li> <li> <a href="../components/">Components</a> </li> <li> <a href="../javascript/">JavaScript</a> </li> <li> <a href="../customize/">Customize</a> </li> </ul> <ul class="nav navbar-nav navbar-right"> <li><a href="http://themes.getbootstrap.com" onclick="ga(&quot;send&quot;,&quot;event&quot;,&quot;Navbar&quot;,&quot;Community links&quot;,&quot;Themes&quot;)">Themes</a></li> <li><a href="http://expo.getbootstrap.com" onclick="ga(&quot;send&quot;,&quot;event&quot;,&quot;Navbar&quot;,&quot;Community links&quot;,&quot;Expo&quot;)">Expo</a></li> <li><a href="http://blog.getbootstrap.com" onclick="ga(&quot;send&quot;,&quot;event&quot;,&quot;Navbar&quot;,&quot;Community links&quot;,&quot;Blog&quot;)">Blog</a></li> </ul> </nav> </div> </header>
<div class="bs-docs-header" id="content" tabindex="-1"> <div class="container"> <h1>CSS</h1> <p>Global CSS settings, fundamental HTML elements styled and enhanced with extensible classes, and an advanced grid system.</p> <div id="carbonads-container"><div class="carbonad"><div id="azcarbon"></div><script>var z=document.createElement("script");z.async=!0,z.src="http://engine.carbonads.com/z/32341/azcarbon_2_1_0_HORIZ";var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(z,s);</script></div></div></div></div>
<div class="fluid-container">
<div class="col-xs-12 col-md-8">
    <jdoc:include type="component" />
    <jdoc:include type="modules" name="bottom" />
</div>
<div class="col-xs-6 col-md-4">
    <jdoc:include type="modules" name="sidebar" />
</div> 
</div>
</body>
</html>
