<?php
defined('_JEXEC') or die();
//print_r($this->data);exit;
$array = array($this->data['fname'],$this->data['gender'],str_replace("\/","-",$this->data['dob']),
              $this->data['tob'],$this->data['pob'],$this->data['lat'],
              $this->data['lon'],$this->data['tmz'], $this->data['dst']);
$array = json_encode($array); 
?>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Horoscope</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li>
              <form method="post"enctype="application/x-www-form-urlencoded" action="<?php echo JRoute::_('index.php?option=com_horoscope&task=lagna.getdetails'); ?>">
              <input type="hidden" name="data" value="<?php echo htmlspecialchars($array); ?>" /><input type="submit" class="navbar-brand navbar-inverse" value="Planet Details" /></form></li>
          <li><form method="post"enctype="application/x-www-form-urlencoded" action="<?php echo JRoute::_('index.php?option=com_horoscope&task=lagna.getascendant'); ?>"><input type="hidden" name="data" value="<?php echo htmlspecialchars($array); ?>" /><input type="submit" class="navbar-brand navbar-inverse" value="Ascendant" /></form></li>
          <li class="active"><a href="#">Moon</a></li>
          <li><form method="post"enctype="application/x-www-form-urlencoded" action="<?php echo JRoute::_('index.php?option=com_horoscope&task=lagna.getnakshatra'); ?>"><input type="hidden" name="data" value="<?php echo htmlspecialchars($array); ?>" /><input type="submit" class="navbar-brand navbar-inverse" value="Nakshatra" /></form></li>
          <li><form method="post"enctype="application/x-www-form-urlencoded" action="<?php echo JRoute::_('index.php?option=com_horoscope&task=lagna.getnavamsha'); ?>"><input type="hidden" name="data" value="<?php echo htmlspecialchars($array); ?>" /><input type="submit" class="navbar-brand navbar-inverse" value="Navamsha" /></form></li>
        </ul>
    </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
<div class="spacer"></div>
<div id="<?php echo $this->data['id']; ?>" class="accordion-id"></div>
<div class="spacer"></div>
<h3>Your Moon Sign is: <?php echo $this->data['moon_sign'] ?></h3>
<?php
echo $this->data['introtext'];

unset($this->data['introtext'],$this->data['id']);
?>