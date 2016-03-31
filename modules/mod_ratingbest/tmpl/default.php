<?php
defined('_JEXEC') or die;
?>
<div class="table-responsive">
<h3>Most Rated</h3>
<table class="table table-striped">
    <tr>
        <th>Title</th>
        <th>Rating</th>
    </tr>
<?php
    foreach($toprating as $data)
    {
        $data->slug = $data->article_id.':'.$data->article_alias;
        $data->catslug = $data->cat_id.':'.$data->cat_alias;
        $data->link = JRoute::_(ContentHelperRoute::getArticleRoute($data->slug, $data->catslug));
?>
    <tr>
            <td><a href="<?php echo $data->link; ?>"><?php echo $data->title ?></a></td>
            <?php 
                    $rating = (int)$data->rating_sum/(int)$data->rating_count;
                    $rating = round($rating, 1);
            ?>
            <td><?php echo $rating; ?></td>

    </tr>
<?php
    }
?>
</table>
</div>
