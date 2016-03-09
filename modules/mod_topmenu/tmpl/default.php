<?php
defined('_JEXEC') or die;
//print_r($topmenu);exit;
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="<?php echo JUri::base(); ?>" class="navbar-brand">Astro Isha</a>
    </div>
    <div class="navbar-collapse collapse" id="navbar">
      <ul class="nav navbar-nav">
      <?php
        foreach($topmenu as $items)
        {
            if($items->level=="1")
            {
                continue;
            }
      ?>
        <li class="dropdown">
          <?php
            if($items->level=="2")
            {
          ?>
          <a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo $items->title ?><span class="caret"></span></a>
          <?php
            }
          ?>
          <ul class="dropdown-menu">
        <?php
            if($items->level=="2")
            {
                echo "<li>".$items->title."</li>";
            }
      ?>
          </ul>
        </li>
        <?php
        }
        ?>
     </ul>
    </div><!--/.nav-collapse -->
</div>
</nav>


