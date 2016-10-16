<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
//  $Id: ezpages_install.php 2006-06-09 bunyip $
//  install/repair/uninstall utility for multi-language EZ-Pages contribution
//

  require('includes/application_top.php');

  $language_query = $db->Execute("select languages_id, name from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_LANGUAGE . "'");

  $primary_language_id = $language_query->fields['languages_id'];
  $primary_language_name = $language_query->fields['name'];


$action = (isset($_GET['action']) ? $_GET['action'] : '');

if ($action == 'install') {

$db->Execute("DROP TABLE IF EXISTS " . TABLE_EZPAGES_TEXT);
$db->Execute("CREATE TABLE IF NOT EXISTS " . TABLE_EZPAGES_TEXT . " (
  pc_id int(11) NOT NULL auto_increment,
  pages_id int(11) NOT NULL default '0',
  languages_id int(11) NOT NULL default '1',
  pages_title varchar(64) NOT NULL default '',
  pages_html_text text,
  PRIMARY KEY  (pc_id),
  KEY idx_ezpages_content (pages_id,languages_id)
);");

}

if ($action == 'repair' or $action == 'install') {

  $pages = $db->Execute("select pages_id, pages_title, pages_html_text, alt_url, alt_url_external, languages_id from " . TABLE_EZPAGES );

  while (!$pages->EOF) {

  $languages = zen_get_languages();
    for ($i=0, $n = sizeof($languages); $i<$n; $i++) {
    if($sql_data_array) unset($sql_data_array);
    if($sql_update_array) unset($sql_update_array);
    if($pages_html_text) unset($pages_html_text);
    if($check_query) unset($check_query);

      $sql = "select * from " . TABLE_EZPAGES_TEXT . "
            where pages_id = " . (int)$pages->fields['pages_id']  . "
        and languages_id = " . (int)$languages[$i]['id'];

    $check_query = $db->Execute($sql);

      if ($check_query->RecordCount() == 0) {

    if (zen_not_null($pages->fields['alt_url']) or zen_not_null($pages->fields['alt_url_external'])) {
      $pages_html_text = NULL;
      } else {
      $pages_html_text = ($languages[$i]['id'] == $pages->fields['languages_id'] ? '' : '(translate me) ') . $pages->fields['pages_html_text'];
      }

    $sql_data_array = array('pages_title' => ($languages[$i]['id'] == $pages->fields['languages_id'] ? '' : '(translate me) ') . $pages->fields['pages_title'],
                                'pages_html_text' => $pages_html_text,
                            'languages_id' => (int)$languages[$i]['id'],
                'pages_id' => (int)$pages->fields['pages_id']);

         zen_db_perform(TABLE_EZPAGES_TEXT, $sql_data_array);

     } else {

    $pages_title = ($languages[$i]['id'] == $pages->fields['languages_id'] ? '' : '(translate me) ') . $pages->fields['pages_title'];

    $pages_title = (zen_not_null($check_query->fields['pages_title']) ? $check_query->fields['pages_title'] : $pages_title );

    if (zen_not_null($pages->fields['alt_url']) or zen_not_null($pages->fields['alt_url_external'])) {
      $pages_html_text = NULL;
      } else {
      $pages_html_text = ($languages[$i]['id'] == $pages->fields['languages_id'] ? '' : '(translate me) ') . $pages->fields['pages_html_text'];
      $pages_html_text = (zen_not_null($check_query->fields['pages_html_text']) ? $check_query->fields['pages_html_text'] : $pages_html_text);
      }

     $sql_update_array = array('pages_title' => $pages_title,
                                'pages_html_text' => $pages_html_text);

     zen_db_perform(TABLE_EZPAGES_TEXT, $sql_update_array, 'update', "pages_id = '" . (int)$pages->fields['pages_id'] . "' and languages_id = '" . (int)$languages[$i]['id'] . "'");

     } // end  if ($count_query->RecordCount() == 0)

  } // end for ($i=0, $n = sizeof($languages); $i<$n; $i++)

    $pages->MoveNext();
  } // end while (!$pages->EOF)

  $update_query = $db->Execute("select * from " . TABLE_EZPAGES_TEXT . "
                                where pages_title like '%translate me%'
                or pages_html_text like '%translate me%'");

  $records = $update_query->RecordCount();

} // end if ($action == 'repair' || $action == 'install')

if ($action == 'uninstall') {


  $pages_query = $db->Execute("select pages_id, pages_title, pages_html_text from " . TABLE_EZPAGES_TEXT . "
                               where languages_id = '" . (int)$primary_language_id . "'");

  while(!$pages_query->EOF) {

  $sql_update_array = array('pages_title' => $pages_query->fields['pages_title'],
                            'pages_html_text' => $pages_query->fields['pages_html_text']);

  zen_db_perform(TABLE_EZPAGES, $sql_update_array, 'update', 'pages_id = ' . $pages_query->fields['pages_id']);

  $pages_query->MoveNext();

  } // end while(!$pages_query->EOF)

  $drop_table = $db->Execute("drop table " . TABLE_EZPAGES_TEXT);

} // end if ($action == 'uninstall')

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  if (typeof _editor_url == "string") HTMLArea.replaceAll();
  }
  // -->
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="init()">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="4" cellpadding="4">
<!-- body_text //-->
  <tr>
    <td class="pageHeading">Multi-Language EZ-Pages - Installation/Maintenance
  </td>
  </tr>
<?php
   if ($action == 'repair') {
?>
  <tr>
    <td class="main">Multi-language ezpages content table repaired.<br />
<?php
     if ($records > 0) {

     echo $records . ' pages now need translations to be fixed from Admin > Tools > EZ-Pages.<br />Those that need to be fixed have "(translate me)" at the start of the page title and/or the html text.<br />(Please check the EZ-Pages page with each of your languages selected or you may miss some entries that need to be fixed)<br /><br /><br /><br />';

     } else {

     echo 'no entries needed to be fixed<br /><br />';

     } // end if ($records > 0)
?>
    </td>
  </tr>
<?php
   } // end if ($action == 'repair')

   if ($action == 'install') {
?>
  <tr>
    <td class="main">Multi-language EZ-Pages is now installed.<br />
  You will need to edit every page to ensure the correct titles/content are set for each language.<br /><br /><br /><br />
    </td>
  </tr>

<?php
   } // end if ($action == 'install')

   if ($action == 'uninstall') {
?>
  <tr>
    <td class="main">Multi-Language EZ-Pages has been uninstalled.<br />
  Your page titles and content have been set to those used for your default language.<br />
  <span class="alert">Unless you intend to re-install Multi-Language EZ-Pages, now you MUST replace the file admin/ezpages.php on your server with the original file (from your Zen Cart installation package) or your EZ-Pages admin page WILL NOT FUNCTION</span><br /><br /><br /><br />
    </td>
  </tr>

<?php
   } // end if ($action == 'uninstall')
?>
<?php
if ($action != 'install') {
?>
  <tr>
    <td class="main"><strong>Install Multi-Language EZ-Pages</strong> This action will drop the
  ezpages_content table from the database if it exists, recreate it and populate it from entries in the ezpages table. <br /><br />
  <span class="alert">Caution: if you already installed Multi-Language EZ-Pages and added pages and/or translations to existing pages, you should use the UNINSTALL link first or you will lose some of your data!</span><br /><br />
  <div align="center"><a href="ezpages_install.php?action=install" title="Install">Install Multi-Language EZ-Pages</a></div><br /><br />
  </td>
  </tr>
<?php
} // end if (!$action == 'install')
?>
  <tr>
    <td class="main"><strong>Fix/Repair Multi-Language EZ-Pages database table</strong> If you are experiencing problems
  with EZ-Pages links not displaying in the store in some languages, try using this link. It is likely that there are some blank entries for page titles and/or html content for the language(s) that the links are not appearing in. The Fix/Repair link will fix this, although you will have to translate the content of those 'missing' links again.<br /><br />
  <div align="center"><a href="ezpages_install.php?action=repair" title="Repair">Fix/Repair Multi-Language EZ-Pages database table</a></div><br /><br />
  </td>
  </tr>
<?php
if ($action != 'uninstall') {
?>
  <tr>
    <td class="main"><strong>Remove/Uninstall Multi-Language EZ-Pages</strong> This link will populate the page_title and page_html_content fields of the ezpages table with corresponding entries from the ezpages_content table for your default language (<?php echo $primary_language_name; ?>), and then remove the ezpages_content table. <br /><br />
  <span class="alert">After you uninstall, you MUST replace the file admin/ezpages.php on your server with the original file (from your Zen Cart installation package) or your EZ-Pages admin page WILL NOT FUNCTION</span> <br /><br />
  <div align="center"><a href="ezpages_install.php?action=uninstall" title="Uninstall">Remove/Uninstall Multi-Language EZ-Pages</a></div>
  </td>
  </tr>
<?php
}// if (!$action == 'uninstall')
?>
<!-- body_text_eof //-->
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>