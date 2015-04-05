<?php
//$messageStack->add('Ty Package Tracker v3.1.4 install started','success');

    $typt_menu_title = 'Ty Package Tracker';
    $typt_menu_text = 'Settings for Ty Package Tracker Features';

    /* find if Ty Package Tracker Configuration Group Exists */
    $sql = "SELECT * FROM ".TABLE_CONFIGURATION_GROUP." WHERE configuration_group_title = '".$typt_menu_title."'";
    $original_config = $db->Execute($sql);

    if($original_config->RecordCount())
    {
        // if exists updating the existing Ty Package Tracker configuration group entry
        $sql = "UPDATE ".TABLE_CONFIGURATION_GROUP." SET 
                configuration_group_description = '".$typt_menu_text."' 
                WHERE configuration_group_title = '".typt_menu_title."'";
        $db->Execute($sql);
        $sort = $original_config->fields['sort_order'];

    }else{
        /* Find max sort order in the configuration group table -- add 2 to this value to create the Ty Package Tracker configuration group ID */
        $sql = "SELECT (MAX(sort_order)+2) as sort FROM ".TABLE_CONFIGURATION_GROUP;
        $result = $db->Execute($sql);
        $sort = $result->fields['sort'];

        /* Create Ty Package Tracker configuration group */
        $sql = "INSERT INTO ".TABLE_CONFIGURATION_GROUP." (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (NULL, '".$typt_menu_title."', '".$typt_menu_text."', ".$sort.", '1')";
        $db->Execute($sql);
   }

    /* Find configuration group ID of Ty Package Tracker */
    $sql = "SELECT configuration_group_id FROM ".TABLE_CONFIGURATION_GROUP." WHERE configuration_group_title='".$typt_menu_title."' LIMIT 1";
    $result = $db->Execute($sql);
        $typt_configuration_id = $result->fields['configuration_group_id'];

    /* Remove Ty Package Tracker items from the configuration table */
    $sql = "DELETE FROM ".TABLE_CONFIGURATION." WHERE configuration_group_id ='".$typt_configuration_id."'";
        $db->Execute($sql);

