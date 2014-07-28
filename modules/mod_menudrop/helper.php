<?php
defined('_JEXEC') or die("Unable to access");

class ModMenuDropHelper
{
	public function getActive(&$params)
	{
		// Get the active menu else return default menu
		$menu = JFactory::getApplication()->getMenu();
		return $menu->getActive() ? $menu->getActive() : $menu->getDefault();
	}
	public function getBase(&$params)
	{
		if($params->get('base'))
		{
			$base = JFactory::getApplication()->getMenu()->getItem($params->get('base'));
		}
		else
		{
			$base	= false;
		}
		
		if(!$base)
		{
			$base	= self::getActive($params);
		}
                return $base;
	}
	public function getList(&$params)
	{
		$app		= JFactory::getApplication();
		$menu		= $app->getMenu();
		$id             = $menu->getActive()->id;
		
		$base		= self::getBase($params);
		$items   	= $menu->getItems('menutype', $params->get('menutype'));
		$count		= count($items);
		$start   	= (int) $params->get('startLevel');
 		$end     	= (int) $params->get('endLevel'); 
                $path           = $base->tree;
                $showAll = $params->get('showAllChildren');
	?>
<div class="dropdown">
	 <button class="btn btn-primary dropdown-toggle" type="button" id="<?php echo "menu_".$id; ?>" data-toggle="dropdown" href="#"
           onclick="javascript:toggleMenu(<?php echo "menu_".$id; ?>)" data-toggle="dropdown">
    <?php
			echo trim($params->get('showTitle'));
	?>
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu btn-primary">
	<?php
		foreach($items as $i=>$item)
		{
			if (($start && $start > $item->level)
						|| ($end && $item->level > $end)
						|| (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
						|| ($start > 1 && !in_array($item->tree[$start - 2], $path)))
                        {
                            unset($items[$i]);
                            continue;
                        }
                        else
                        {
		?>
				<li class="dropdown" id="menu_<?php echo $item->id; ?>">
					<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
				</li>
		<?php
                        }
		}
	?>
        </ul>
</div>
<?php
        }
}
?>
