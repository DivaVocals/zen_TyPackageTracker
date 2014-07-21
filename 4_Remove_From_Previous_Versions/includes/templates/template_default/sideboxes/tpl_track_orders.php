<?php
/*
 * This file is derived from tpl_order_history.php
 *
 ******************************************************************************
 * Side Box Template                                                          *
 *                                                                            *
 * @package templateSystem                                                    *
 * @copyright Copyright 2003-2005 Zen Cart Development Team                   *
 * @copyright Portions Copyright 2003 osCommerce                              *
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0   *
 * @version $Id: tpl_order_history.php 3324 2006-03-31 06:02:07Z drbyte $     *
 ******************************************************************************
 * File ID: tpl_track_orders.php v2.2 by colosports
 */
  $content = "";
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">' . "\n";
  $content .= '<ul class="orderHistList">' . "\n" ;
  
  if (sizeof($customer_orders)==0) {
     $content .= '<li><a href="' . zen_href_link(FILENAME_ACCOUNT_HISTORY, '','SSL') . '">Track Your Orders</a></li>';
 }

  for ($i=1; $i<=sizeof($customer_orders); $i++) {

        $content .= '<li><a href="' . zen_href_link(FILENAME_ACCOUNT_HISTORY_TRACK, 'order_id=' . $customer_orders[$i]['id'],'SSL') . '">Order #' . $customer_orders[$i]['id'] . '</a></li>' . "\n" ;
  }
  $content .= '</ul>' . "\n" ;
  $content .= '</div>';
?>