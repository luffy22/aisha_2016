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

        foreach($items as $i=>$item)
        {
                if (($start && $start > $item->level)
                                        || ($end && $item->level > $end)
                                        || (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
                                        || ($start > 1 && !in_array($item->tree[$start - 2], $path)))
                {
                    unset($item[$i]);
                    continue;
                }

        }
        return $items;
    }
    public function getLink(&$params)
    {
        $app		= JFactory::getApplication();
        $menu		= $app->getMenu();
        $id             = $menu->getActive()->id;
    }
    public function getMenuItemId()
    {
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
    }
}
?>
