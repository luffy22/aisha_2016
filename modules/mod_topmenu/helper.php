<?php
class modTopMenuHelper
{
    /**
     * Get base menu item.
     *
     * @param   JRegistry  &$params  The module options.
     *
     * @return   object
     *
     * @since	3.0.2
     */
    public function gettopmenu(&$params)
    {
	$app = JFactory::getApplication();
        $menu = $app->getMenu();

        // Get active menu item
        $base       = self::getBase($params);
        $result     = $menu->getItems('menutype', $base->menutype);
?>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
     <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-controls="Home">
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
        foreach($result as $items)
        {
            $url   = JRoute::_($items->link . "&Itemid=" . $items->id);
            if($items->level !== '1')
            {
                continue;
            }
       ?>
            <li class="dropdown navbar-inverse">
          <?php
            if($items->level=="1")
            {
                $children       = $menu->getItems('parent_id',$items->id, false);
                
          ?>
             <a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="<?php echo $url ?>"><?php echo $items->title ?><span class="caret"></span></a>
             <ul class="nav navbar-inverse dropdown-menu">
             <?php
                    foreach($children as $child)
                    {
                        $url   = JRoute::_($child->link . "&Itemid=" . $child->id);
                    ?>
                         <li><a href="<?php echo $url; ?>" title="<?php echo $child->title; ?>"><?php echo $child->title; ?></a></li>
                 <?php
                    }
             ?>
             </ul>
      <?php
            }
      ?>
            </li>
<?php
      }
?>
            </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>
<?php
    }
    public function getChildren($title, $link, $id)
    {
        $app        = JFactory::getApplication();
        $menu       = $app->getMenu()->getItems('parent_id',$id, true);
        $result     = array("title"=>$title, "link"=>$link,"id"=>$id,$menu);
        return $result;      
        
    }
    public static function getBase(&$params)
    {
        // Get base menu item from parameters
        if ($params->get('base'))
        {
                $base = JFactory::getApplication()->getMenu()->getItem($params->get('base'));
        }
        else
        {
                $base = false;
        }

        // Use active menu item if no base found
        if (!$base)
        {
                $base = self::getActive($params);
        }

        return $base;
    }
    public static function getActive(&$params)
    {
        $menu = JFactory::getApplication()->getMenu();

        return $menu->getActive() ? $menu->getActive() : $menu->getDefault();
    }
        
}
