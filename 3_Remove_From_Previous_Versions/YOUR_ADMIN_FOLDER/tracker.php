<?php
/*
//////////////////////////////////////////////////////////
//  SUPER ORDERS                                        //
//                                                      //
//  By Frank Koehl (PM: BlindSide)                      //
//                                                      //
//  Powered by Zen-Cart (www.zen-cart.com)              //
//  Portions Copyright (c) 2005 The Zen-Cart Team       //
//                                                      //
//  Released under the GNU General Public License       //
//  available at www.zen-cart.com/license/2_0.txt       //
//  or see "license.txt" in the downloaded zip          //
//////////////////////////////////////////////////////////
//  super_orders.php is based on:                       //
//  $Id: orders.php 528 2005-2-10 15:56:02Z wilt $      //
//                                                      //
//  All major code changes are enclosed between         //
//  SUPER_CODE_START and SUPER_CODE_END taglines        //
//////////////////////////////////////////////////////////
// Ty Package tracker is base on super_orders.php and orders.php
// Modified by colosports v2.2
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status = $db->Execute("SELECT orders_status_id, orders_status_name
                                 FROM " . TABLE_ORDERS_STATUS . "
                                 WHERE language_id = '" . (int)$_SESSION['languages_id'] . "'");


  while (!$orders_status->EOF) {
    $orders_statuses[] = array('id' => $orders_status->fields['orders_status_id'],
                               'text' => $orders_status->fields['orders_status_name'] . ' [' . $orders_status->fields['orders_status_id'] . ']');
    $orders_status_array[$orders_status->fields['orders_status_id']] = $orders_status->fields['orders_status_name'];
    $orders_status->MoveNext();
  }

  $orders_id_array = $db->Execute("SELECT orders_id FROM " . TABLE_ORDERS);
  while (!$orders_id_array->EOF) {
    $orders_id_list[] = array('id' => $orders_id_array->fields['orders_id'],
                              'text' => $orders_id_array->fields['orders_id']);
    $orders_id_array->MoveNext();
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (zen_not_null($action)) {
    switch ($action) {

      case 'update_order':
        // demo active test
        if (zen_admin_demo()) {
          $_GET['action']= '';
          $messageStack->add_session(ERROR_ADMIN_DEMO, 'caution');
          zen_redirect(zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('action')) . 'action=edit', 'NONSSL'));
        }
        $oID = zen_db_prepare_input($_GET['oID']);
        $status = zen_db_prepare_input($_POST['status']);
// TY1 BEGIN, DEFINE VALUES  ----------------------------------------------
        $track_id1 = str_replace(" ", "", zen_db_prepare_input($_POST['track_id1']));
        $track_id2 = str_replace(" ", "", zen_db_prepare_input($_POST['track_id2']));
        $track_id3 = str_replace(" ", "", zen_db_prepare_input($_POST['track_id3']));
        $track_id4 = str_replace(" ", "", zen_db_prepare_input($_POST['track_id4']));
        $track_id5 = str_replace(" ", "", zen_db_prepare_input($_POST['track_id5']));
// END TY1 ------------------------------------------------------------------
        $comments = zen_db_prepare_input($_POST['comments']);

        $order_updated = false;
        $check_status = $db->Execute("SELECT customers_name, customers_email_address, orders_status,
                                      payment_module_code, shipping_module_code, date_purchased FROM " . TABLE_ORDERS . "
                                      WHERE orders_id = '" . (int)$oID . "'");

        if ( ($check_status->fields['orders_status'] != $status) || zen_not_null($comments)) {
          $db->Execute("update " . TABLE_ORDERS . "
                        set orders_status = '" . zen_db_input($status) . "', last_modified = now()
                        where orders_id = '" . (int)$oID . "'");

          $customer_notified = '0';
          if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $notify_comments = '';
// TY2 BEGIN, E-MAIL TRACKING INFORMATION  ----------------------------------
            if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on') ) {
              if (zen_not_null($comments)) {
                $notify_comments = EMAIL_TEXT_COMMENTS_UPDATE . $comments;
              }
              if (zen_not_null($track_id1)) { $notify_comments .= "\n\n<br /><br />Your " . CARRIER_NAME_1 . " Tracking ID is " . $track_id1 . " \n<br /><a href=" . CARRIER_LINK_1 . $track_id1 . ">Click here</a> to track your package. \n<br />If the above link does not work, copy the following URL address and paste it into your Web browser. \n<br />" . CARRIER_LINK_1 . $track_id1 . "\n\n<br /><br />It may take up to 24 hours for the tracking information to appear on the website." . "\n<br />"; }
              if (zen_not_null($track_id2)) { $notify_comments .= "\n\n<br /><br />Your " . CARRIER_NAME_2 . " Tracking ID is " . $track_id2 . " \n<br /><a href=" . CARRIER_LINK_2 . $track_id2 . ">Click here</a> to track your package. \n<br />If the above link does not work, copy the following URL address and paste it into your Web browser. \n<br />" . CARRIER_LINK_2 . $track_id2 . "\n\n<br /><br />It may take up to 24 hours for the tracking information to appear on the website." . "\n<br />"; }
              if (zen_not_null($track_id3)) { $notify_comments .= "\n\n<br /><br />Your " . CARRIER_NAME_3 . " Tracking ID is " . $track_id3 . " \n<br /><a href=" . CARRIER_LINK_3 . $track_id3 . ">Click here</a> to track your package. \n<br />If the above link does not work, copy the following URL address and paste it into your Web browser. \n<br />" . CARRIER_LINK_3 . $track_id3 . "\n\n<br /><br />It may take up to 24 hours for the tracking information to appear on the website." . "\n<br />"; }
              if (zen_not_null($track_id4)) { $notify_comments .= "\n\n<br /><br />Your " . CARRIER_NAME_4 . " Tracking ID is " . $track_id4 . " \n<br /><a href=" . CARRIER_LINK_4 . $track_id4 . ">Click here</a> to track your package. \n<br />If the above link does not work, copy the following URL address and paste it into your Web browser. \n<br />" . CARRIER_LINK_4 . $track_id4 . "\n\n<br /><br />It may take up to 24 hours for the tracking information to appear on the website." . "\n<br />"; }
              if (zen_not_null($track_id5)) { $notify_comments .= "\n\n<br /><br />Your " . CARRIER_NAME_5 . " Tracking ID is " . $track_id5 . " \n<br /><a href=" . CARRIER_LINK_5 . $track_id5 . ">Click here</a> to track your package. \n<br />If the above link does not work, copy the following URL address and paste it into your Web browser. \n<br />" . CARRIER_LINK_5 . $track_id5 . "\n\n<br /><br />It may take up to 24 hours for the tracking information to appear on the website." . "\n<br />"; }
// END TY2 --------------------------------------------------------------------
            }


//send emails
      $message = STORE_NAME . " " . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n\n" .
      EMAIL_TEXT_INVOICE_URL . ' ' . zen_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . "\n\n" .
      EMAIL_TEXT_DATE_ORDERED . ' ' . zen_date_long($check_status->fields['date_purchased']) . "\n\n" .
      strip_tags($notify_comments) .
      EMAIL_TEXT_STATUS_UPDATED . sprintf(EMAIL_TEXT_STATUS_LABEL, $orders_status_array[$status] ) .
      EMAIL_TEXT_STATUS_PLEASE_REPLY;

      $html_msg['EMAIL_CUSTOMERS_NAME']    = $check_status->fields['customers_name'];
      $html_msg['EMAIL_TEXT_ORDER_NUMBER'] = EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID;
      $html_msg['EMAIL_TEXT_INVOICE_URL']  = '<a href="' . zen_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') .'">'.str_replace(':','',EMAIL_TEXT_INVOICE_URL).'</a>';
      $html_msg['EMAIL_TEXT_DATE_ORDERED'] = EMAIL_TEXT_DATE_ORDERED . ' ' . zen_date_long($check_status->fields['date_purchased']);
      $html_msg['EMAIL_TEXT_STATUS_COMMENTS'] = nl2br($notify_comments);
      $html_msg['EMAIL_TEXT_STATUS_UPDATED'] = str_replace('\n','<br />', EMAIL_TEXT_STATUS_UPDATED);
      $html_msg['EMAIL_TEXT_STATUS_LABEL'] = str_replace('\n','<br />', sprintf(EMAIL_TEXT_STATUS_LABEL, $orders_status_array[$status] ));
      $html_msg['EMAIL_TEXT_NEW_STATUS'] = $orders_status_array[$status];
      $html_msg['EMAIL_TEXT_STATUS_PLEASE_REPLY'] = str_replace('\n','<br />', EMAIL_TEXT_STATUS_PLEASE_REPLY);

//For zencart 1.3.0 and earlier (uncomment next 3 lines)
//$html_msg['EMAIL_TEXT_STATUS_COMMENTS'] = $notify_comments;
//$html_msg['EMAIL_TEXT_STATUS_UPDATED'] = str_replace('\n','', EMAIL_TEXT_STATUS_UPDATED);
//$html_msg['EMAIL_TEXT_STATUS_LABEL'] = '<strong>New status:</strong>';

            zen_mail($check_status->fields['customers_name'], $check_status->fields['customers_email_address'], EMAIL_TEXT_SUBJECT . ' #' . $oID, $message, STORE_NAME, EMAIL_FROM, $html_msg, 'order_status');

            $customer_notified = '1';
//send extra emails
            if (SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO_STATUS == '1' and SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO != '') {
              zen_mail('', SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO, SEND_EXTRA_ORDERS_STATUS_ADMIN_EMAILS_TO_SUBJECT . ' ' . EMAIL_TEXT_SUBJECT . ' #' . $oID, $message, STORE_NAME, EMAIL_FROM, $html_msg, 'order_status_extra');
            }
          }

// TY3 BEGIN, APPEND FIELDS TO DATABASE------------------------------------
          $db->Execute("insert into " . TABLE_ORDERS_STATUS_HISTORY . "
                      (orders_id, orders_status_id, date_added, customer_notified, track_id1, track_id2, track_id3, track_id4, track_id5, comments)
                      values ('" . (int)$oID . "',
                      '" . zen_db_input($status) . "',
                      now(),
                      '" . zen_db_input($customer_notified) . "',
                      '" . zen_db_input($track_id1)  . "',
                      '" . zen_db_input($track_id2)  . "',
                      '" . zen_db_input($track_id3)  . "',
                      '" . zen_db_input($track_id4)  . "',
                      '" . zen_db_input($track_id5)  . "',
                      '" . zen_db_input($comments)  . "')");
// END TY3 ----------------------------------------------------------------

          $order_updated = true;
        }

        if ($order_updated == true) {
         if ($status == DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE) {
            // adjust download_maxdays based on current date
            $zc_max_days = date_diff($check_status->fields['date_purchased'], date('Y-m-d H:i:s', time())) + DOWNLOAD_MAX_DAYS;

            $update_downloads_query = "update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_maxdays='" . $zc_max_days . "', download_count='" . DOWNLOAD_MAX_COUNT . "' where orders_id='" . (int)$oID . "'";
            $db->Execute($update_downloads_query);
          }
          $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        zen_redirect(zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('action')) . 'action=edit', 'NONSSL'));
        break;
      case 'deleteconfirm':
        // demo active test
        if (zen_admin_demo()) {
          $_GET['action']= '';
          $messageStack->add_session(ERROR_ADMIN_DEMO, 'caution');
          zen_redirect(zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('oID', 'action')), 'NONSSL'));
        }
        $oID = zen_db_prepare_input($_GET['oID']);

        zen_remove_order($oID, $_POST['restock']);
        zen_redirect(zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('oID', 'action')), 'NONSSL'));
        break;
    }
  }

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = zen_db_prepare_input($_GET['oID']);

    $orders = $db->Execute("SELECT orders_id FROM " . TABLE_ORDERS . "
                            WHERE orders_id = '" . (int)$oID . "'");

    $order_exists = true;
    if ($orders->RecordCount() <= 0) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }

  include(DIR_WS_CLASSES . 'order.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE . ': ' . HEADING_TITLE_TRACK; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" media="print" href="includes/stylesheet_print.css">
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
  }
  // -->
</script>
<script language="javascript" type="text/javascript"><!--
function couponpopupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body onload="init()">
<!-- header //-->
<div class="header-area">
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
</div>
<!-- header_eof //-->

<!-- body //-->

<table border="0" width="100%" cellspacing="3" cellpadding="3">
        <tr>
<!-- bof search headers //-->            
        <td><?php echo zen_draw_form('search', FILENAME_TRACK, '', 'get', '', true); ?>
        <td class="pageHeading"><?php echo HEADING_TITLE_TRACK; ?></td>
        <td align="right">
        		<?php echo HEADING_TITLE_SEARCH_DETAIL . ' ' . zen_draw_input_field('search') . zen_hide_session_id();
				  if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
				    $keywords = zen_db_input(zen_db_prepare_input($_GET['search']));
				    echo '<br/ >' . TEXT_INFO_SEARCH_DETAIL_FILTER . $keywords;
				  } ?>
        </form></td>
	<?php	
	            // show reset search
	  if ((isset($_GET['search']) && zen_not_null($_GET['search'])) or $_GET['cID'] !='') {
	     echo '<td  class="smallText" align="right"><a href="' . zen_href_link(FILENAME_TRACK, '', 'NONSSL') . '">' . zen_image_button('button_reset.gif', IMAGE_RESET) . '</a></td>';
	  }
	?>            
<!-- eof search headers //-->

<!-- bof search order id //-->
        <td><?php echo zen_draw_form('orders', FILENAME_TRACK, '', 'get', '', true); ?><?php echo HEADING_TITLE_SEARCH . ' ' . zen_draw_input_field('oID', '', 'size="10"') . zen_draw_hidden_field('action', 'edit'); ?></form></td>
        <td><?php echo zen_draw_form('status', FILENAME_TRACK, '', 'get', '', true); ?><?php echo HEADING_TITLE_STATUS . ' ' . zen_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), $_GET['status'], 'onChange="this.form.submit();"'); ?></form></td>
<!-- eof search order id //-->
        </tr>
    </table>
<hr />
      
<!-- body_text //-->
<?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
    if ($order->info['payment_module_code']) {
      if (file_exists(DIR_FS_CATALOG_MODULES . 'payment/' . $order->info['payment_module_code'] . '.php')) {
        require(DIR_FS_CATALOG_MODULES . 'payment/' . $order->info['payment_module_code'] . '.php');
        require(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language'] . '/modules/payment/' . $order->info['payment_module_code'] . '.php');
        $module = new $order->info['payment_module_code'];
//        echo $module->admin_notification($oID);
      }
    }
//  MODIFIED BY MERSON MATHEW from SUPER ORDERS module to display the next order and not just increment the order number by 1
 $get_prev = $db->Execute("SELECT orders_id FROM " . TABLE_ORDERS . " WHERE orders_id < '" . $oID . "' ORDER BY orders_id DESC LIMIT 1");

    if (zen_not_null($get_prev->fields['orders_id'])) {
      $prev_button = '            <INPUT TYPE="BUTTON" VALUE="<<< ' . $get_prev->fields['orders_id'] . '" ONCLICK="window.location.href=\'' . zen_href_link(FILENAME_TRACK, 'oID=' . $get_prev->fields['orders_id'] . '&action=edit') . '\'">';
    }
    else {
      $prev_button = '            <INPUT TYPE="BUTTON" VALUE="' . BUTTON_TO_LIST . '" ONCLICK="window.location.href=\'' . zen_href_link(FILENAME_TRACK) . '\'">';
    }


    $get_next = $db->Execute("SELECT orders_id FROM " . TABLE_ORDERS . " WHERE orders_id > '" . $oID . "' ORDER BY orders_id ASC LIMIT 1");

    if (zen_not_null($get_next->fields['orders_id'])) {
      $next_button = '            <INPUT TYPE="BUTTON" VALUE="' . $get_next->fields['orders_id'] . ' >>>" ONCLICK="window.location.href=\'' . zen_href_link(FILENAME_TRACK, 'oID=' . $get_next->fields['orders_id'] . '&action=edit') . '\'">';
    }
    else {
      $next_button = '            <INPUT TYPE="BUTTON" VALUE="' . BUTTON_TO_LIST . '" ONCLICK="window.location.href=\'' . zen_href_link(FILENAME_TRACK) . '\'">';
    }
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_ORDER_DETAILS . $oID; ?></td>
            <td class="main" align="center" valign="middle"><?php echo $prev_button; ?></td>
            <td class="smallText" align="center" valign="center"><?php
              echo SELECT_ORDER_LIST . '<br>';
              echo zen_draw_form('search_oid', FILENAME_TRACK, '', 'get', '', true);
              echo zen_draw_pull_down_menu('oID', $orders_id_list, '', 'onChange="this.form.submit();"');
              echo zen_draw_hidden_field('action', 'edit');
              echo '</form>';
            ?></td>
            <td class="main" align="center" valign="middle"><?php echo $next_button; ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><?php
              if (SUPER_ORDERS_MOD_STATUS == 'True') {
                 echo '<a href="' . zen_href_link(FILENAME_EDIT, 'action=edit&oID=' . $_GET['oID'], 'NONSSL') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a>&nbsp;&nbsp;';
              }
              echo '<a href="' . zen_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . zen_image_button('button_invoice.gif', ICON_ORDER_INVOICE) . '</a>&nbsp;&nbsp;';
              echo '<a href="' . zen_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . zen_image_button('button_packingslip.gif', ICON_ORDER_PACKINGSLIP) . '</a>&nbsp;&nbsp;';
              echo '<a href="javascript:history.back()">' . zen_image_button('button_back.gif', IMAGE_BACK) . '</a>';
//  SUPER_CODE_END
            ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo zen_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="1" cellspacing="0" cellpadding="2">
             <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main">
                <?php 
                echo str_replace(" <BR />", "<BR />", strtoupper(zen_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br />'))); 
                ?>
                </td>
			  </tr>
               <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
              <tr>
                <td class="main"><b>Shipping Method:</b></td>
                <td class="main"><?php echo $order->info['shipping_method']; ?></td>
              </tr>
	          <tr>
				<td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
				<td class="main"><?php echo $order->info['payment_method']; ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="dataTableContent"><?php
                  echo '<a href="mailto:' . $order->customer['email_address'] . '">' . '<font color="blue">' . $order->customer['email_address'] . '</a></font>&nbsp;'; ?></font></td>
              </tr>
           </table></td>

           <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
               <tr>
                 <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                 <td class="main"><?php echo zen_address_format($order->billing['format_id'], $order->billing, 1, '', '<br />'); ?></td>
               </tr>

           </table></td>
        </tr>
        </table></td>
      </tr>
     <td><table border="0" cellspacing="0" cellpadding="2">
        <tr>
           <td class="main"><b><?php echo ENTRY_DATE_PURCHASED; ?></b></td>
           <td class="main"><?php echo zen_date_long($order->info['date_purchased']); ?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="2" class="main"><b><u><?php echo ENTRY_PAYMENT_DETAILS; ?></u></b></td>
        </tr>

          </table>
        </td>
<?php

      if (method_exists($module, 'admin_notification')) {
?>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <?php echo $module->admin_notification($oID); ?>
      </tr>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
}
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      echo '          <tr class="dataTableRow">' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . nl2br($order->products[$i]['attributes'][$j]['value']);
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          if ($order->products[$i]['attributes'][$j]['product_attribute_is_free'] == '1' and $order->products[$i]['product_is_free'] == '1') echo TEXT_INFO_ATTRIBUTE_FREE;
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top">' . zen_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><strong>' .
                          $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) .
                          ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format($order->products[$i]['onetime_charges'], true, $order->info['currency'], $order->info['currency_value']) : '') .
                        '</strong></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><strong>' .
                          $currencies->format(zen_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) .
                          ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') .
                        '</strong></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><strong>' .
                          $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) .
                          ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format($order->products[$i]['onetime_charges'], true, $order->info['currency'], $order->info['currency_value']) : '') .
                        '</strong></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><strong>' .
                          $currencies->format(zen_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) .
                          ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') .
                        '</strong></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td align="right" colspan="8"><table border="0" cellspacing="0" cellpadding="2">
<?php
    // Short shipping display
    // Formats shipping entry to remove the TEXT_WAY define
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
  /* Removed because it conflict with FedEx module
    if ($order->totals[$i]['class'] == 'ot_shipping') {
      $format_delivery = explode(" (", $order->totals[$i]['title'], 2);
      $display_title = $format_delivery[0] . ':';
    }
    else {
      $display_title = $order->totals[$i]['title'];
    }
  */
