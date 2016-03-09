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
        $base = self::getBase($params);
        $items   = $menu->getItems('menutype', $base->menutype);
        
        return $items;
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
    
        
}
