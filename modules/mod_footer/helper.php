<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_archive
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_articles_archive
 *
 * @package     Joomla.Site
 * @subpackage  mod_articles_archive
 * @since       1.5
 */
class ModFooterHelper
{
    public function showFooter()
    {
?>
        <div class="footer-text">
            <div class="custom">
                <h3><?php echo date("Y"); ?>&nbsp;Astro Isha</h3>
            </div>
        </div>
<?php
    }
}