$display_title = $order->totals[$i]['title'];

      echo '              <tr>' . "\n" .
           '                <td align="right" class="'. str_replace('_', '-', $order->totals[$i]['class']) . '-Text">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="'. str_replace('_', '-', $order->totals[$i]['class']) . '-Amount">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>

<?php
  // show downloads
  require(DIR_WS_MODULES . 'orders_download.php');
?>

      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" colspan="2"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
<!-- TY4 BEGIN, DISPLAY MESSAGE IN E-MAIL------------------------------->
	<td class="smallText" align="center"><b><?php echo TABLE_HEADING_TRACKING_ID; ?></b></td>
<!-- END TY4------------------------------------------------------------>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
<?php
// TY5 BEGIN, INCLUDE DATABASE FIELDS ------------------------------
    $orders_history = $db->Execute("select orders_status_id, date_added, customer_notified, track_id1, track_id2, track_id3, track_id4, track_id5, comments
                                    from " . TABLE_ORDERS_STATUS_HISTORY . "
                                    where orders_id = '" . zen_db_input($oID) . "'
                                    order by date_added");
// END TY5-----------------------------------------------------------

    if ($orders_history->RecordCount() > 0) {
      while (!$orders_history->EOF) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . zen_datetime_short($orders_history->fields['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history->fields['customer_notified'] == '1') {
          echo zen_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo zen_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history->fields['orders_status_id']] . '</td>' . "\n";
// TY6 BEGIN, DEFINE TRACKING INFORMATION ON ORDER.PHP FILE ----------------
        $display_track_id = '&nbsp;';
	$display_track_id .= (empty($orders_history->fields['track_id1']) ? '' : CARRIER_NAME_1 . ": <a href=" . CARRIER_LINK_1 . nl2br(zen_output_string_protected($orders_history->fields['track_id1'])) . ' target="_blank">' . nl2br(zen_output_string_protected($orders_history->fields['track_id1'])) . "</a><br />&nbsp;" );
	$display_track_id .= (empty($orders_history->fields['track_id2']) ? '' : CARRIER_NAME_2 . ": <a href=" . CARRIER_LINK_2 . nl2br(zen_output_string_protected($orders_history->fields['track_id2'])) . ' target="_blank">' . nl2br(zen_output_string_protected($orders_history->fields['track_id2'])) . "</a><br />&nbsp;" );
	$display_track_id .= (empty($orders_history->fields['track_id3']) ? '' : CARRIER_NAME_3 . ": <a href=" . CARRIER_LINK_3 . nl2br(zen_output_string_protected($orders_history->fields['track_id3'])) . ' target="_blank">' . nl2br(zen_output_string_protected($orders_history->fields['track_id3'])) . "</a><br />&nbsp;" );
	$display_track_id .= (empty($orders_history->fields['track_id4']) ? '' : CARRIER_NAME_4 . ": <a href=" . CARRIER_LINK_4 . nl2br(zen_output_string_protected($orders_history->fields['track_id4'])) . ' target="_blank">' . nl2br(zen_output_string_protected($orders_history->fields['track_id4'])) . "</a><br />&nbsp;" );
	$display_track_id .= (empty($orders_history->fields['track_id5']) ? '' : CARRIER_NAME_5 . ": <a href=" . CARRIER_LINK_5 . nl2br(zen_output_string_protected($orders_history->fields['track_id5'])) . ' target="_blank">' . nl2br(zen_output_string_protected($orders_history->fields['track_id5'])) . "</a><br />&nbsp;" );
        echo '            <td class="smallText">' . $display_track_id . '</td>' . "\n";
// END TY6 -------------------------------------------------------------------
echo '            <td class="smallText">' . nl2br(zen_db_output($orders_history->fields['comments'])) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
        $orders_history->MoveNext();
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr>
      <?php echo zen_draw_form('status', FILENAME_TRACK, zen_get_all_get_params(array('action')) . 'action=update_order', 'post', '', true); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
<!-- TY7 BEGIN, DISPLAY TRACKING INFORMATION ON ORDER.PHP FILE------------------>
            <td colspan="2"><?php echo zen_draw_separator(); ?></td>
          </tr>
          <tr>
      <td>
      <table border="0" cellspacing="0" cellpadding="3">
      <tr>
	    <td class="main" align="center"><b><u><?php echo ENTRY_ADD_TRACK; ?></u></b></td>
	    <td class="main" align="center"><b><u><?php echo ENTRY_ADD_COMMENTS; ?></u></b></td>
	    </tr>
      <tr valign="top">
      	<td width="250">
	       <table width="100%" border="1" cellspacing="0" cellpadding="3">
	       <tr>
	       	<td class="main" align="right"><strong><?php echo TABLE_HEADING_CARRIER_NAME; ?></strong></td>
	        <td class="main"><strong><?php echo TABLE_HEADING_TRACKING_ID; ?></strong></td>
	       </tr>
	       <?php if (CARRIER_STATUS_1 == 'True') { ?>
	       <tr>
	         <td align="right"><?php echo CARRIER_NAME_1; ?></td><td valign="top"><?php echo zen_draw_input_field('track_id1', ''); ?></td>
	       </tr>
	       <?php } ?>
	       <?php if (CARRIER_STATUS_2 == 'True') { ?>
	       	<tr>
	       	  <td align="right"><?php echo CARRIER_NAME_2; ?></td><td valign="top"><?php echo zen_draw_input_field('track_id2', ''); ?></td>
	       	</tr>
	       <?php } ?>
	       <?php if (CARRIER_STATUS_3 == 'True') { ?>
	       	<tr>
	       	  <td align="right"><?php echo CARRIER_NAME_3; ?></td><td valign="top"><?php echo zen_draw_input_field('track_id3', ''); ?></td>
	       	</tr>
	       <?php } ?>
	       <?php if (CARRIER_STATUS_4 == 'True') { ?>
	       	<tr>
	       	  <td align="right"><?php echo CARRIER_NAME_4; ?></td><td valign="top"><?php echo zen_draw_input_field('track_id4', ''); ?></td>
	       	</tr>
	       <?php } ?>
	       <?php if (CARRIER_STATUS_5 == 'True') { ?>
	       	<tr>
	       	  <td align="right"><?php echo CARRIER_NAME_5; ?></td><td valign="top"><?php echo zen_draw_input_field('track_id5', ''); ?></td>
	       	</tr>
	       <?php } ?>

	       </table>
	     </td>
	     <td width="500">
	       <table width="100%" border="1" cellspacing="0" cellpadding="3">
         <tr>
	         <td class="main"><strong><?php echo TABLE_HEADING_COMMENTS; ?></strong></td>
	       </tr>
	       <tr>
	         <td valign="top" class="main noprint"><?php echo zen_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
	       </tr>
	       </table>
	     </td>
      </tr>
      </table>
      </td>
<!-- END TY7------------------------------------------------------------>
            <td class="main" valign="center"><?php
// TY8 BEGIN, CHANGE DEFAULT VALUE TO NOTIFY CUSTOMER ------------------
              echo zen_draw_checkbox_field('notify', '', true); echo '&nbsp;<b>' . ENTRY_NOTIFY_CUSTOMER . '</b>' . '<br><br>';
// END TY8 -------------------------------------------------------------
              echo zen_draw_checkbox_field('notify_comments', '', true); echo '&nbsp;<b>' . ENTRY_NOTIFY_COMMENTS . '</b>';
            ?></td>
          </tr>
          <tr>
            <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo zen_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
            <td valign="top"><?php echo zen_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>
      <tr>
        <td colspan="3"><?php echo zen_draw_separator(); ?></td>
      </tr>
<?php
// SUPER_CODE_START
?>
      <tr>
        <td align="right"><?php
          if (SUPER_ORDERS_MOD_STATUS == 'True') {
                 echo '<a href="' . zen_href_link(FILENAME_EDIT, 'action=edit&oID=' . $_GET['oID'], 'NONSSL') . '">' . zen_image_button('button_edit.gif', IMAGE_EDIT) . '</a>&nbsp;&nbsp;';
          }
          echo '<a href="' . zen_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $oID) . '" TARGET="_blank">' . zen_image_button('button_invoice.gif', ICON_ORDER_INVOICE) . '</a>&nbsp;&nbsp;';
          echo '<a href="' . zen_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $oID) . '" TARGET="_blank">' . zen_image_button('button_packingslip.gif', ICON_ORDER_PACKINGSLIP) . '</a>&nbsp;&nbsp;';
          echo '<a href="javascript:history.back()">' . zen_image_button('button_back.gif', IMAGE_BACK) . '</a>';
        ?></td>
      </tr>

<?php
// SUPER_CODE_END

// check if order has open gv
        $gv_check = $db->Execute("select order_id, unique_id
                                  from " . TABLE_COUPON_GV_QUEUE ."
                                  where order_id = '" . $_GET['oID'] . "' and release_flag='N' limit 1");
        if ($gv_check->RecordCount() > 0) {
          $goto_gv = '<a href="' . zen_href_link(FILENAME_GV_QUEUE, 'order=' . $_GET['oID']) . '">' . zen_image_button('button_gift_queue.gif',IMAGE_GIFT_QUEUE) . '</a>';
          echo '      <tr><td align="right"><table width="225"><tr>';
          echo '        <td align="center">';
          echo $goto_gv . '&nbsp;&nbsp;';
          echo '        </td>';
          echo '      </tr></table></td></tr>';
        }
?>
<?php
  } else {
?>
      <!-- Orders Listing -->

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
              <!-- SHORTCUT ICON LEGEND -->
                <td><table border="0" cellspacing="0" cellpadding="2" width="100%" valign="top">
                  <tr>
                    <td align="center"><?php echo zen_image(DIR_WS_IMAGES . 'icon_cust_orders.gif', MINI_ICON_ORDERS); ?></td>
                    <td class="smallText"><?php echo MINI_ICON_ORDERS; ?></td>

                    <td align="center"><?php echo zen_image(DIR_WS_IMAGES . 'icon_track.gif', ICON_ORDER_TRACK); ?></td>
                    <td class="smallText"><?php echo ICON_ORDER_TRACK; ?></td>

                    <td align="center"><?php echo zen_image(DIR_WS_IMAGES . 'icon_edit.gif', ICON_ORDER_EDIT); ?></td>
                    <td class="smallText"><?php echo ICON_ORDER_EDIT; ?></td>

                    <td align="center"><?php echo zen_image(DIR_WS_IMAGES . 'icon_invoice.gif', ICON_ORDER_INVOICE); ?></td>
                    <td class="smallText"><?php echo ICON_ORDER_INVOICE; ?></td>

                    <td align="center"><?php echo zen_image(DIR_WS_IMAGES . 'icon_packingslip.gif', ICON_ORDER_PACKINGSLIP); ?></td>
                    <td class="smallText"><?php echo ICON_ORDER_PACKINGSLIP; ?></td>

                    <td align="center"><?php echo zen_image(DIR_WS_IMAGES . 'icon_delete.gif', ICON_ORDER_DELETE); ?></td>
                    <td class="smallText"><?php echo ICON_ORDER_DELETE; ?></td>
                  </tr>
                </table></td>
              </tr>
              <!-- SHORTCUT ICON LEGEND EOF -->
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
<?php
// Sort Listing
          switch ($_GET['list_order']) {
              case "id-asc":
              $disp_order = "c.customers_id";
              break;
              case "firstname":
              $disp_order = "c.customers_firstname";
              break;
              case "firstname-desc":
              $disp_order = "c.customers_firstname DESC";
              break;
              case "lastname":
              $disp_order = "c.customers_lastname, c.customers_firstname";
              break;
              case "lastname-desc":
              $disp_order = "c.customers_lastname DESC, c.customers_firstname";
              break;
              case "company":
              $disp_order = "a.entry_company";
              break;
              case "company-desc":
              $disp_order = "a.entry_company DESC";
              break;
              default:
              $disp_order = "c.customers_id DESC";
          }
?>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_ORDERS_ID; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_SHIPPING_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>

<?php
// create search filter
  $search = '';
  if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
    $keywords = zen_db_input(zen_db_prepare_input($_GET['search']));
    $search = " and (o.customers_city like '%" . $keywords . "%' or o.customers_postcode like '%" . $keywords . "%' or o.date_purchased like '%" . $keywords . "%' or o.billing_name like '%" . $keywords . "%' or o.billing_company like '%" . $keywords . "%' or o.billing_street_address like '%" . $keywords . "%' or o.delivery_city like '%" . $keywords . "%' or o.delivery_postcode like '%" . $keywords . "%' or o.delivery_name like '%" . $keywords . "%' or o.delivery_company like '%" . $keywords . "%' or o.delivery_street_address like '%" . $keywords . "%' or o.billing_city like '%" . $keywords . "%' or o.billing_postcode like '%" . $keywords . "%' or o.customers_email_address like '%" . $keywords . "%' or o.customers_name like '%" . $keywords . "%' or o.customers_company like '%" . $keywords . "%' or o.customers_street_address  like '%" . $keywords . "%' or o.customers_telephone like '%" . $keywords . "%')";
  }
?>
<?php
    $new_fields = ", o.customers_street_address, o.delivery_name, o.delivery_street_address, o.billing_name, o.billing_street_address ";
    if (isset($_GET['cID'])) {
      $cID = zen_db_prepare_input($_GET['cID']);
      $orders_query_raw = "select o.orders_id, o.customers_id, o.customers_company, o.delivery_company, o.customers_name, o.customers_email_address, o.payment_method, o.payment_module_code, o.shipping_module_code, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total" . $new_fields . " from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$_SESSION['languages_id'] . "' and ot.class = 'ot_total' order by orders_id DESC";
    } elseif ($_GET['status'] != '') {
      $status = zen_db_prepare_input($_GET['status']);
      $orders_query_raw = "select o.orders_id, o.customers_id, o.customers_company, o.delivery_company, o.customers_name, o.payment_method, o.payment_module_code, o.shipping_module_code, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total" . $new_fields . " from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$_SESSION['languages_id'] . "' and s.orders_status_id = '" . (int)$status . "' and ot.class = 'ot_total'  " . $search . " order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.orders_id, o.customers_id, o.customers_company, o.delivery_company, o.customers_name, o.payment_method, o.payment_module_code, o.shipping_module_code, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total" . $new_fields . " from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$_SESSION['languages_id'] . "' and ot.class = 'ot_total'  " . $search . " order by o.orders_id DESC";
    }
    $orders_query_numrows = '';
    $orders_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ORDERS, $orders_query_raw, $orders_query_numrows);
    $orders = $db->Execute($orders_query_raw);
    while (!$orders->EOF) {
    if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders->fields['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders->fields);
      }

      if (isset($oInfo) && is_object($oInfo) && ($orders->fields['orders_id'] == $oInfo->orders_id)) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=edit', 'NONSSL') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('oID')) . 'oID=' . $orders->fields['orders_id'], 'NONSSL') . '\'">' . "\n";
      }

      $show_difference = '';
      if (($orders->fields['delivery_name'] != $orders->fields['billing_name'] and $orders->fields['delivery_name'] != '')) {
        $show_difference = ' ' . zen_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
      if (($orders->fields['delivery_street_address'] != $orders->fields['billing_street_address'] and $orders->fields['delivery_street_address'] != '')) {
        $show_difference = ' ' . zen_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
      if ($show_difference == '') {
        $show_difference = ' ' . zen_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', 10, 10);
      }
//  SUPER_CODE_START
?>
                <td class="dataTableContent" align="left"><?php
                  echo $orders->fields['orders_id'] . $show_difference;
                ?></td>
                <td class="dataTableContent"><?php
//                  echo '<a href="' . zen_href_link(FILENAME_CUSTOMERS, 'cID=' . $orders->fields['customers_id'] . '&action=edit', 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_cust_info.gif', MINI_ICON_INFO) . '</a>&nbsp;';
                  echo '<a href="' . zen_href_link(FILENAME_TRACK, 'cID=' . $orders->fields['customers_id'], 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_cust_orders.gif', MINI_ICON_ORDERS) . '</a>&nbsp;';
                  echo $orders->fields['customers_name'] . ($orders->fields['customers_company'] != '' ? '<br />(' . $orders->fields['customers_company'] . ')' : ''); ?>
                </td>
                <td class="dataTableContent" align="left"><?php echo $orders->fields['delivery_name'] . ($orders->fields['delivery_company'] != '' ? '<br />(' . $orders->fields['delivery_company'] . ')' : ''); ?></td>
                <td class="dataTableContent" align="right"><?php echo strip_tags($orders->fields['order_total']); ?></td>
                <td class="dataTableContent" align="center"><?php echo zen_datetime_short($orders->fields['date_purchased']); ?></td>
                <td class="dataTableContent" align="left"><?php echo $orders->fields['payment_module_code'] . '<br />' . $orders->fields['shipping_module_code']; ?></td>
                <td class="dataTableContent" align="left"><?php echo $orders->fields['orders_status_name']; ?></td>

                <td class="dataTableContent" align="right"><?php
                    echo '<a href="' . zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders->fields['orders_id'] . '&action=edit', 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_track.gif', ICON_ORDER_TRACK) . '</a>&nbsp';
                    if (SUPER_ORDERS_MOD_STATUS == 'True') {
                       echo '<a href="' . zen_href_link(FILENAME_EDIT, 'action=edit&oID=' . $orders->fields['orders_id']) . '">' . zen_image(DIR_WS_IMAGES . 'icon_edit.gif', ICON_ORDER_EDIT) . '</a>&nbsp;&nbsp;';
                    }
                    echo '<a href="' . zen_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $orders->fields['orders_id']) . '" TARGET="_blank">' . zen_image(DIR_WS_IMAGES . 'icon_invoice.gif', ICON_ORDER_INVOICE) . '</a>&nbsp;';
                    echo '<a href="' . zen_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $orders->fields['orders_id']) . '" TARGET="_blank">' . zen_image(DIR_WS_IMAGES . 'icon_packingslip.gif', ICON_ORDER_PACKINGSLIP) . '</a>&nbsp;';
                    echo '<a href="' . zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders->fields['orders_id'] . '&action=delete', 'NONSSL') . '">' . zen_image(DIR_WS_IMAGES . 'icon_delete.gif', ICON_ORDER_DELETE) . '</a>';
                ?>&nbsp;</td>
              </tr>
