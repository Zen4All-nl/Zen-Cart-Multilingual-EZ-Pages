<?php
/*
 $Id: reg_multi_ezpages.php, v 1.4 2011/11/24  $

  By Jack 9.21.2012

  Powered by Zen-Cart (www.zen-cart.com)
  Portions Copyright (c) 2006 The Zen Cart Team

  Released under the GNU General Public License
  available at www.zen-cart.com/license/2_0.txt
  or see "license.txt" in the downloaded zip

  DESCRIPTION: ezpages Multi-languages support
*/

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

if (function_exists('zen_register_admin_page')) {
    if (!zen_page_key_exists('multi_ezpages')) {
        // ezpages Multi-languages support
        zen_register_admin_page('multi_ezpages', 'BOX_MULTI_EZPAGES_INSTALL','FILENAME_MULTI_EZPAGES_INSTALL', '', 'tools', 'Y', 70);
    }
}
?>