//-- ADD VALUES TO EDIT ORDERS CONFIGURATION GROUP (Admin > Configuration > Ty Package Tracker) --
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 1 Status', 'CARRIER_STATUS_1', 'True', 'Enable Tracking for Carrier 1<br /><br />Set to false if you do NOT want Carrier 1 to be displayed on Admin and Customer page.', '".$typt_configuration_id."', 90, now(), now(), NULL, 'zen_cfg_select_option(array(\'True\', \'False\'), ')";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 1 Name', 'CARRIER_NAME_1', 'FedEx', 'Enter name of Carrier 1 <br /> <br /><strong>Example:</strong> FedEx, UPS, Canada Post, etc...<br />(default: FedEx)', '".$typt_configuration_id."', 95,  now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 1 Tracking Link', 'CARRIER_LINK_1', 'http://www.fedex.com/Tracking?action=track&tracknumbers=', 'Enter the tracking link of Carrier 1<br /> <br /><strong>Example:</strong> http://www.fedex.com/Tracking?action=track&tracknumbers=', '".$typt_configuration_id."', 100, now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 2 Status', 'CARRIER_STATUS_2', 'True', 'Enable Tracking for Carrier 2<br /><br />Set to false if you do NOT want Carrier 2 to be displayed on Admin and Customer page.', '".$typt_configuration_id."', 105, now(), now(), NULL, 'zen_cfg_select_option(array(\'True\', \'False\'), ')";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 2 Name', 'CARRIER_NAME_2', 'UPS', 'Enter name of Carrier 2 <br /> <br /><strong>Example:</strong> FedEx, UPS, Canada Post, etc...<br />(default: UPS)', '".$typt_configuration_id."', 110,  now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 2 Tracking Link', 'CARRIER_LINK_2', 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=en_US&InquiryNumber1=', 'Enter the tracking link of Carrier 2<br /> <br /><strong>Example:</strong> http://www.fedex.com/Tracking?action=track&tracknumbers=', '".$typt_configuration_id."', 115, now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 3 Status', 'CARRIER_STATUS_3', 'True', 'Enable Tracking for Carrier 3<br /><br />Set to false if you do NOT want Carrier 3 to be displayed on Admin and Customer page.', '".$typt_configuration_id."', 120, now(), now(), NULL, 'zen_cfg_select_option(array(\'True\', \'False\'), ')";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 3 Name', 'CARRIER_NAME_3', 'USPS', 'Enter name of Carrier 3 <br /> <br /><strong>Example:</strong> FedEx, UPS, Canada Post, etc...<br />(default: USPS)', '".$typt_configuration_id."', 125,  now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 3 Tracking Link', 'CARRIER_LINK_3', 'https://tools.usps.com/go/TrackConfirmAction!input.action?tLabels=', 'Enter the tracking link of Carrier 3<br /> <br /><strong>Example:</strong> http://www.fedex.com/Tracking?action=track&tracknumbers=', '".$typt_configuration_id."', 130, now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 4 Status', 'CARRIER_STATUS_4', 'False', 'Enable Tracking for Carrier 4<br /><br />Set to false if you do NOT want Carrier 4 to be displayed on Admin and Customer page.', '".$typt_configuration_id."', 140, now(), now(), NULL, 'zen_cfg_select_option(array(\'True\', \'False\'), ')";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 4 Name', 'CARRIER_NAME_4', '', 'Enter name of Carrier 4 <br /> <br /><strong>Example:</strong> FedEx, UPS, Canada Post, etc...<br />(default: blank)', '".$typt_configuration_id."', 145, now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 4 Tracking Link', 'CARRIER_LINK_4', '', 'Enter the tracking link of Carrier 4<br /> <br /><strong>Example:</strong> http://www.fedex.com/Tracking?action=track&tracknumbers=', '".$typt_configuration_id."', 150,  now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 5 Status', 'CARRIER_STATUS_5', 'False', 'Enable Tracking for Carrier 5<br /><br />Set to false if you do NOT want Carrier 5 to be displayed on Admin and Customer page.', '".$typt_configuration_id."', 155, now(), now(), NULL, 'zen_cfg_select_option(array(\'True\', \'False\'), ')";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 5 Name', 'CARRIER_NAME_5', '', 'Enter name of Carrier 5 <br /> <br /><strong>Example:</strong> FedEx, UPS, Canada Post, etc...<br />(default: blank)', '".$typt_configuration_id."', 160,  now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Package Tracking - Carrier 5 Tracking Link', 'CARRIER_LINK_5', '', 'Enter the tracking link of Carrier 5<br /> <br /><strong>Example:</strong> http://www.fedex.com/Tracking?action=track&tracknumbers=', '".$typt_configuration_id."', 165,  now(), now(), NULL, NULL)";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Max display for Track Order sidebox', 'MAX_DISPLAY_PRODUCTS_IN_TRACK_ORDERS_BOX', '3', 'The maximum number of orders to display on the Track Order sidebox ', '".$typt_configuration_id."', '170',  now(), now(), NULL, NULL)";                               
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Ty Package Tracker Module Switch', 'TY_TRACKER', 'False', 'If you have the either Edit Orders v4.x or Super Orders v4.x installed, set this option to TRUE so that the Ty Package Tracker fields will display in Edit Orders or Super Orders<br><br><strong><font color=red>YOU MUST HAVE EDIT ORDERS v4.x OR SUPER ORDERS v4.x INSTALLED TO USE THIS FEATURE!!</font></strong><br><br>\(Activating this flag without the required mod\(s\) installed <strong>WILL CAUSE ERRORS IN YOUR STORE!!!!</strong>\)', '".$typt_configuration_id."', 175, now(), now(), NULL, 'zen_cfg_select_option(array(\'True\', \'False\'), ')";
    $db->Execute($sql);
	$sql = "INSERT INTO ".TABLE_CONFIGURATION." (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES (NULL, 'Current Ty Package Tracker Version', 'TY_TRACKER_VERSION ', '3.1.4', 'Version number (DO NOT MODIFY THIS VALUE)', '".$typt_configuration_id."', 0,  now(), now(), NULL, 'zen_cfg_select_option(array(''3.1.4''), ')";
    $db->Execute($sql);

// add columns to order table
    //check if track_id1 column exists
    $sql ="SHOW COLUMNS FROM ".TABLE_ORDERS_STATUS_HISTORY." LIKE '%track_id1%'";
    $result = $db->Execute($sql);
    if(!$result->RecordCount())
    {
        $sql = "ALTER TABLE ".TABLE_ORDERS_STATUS_HISTORY." ADD track_id1 TEXT default NULL";
        $db->Execute($sql);
    }

    //check if track_id2 column exists
    $sql ="SHOW COLUMNS FROM ".TABLE_ORDERS_STATUS_HISTORY." LIKE '%track_id2%'";
    $result = $db->Execute($sql);
    if(!$result->RecordCount())
    {
        $sql = "ALTER TABLE ".TABLE_ORDERS_STATUS_HISTORY." ADD track_id2 TEXT default NULL";
        $db->Execute($sql);
    }
    //check if track_id3 column exists
    $sql ="SHOW COLUMNS FROM ".TABLE_ORDERS_STATUS_HISTORY." LIKE '%track_id3%'";
    $result = $db->Execute($sql);
    if(!$result->RecordCount())
    {
        $sql = "ALTER TABLE ".TABLE_ORDERS_STATUS_HISTORY." ADD track_id3 TEXT default NULL";
        $db->Execute($sql);
    }
    //check if track_id4 column exists
    $sql ="SHOW COLUMNS FROM ".TABLE_ORDERS_STATUS_HISTORY." LIKE '%track_id4%'";
    $result = $db->Execute($sql);
    if(!$result->RecordCount())
    {
        $sql = "ALTER TABLE ".TABLE_ORDERS_STATUS_HISTORY." ADD track_id4 TEXT default NULL";
        $db->Execute($sql);
    }
    //check if track_id5 column exists
    $sql ="SHOW COLUMNS FROM ".TABLE_ORDERS_STATUS_HISTORY." LIKE '%track_id5%'";
    $result = $db->Execute($sql);
    if(!$result->RecordCount())
    {
        $sql = "ALTER TABLE ".TABLE_ORDERS_STATUS_HISTORY." ADD track_id5 TEXT default NULL";
        $db->Execute($sql);
    }

   if(file_exists(DIR_FS_ADMIN . DIR_WS_INCLUDES . 'auto_loaders/config.typt.php'))
    {
        if(!unlink(DIR_FS_ADMIN . DIR_WS_INCLUDES . 'auto_loaders/config.typt.php'))
	{
		$messageStack->add('The auto-loader '.DIR_FS_ADMIN.'includes/auto_loaders/config.typt.php  has not been deleted.  For this module to work you must delete this file manually.','error');
	};
    }

       $messageStack->add('Ty Package Tracker v3.1.4 install completed!','success');

    // find next sort order in admin_pages table
    $sql = "SELECT (MAX(sort_order)+2) as sort FROM ".TABLE_ADMIN_PAGES;
    $result = $db->Execute($sql);
    $admin_page_sort = $result->fields['sort'];

    // now register the admin pages
    // Admin Menu for Ty Package Tracker Configuration Menu
    zen_deregister_admin_pages('configTyPackageTracker');
    zen_register_admin_page('configTyPackageTracker',
        'BOX_CONFIGURATION_TY_PACKAGE_TRACKER', 'FILENAME_CONFIGURATION',
        'gID=' . $typt_configuration_id, 'configuration', 'Y',
        $admin_page_sort);