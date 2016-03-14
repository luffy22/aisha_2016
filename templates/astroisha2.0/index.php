<?php
    //error_reporting(0);       // uncomment on server
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
<jdoc:include type="modules" name="jbanner" />
<div class="fluid-container">
<div class="col-xs-12 col-md-8">
    <div class="breadcrumb">
        <jdoc:include type="modules" name="breadcrumbs" style="none" />
    </div>
    <div class="spacer"></div>
    <jdoc:include type="component" />
    <div class="spacer"></div>
    <jdoc:include type="message" />
    <div class="spacer"></div>
    <jdoc:include type="modules" name="relatedarticles" style="none" />
    <div class="spacer"></div>
</div>
<div class="col-xs-6 col-md-4">
    <jdoc:include type="modules" name="sidebar" />
</div>
</div>
</body>
</html>
