<?php 
defined('_JEXEC') or die;
?>
<div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" id="<?php echo "menu_".$id; ?>" data-toggle="dropdown" href="#"
           onclick="javascript:toggleMenu(<?php echo "menu_".$base->id;; ?>)" data-toggle="dropdown">
<?php
        echo trim($params->get('showTitle'));
?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu btn-primary">
<?php
        foreach($list as $i=>$item)
        {
            $url   = JRoute::_($item->link . "&Itemid=" . $item->id); // use JRoute to make link from object
        ?>  
            <li class="dropdown" id="menu_<?php echo $item->id; ?>">
                <a href="<?php echo $url; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
            </li>
        <?php
        }
?>
    </ul>
</div>