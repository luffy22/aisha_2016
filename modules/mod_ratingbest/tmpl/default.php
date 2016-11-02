<?php
defined('_JEXEC') or die;
$app = JFactory::getApplication();
$menu = $app->getMenu();
if ($menu->getActive() == $menu->getDefault()) {
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
<?php
}
else
{
?>
<div class="table-responsive">
<h3>Recent Articles</h3>
<table class="table table-striped">
    <tr>
        <th>Title</th>
        <th>Category</th>
    </tr>
<?php
    foreach($recent as $data)
    {  
        $data['slug'] = $data['article_id'].':'.$data['article_alias'];
        $data['catslug'] = $data['cat_id'].':'.$data['cat_alias'];
        $data['link'] = JRoute::_(ContentHelperRoute::getArticleRoute($data['slug'], $data['catslug']));
        $data['catlink']    = JRoute::_(ContentHelperRoute::getCategoryRoute($data['cat_id'], $data['language']));
?>
    <tr>
        <td><a href="<?php echo $data['link']; ?>" title="<?php echo $data['title'];  ?> Article"><?php echo $data['title']; ?></a></td>
        <td><a href="<?php echo $data['catlink']; ?>" title="<?php echo $data['cat_title'] ?> Category"><?php echo $data['cat_title'] ?></a></td>
    </tr>
<?php
    }
?>
</table>
</div>
<?php
}
?>