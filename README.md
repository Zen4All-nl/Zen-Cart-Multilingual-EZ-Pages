Contribution:  Multi-Language Support for EZ-Pages
Version: 1.7
Designed for: Zen Cart v1.5.0 + (merged w/ 1.5.5f files)
Designed by: Neville Kerr (forum ID: bunyip)
Updated by: Zen4All (forum ID: design75)
License: under the GPL - See attached License for info.

========================================================

WHAT DOES THIS CONTRIBUTION DO?

This contribution provides multi-language support for the EZ-Pages module that was included in the Zen Cart v1.3.0 release. The standard version does not support more than one language (even though there is a language_id field in the database table).

After installing this module you will be able to enter multi-lingual page/link titles and page content for each EZ-Page from a single admin page (multiple input fields will be present, allowing input for each language just as there is for categories and products).

This latest update adds a new page to the Admin panel that simplifies the install/uninstall process and ensures a smooth transition multi-language support. It also provides a fix/repair facility for the new database table to resolve any problems that may have occurred as a result of incomplete installation of the initial release version.

IF YOU ARE UPGRADING FROM AN EARLIER VERSION, YOU WILL NEED TO UPLOAD THE NEW FILES, THEN USE THE UNINSTALL FUNCTION, AND THEN THE INSTALL FUNCTION from Admin > Tools > Install/Uninstall Multi-Language EZ-Pages. This is because of a change in the structure and indexing of the ezpages_content table


========================================================

INSTALLATION

Step 1.  Unzip the archive file

Step 2.  replace the following two CUSTOM folder with your own folder(template) name
  includes\modules\YOUR_TEMPLATE
  includes\modules\sideboxes\YUOR_TEMPLATE

Step 3.  Make a copy of the included file
  admin/includes/languages/english/extra_definitions/ezpages_multilanguage_defines.php
and save it as
  YOUR_ADMIN/includes/languages/YOUR_LANGUAGE/extra_definitions/ezpages_multilanguage_defines.php
then edit the two define statements in the file to suit your language.

Step 4. Transfer all files into your Zen Cart folder. Path names have been included in the archive.

Step 5.  From your Admin panel, go to Tools > Install/Uninstall Multi-Language EZ-Pages. Click the Install link. This will run a script that creates the new database table and populates it from existing data in your current ezpages table. You MUST complete this installation step or the module will not function.

Step 6.  Go to Admin > Tools > EZ Pages and add the titles/content for your additional languages.


========================================================

REMOVAL/UNINSTALLATION

Step 1.  Go to Admin > Tools > Install/Uninstall Multi-Language EZ-Pages and click the link for Uninstall. This transfers page titles and html content for your default language back into the regular ezpages table, and then drops the ezpages_content table. After this step you MUST replace the file admin/ezpages.php with an original file from your Zen Cart installation package or your ezpages admin page WILL NOT FUNCTION.


========================================================

TROUBLESHOOTING

If you installed an earlier version of this contribution, you may experience problems with links not displaying for some languages. That's likely to be caused by no entry for that page/language combination in the ezpages_content table of the database. Go to Admin > Tools > Install/Uninstall Multi-Language EZ-Pages and click the Fix/Repair link: that will ensure that there is an entry in the database table for every page/language combination. The page will tell you how many pages you need to update translations for after the table is fixed.


========================================================

History:

2018-03-19 - v1.7 Multiple updates, zencartcode updated to 1.5.5f/1.5.6
2012-09-26 - minor bugfix for filename and CUSTOM folder
2012-09-21 - updated for Zen Cart v1.5.1
2006-06-09 - v1.0.1 release - included install/repair/uninstall capability
2006-06-02 - minor bugfix in error checking section
2006-05-31 - v1.0 Initial Release

========================================================

NEW FILES:

These files are new and don't overwrite/over-ride any existing files:

includes/extra_datafiles/ezpages_multilanguage_database_names.php
admin/includes/extra_datafiles/ezpages_multilanguage_database_names.php
admin/includes/extra_datafiles/ezpages_multilanguage_filenames.php
admin/includes/functions/extra_functions/reg_multi_ezpages.php
admin/includes/languages/english/extra_definitions/ezpages_multilanguage_defines.php
admin/ezpages_install.php

========================================================

FILES TO OVER-RIDE

The following files go into your custom over-ride folders (replace CUSTOM with your own folder name):

includes/modules/YOUR_TEMPLATE/ezpages_bar_footer.php
includes/modules/YOUR_TEMPLATE/ezpages_bar_header.php
includes/modules/sideboxes/YOUR_TEMPLATE/ezpages.php


========================================================

File Modifications:

These two files overwrite existing files that don't have an over-ride capability: make backup copies just in case, and keep them in mind for when you are next upgrading your Zen Cart...

includes/modules/pages/page/header_php.php
admin/ezpages.php
