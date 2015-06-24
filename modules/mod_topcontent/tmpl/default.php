<?php
defined('_JEXEC') or die;
//echo "<pre>";
//echo print_r($topview);
//echo "</pre>";
?>
<div class="spacer"></div>
<div>
<ul class="nav nav-tabs" role="tablist">
<li class="active" role="presentation"><a href="#tp-1" role="tab" data-toggle="tab">Most Read</a></li>
<li role="presentation"><a href="#tp-2" role="tab" data-toggle="tab">Recent Popular</a></li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
<div class="spacer"></div>
<div id="tp-1" class="tab-pane active" role="tabpanel">
   <div class="panel-group" id="accordion1" role="tablist" aria-multiselectable="true">
<?php
$i=1;
foreach($topview as $data)
{
    $data->slug = $data->article_id.':'.$data->article_alias;
    $data->catslug = $data->catid.':'.$data->cat_alias;
    $data->link = JRoute::_(ContentHelperRoute::getArticleRoute($data->slug, $data->catslug));
?>
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="heading_<?php echo $i; ?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion1" href="#collapse_<?php echo $i; ?>" 
           aria-expanded="true" aria-controls="collapse_<?php echo $i; ?>">
          <?php echo trim($data->title); ?>
        </a>
      </h4>
    </div>
    <div id="collapse_<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?php echo $i; ?>">
      <div class="panel-body">
    <div><strong>Hits: <?php echo $data->hits; ?></strong></div>
    <p>
    <?php

            echo substr(strip_tags(trim($data->introtext)), 1, 1000);
            echo "....";
    ?>
        <a href="<?php echo $data->link; ?>" title="<?php echo $data->title; ?>">Read More</a>
    </p>    
      </div>
    </div>
  </div>
<?php
$i++;
}
?>
</div>
</div>
<div id="tp-2" class="tab-pane" role="tabpanel">
   <div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
<?php
foreach($toprecent as $data)
{
    $data->slug = $data->article_id.':'.$data->article_alias;
    $data->catslug = $data->catid.':'.$data->cat_alias;
    $data->link = JRoute::_(ContentHelperRoute::getArticleRoute($data->slug, $data->catslug));
?>
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="heading_<?php echo $i; ?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?php echo $i; ?>" 
           aria-expanded="true" aria-controls="collapse_<?php echo $i; ?>">
          <?php echo trim($data->title); ?>
        </a>
      </h4>
    </div>
    <div id="collapse_<?php echo $i; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_<?php echo $i; ?>">
      <div class="panel-body">
    <div><strong>Hits: <?php echo $data->hits; ?></strong></div>
    <p>
    <?php

            echo substr(strip_tags(trim($data->introtext)), 1, 1000);
            echo "....";
    ?>
        <a href="<?php echo $data->link; ?>" title="<?php echo $data->title; ?>">Read More</a>
    </p>    
      </div>
    </div>
  </div>
<?php
$i++;
}
?>
</div>
</div>
<!-- End of tabs -->
</div> <!-- class="tab-content" -->
</div>
<div class="spacer"></div>