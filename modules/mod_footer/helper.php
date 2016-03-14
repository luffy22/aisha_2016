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
<footer class="footer">
      <div class="container">
        <p class="footer-text"><a href="<?php echo JUri::base(); ?>" title="Navigate to Home Page"><?php echo date("Y"); ?> Astro Isha</a></p>
      </div>
    </footer>
<?php
    }
}
