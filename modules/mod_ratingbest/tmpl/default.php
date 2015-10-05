<?php
defined('_JEXEC') or die;
?>
<h3>Most Rated</h3>
<div class="row">
    <div class="col-xs-12 col-md-8"><strong>Title</strong></div>
    <div class="col-md-1"><strong>Rating</strong></div>
</div>
<?php
    foreach($toprating as $data)
    {
        $data->slug = $data->article_id.':'.$data->article_alias;
        $data->catslug = $data->cat_id.':'.$data->cat_alias;
        $data->link = JRoute::_(ContentHelperRoute::getArticleRoute($data->slug, $data->catslug));
?>
<div class="row">   
           <div class="col-xs-12 col-md-8">
            <a href="<?php echo $data->link; ?>"><?php echo $data->title ?></a>
            </div>
           <div class="col-md-1">
            <?php 
                    $rating = (int)$data->rating_sum/(int)$data->rating_count;
                    $rating = round($rating, 1);
                    echo $rating;
            ?>
           </div>
       </div>
<?php
    }
?>


