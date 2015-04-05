<?php
/*
 * This file is derived from tpl_account_history_info_default.php
 *
 ******************************************************************************
 * Page Template
 *
 * Loaded automatically by index.php?main_page=account_edit.<br />
 * Displays information related to a single specific order
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_account_history_info_default.php 3306 2006-03-29 07:15:46Z drbyte $
 ******************************************************************************
 * File ID: tpl_ty_package_tracker.php v2.2 by colosports
 */
?>

<div class="centerColumn" id="accountHistInfo">

<div class="forward"><?php echo HEADING_ORDER_DATE . ' ' . zen_date_long($order->info['date_purchased']); ?></div>
<br class="clearBoth" />

<table border="0" width="100%" cellspacing="0" cellpadding="0" summary="Itemized listing of previous order, includes number ordered, items and prices">
<caption><h2 id="orderHistoryDetailedOrder"><?php echo HEADING_TITLE . ORDER_HEADING_DIVIDER . sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']); ?></h2></caption>
    <tr class="tableHeading">
        <th scope="col" id="myAccountQuantity"><?php echo HEADING_QUANTITY; ?></th>
        <th scope="col" id="myAccountProducts"><?php echo HEADING_PRODUCTS; ?></th>
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>
        <th scope="col" id="myAccountTax"><?php echo HEADING_TAX; ?></th>
<?php
 }
?>
        <th scope="col" id="myAccountTotal"><?php echo HEADING_TOTAL; ?></th>
    </tr>
<?php
  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
  ?>
    <tr>
        <td class="accountQuantityDisplay"><?php echo  $order->products[$i]['qty'] . QUANTITY_SUFFIX; ?></td>
        <td class="accountProductDisplay"><?php echo  $order->products[$i]['name'];

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      echo '<ul id="orderAttribsList">';
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<li>' . $order->products[$i]['attributes'][$j]['option'] . TEXT_OPTION_DIVIDER . nl2br($order->products[$i]['attributes'][$j]['value']) . '</li>';
      }
        echo '</ul>';
    }
?>
        </td>
<?php
    if (sizeof($order->info['tax_groups']) > 1) {
?>
        <td class="accountTaxDisplay"><?php echo zen_display_tax_value($order->products[$i]['tax']) . '%' ?></td>
<?php
    }
?>
        <td class="accountTotalDisplay"><?php echo $currencies->format(zen_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ($order->products[$i]['onetime_charges'] != 0 ? '<br />' . $currencies->format(zen_add_tax($order->products[$i]['onetime_charges'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) : '') ?></td>
    </tr>
<?php
  }
?>
</table>
<hr />
<div id="orderTotals">
<?php
  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
?>
     <div class="amount larger forward"><?php echo $order->totals[$i]['text'] ?></div>
     <div class="lineTitle larger forward"><?php echo $order->totals[$i]['title'] ?></div>
<br class="clearBoth" />
<?php
  }
?>

</div>

<?php
/**
 * Used to display any downloads associated with the cutomers account
 */
  if (DOWNLOAD_ENABLED == 'true') require($template->get_template_dir('tpl_modules_downloads.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_downloads.php');
?>


<?php
/**
 * Used to loop thru and display order status information
 */
if (sizeof($statusArray)) {
?>

<table border="1" width="100%" cellspacing="2" cellpadding="2" id="myAccountOrdersStatus" summary="Table contains the date, order status and any comments regarding the order">
<caption><h2 id="orderHistoryStatus"><?php echo HEADING_ORDER_HISTORY; ?></h2></caption>
    <tr class="tableHeading">
        <th scope="col" id="myAccountStatusDate"><?php echo TABLE_HEADING_STATUS_DATE; ?></th>
        <th scope="col" id="myAccountStatus"><?php echo TABLE_HEADING_STATUS_ORDER_STATUS; ?></th>
        <th scope="col" id="myAccountStatusComments"><?php echo TABLE_HEADING_STATUS_COMMENTS; ?></th>
       </tr>
<?php
  foreach ($statusArray as $statuses) {
?>
    <tr>
        <td><?php echo zen_date_short($statuses['date_added']); ?></td>
        <td><?php echo $statuses['orders_status_name']; ?></td>
          <?php
			$display_track_id = '';
			$display_track_id .= (empty($statuses['track_id1']) ? '' : CARRIER_NAME_1 . ":<a href=" . CARRIER_LINK_1 . nl2br(zen_output_string_protected($statuses['track_id1'])) . ' target="_blank">' . nl2br(zen_output_string_protected($statuses['track_id1'])) . "</a><br />" );
			$display_track_id .= (empty($statuses['track_id2']) ? '' : CARRIER_NAME_2 . ":<a href=" . CARRIER_LINK_2 . nl2br(zen_output_string_protected($statuses['track_id2'])) . ' target="_blank">' . nl2br(zen_output_string_protected($statuses['track_id2'])) . "</a><br />" );
			$display_track_id .= (empty($statuses['track_id3']) ? '' : CARRIER_NAME_3 . ":<a href=" . CARRIER_LINK_3 . nl2br(zen_output_string_protected($statuses['track_id3'])) . ' target="_blank">' . nl2br(zen_output_string_protected($statuses['track_id3'])) . "</a><br />" );
			$display_track_id .= (empty($statuses['track_id4']) ? '' : CARRIER_NAME_4 . ":<a href=" . CARRIER_LINK_4 . nl2br(zen_output_string_protected($statuses['track_id4'])) . ' target="_blank">' . nl2br(zen_output_string_protected($statuses['track_id4'])) . "</a><br />" );
			$display_track_id .= (empty($statuses['track_id5']) ? '' : CARRIER_NAME_5 . ":<a href=" . CARRIER_LINK_5 . nl2br(zen_output_string_protected($statuses['track_id5'])) . ' target="_blank">' . nl2br(zen_output_string_protected($statuses['track_id5'])) . "</a><br />" );
			?>
        <td><?php echo $display_track_id; echo (empty($statuses['comments']) ? '&nbsp;' : '<br />' . nl2br(zen_output_string_protected($statuses['comments']))); ?></td>
     </tr>
<?php
  }
?>
</table>
<?php } ?>

<hr />
<div id="myAccountShipInfo" class="floatingBox back">
<?php
  if ($order->delivery != false) {
?>
<h3><?php echo HEADING_DELIVERY_ADDRESS; ?></h3>
<address><?php echo zen_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?></address>
<?php
  }
?>

<?php
    if (zen_not_null($order->info['shipping_method'])) {
?>
<h4><?php echo HEADING_SHIPPING_METHOD; ?></h4>
<div><?php echo $order->info['shipping_method']; ?></div>
<?php } else { // temporary just remove these 4 lines ?>
<div>WARNING: Missing Shipping Information</div>
<?php
    }
?>
</div>

<div id="myAccountPaymentInfo" class="floatingBox forward">
<h3><?php echo HEADING_BILLING_ADDRESS; ?></h3>
<address><?php echo zen_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?></address>

<h4><?php echo HEADING_PAYMENT_METHOD; ?></h4>
<div><?php echo $order->info['payment_method']; ?></div>
</div>
<br class="clearBoth" />
</div>