<?php
      $orders->MoveNext();
    }
//  SUPER_CODE_END
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ORDERS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ORDERS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], zen_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>

<?php
  if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
?>
                  <tr>
                    <td class="smallText" align="right" colspan="2">
                      <?php
                        echo '<a href="' . zen_href_link(FILENAME_TRACK, '', 'NONSSL') . '">' . zen_image_button('button_reset.gif', IMAGE_RESET) . '</a>';
                        if (isset($_GET['search']) && zen_not_null($_GET['search'])) {
                          $keywords = zen_db_input(zen_db_prepare_input($_GET['search']));
                          echo '<br/ >' . TEXT_INFO_SEARCH_DETAIL_FILTER . $keywords;
                        }
                      ?>
                    </td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_ORDER . '</strong>');

      $contents = array('form' => zen_draw_form('orders', FILENAME_TRACK, zen_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm', 'post', '', true));
//      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br /><br /><strong>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</strong>');
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br /><br /><strong>' . ENTRY_ORDER_ID . $oInfo->orders_id . '<br />' . $oInfo->order_total . '<br />' . $oInfo->customers_name . ($oInfo->customers_company != '' ? '<br />' . $oInfo->customers_company : '') . '</strong>');
      $contents[] = array('text' => '<br />' . zen_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY);
      $contents[] = array('align' => 'center', 'text' => '<br />' . zen_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . zen_href_link(FILENAME_TRACK, zen_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id, 'NONSSL') . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . zen_datetime_short($oInfo->date_purchased) . '</b>');
      }
      break;
  }

  if ( (zen_not_null($heading)) && (zen_not_null($contents)) ) {
?>
            <td width="25%" valign="top"><table border="0" cellspacing="0" cellpadding="0" width="100%" valign="top">
              <tr>
                <td colspan="2" valign="top">
<?php
    $box = new box;
    echo $box->infoBox($heading, $contents);
//  SUPER_CODE_START
?>
                </td>
              </tr>
            </table></td>
<?php
//  SUPER_CODE_START
  } // END if ( (zen_not_null($heading)) && (zen_not_null($contents)) )
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>