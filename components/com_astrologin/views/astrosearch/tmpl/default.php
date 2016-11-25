<?php
defined('_JEXEC') or die('Restricted access');
$astro      = $this->astro;
//print_r($astro);
?>
<div class="spacer"></div>
<?php
     foreach($astro as $data)
     {
?>
<div class="panel panel-primary" id="<?php echo "astro_".$data->number ?>">
    <div class="panel-heading"><?php echo $data->name; ?></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3"><img src="<?php echo JURI::base() ?>images/profiles/<?php echo trim($data->img_1_id); ?>" title="<?php echo $this->img_1 ?>" class="img-responsive" /></div>
            <div class="col-md-5"><strong>Location:</strong> <?php echo $data->city.", ",$data->state.", ".$data->country;  ?><br/>
              <strong>Little About Me:</strong><br/> <?php echo $data->info; ?>
              <a class="btn btn-primary" href="<?php echo JURI::base(); ?>astrosearch?astro=AS00<?php echo $data->number; ?>00">Get Full Details</a>
              <?php
                 if($data->membership == "Paid")
                 {
              ?>
              <button class="btn btn-success">Get Online Consultation</button>
              <?php
                 }
              ?>
            </div>
        </div>
    </div>
</div>
<?php
     }
?>
<div class="spacer"></div>

