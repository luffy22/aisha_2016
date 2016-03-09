<?php
    error_reporting(0);       // uncomment on server
    defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" 
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/css/bootstrap-theme.min.css" type="text/css" />
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/jquery.min.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/bootstrap/js/bootstrap.min.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/common.js" type="text/javascript" language="javascript"></script>
</head>
<body>
<jdoc:include type="modules" name="topmenu" /> 
<jdoc:include type="component" />
<jdoc:include type="modules" name="bottom" />
</body>
</html>
