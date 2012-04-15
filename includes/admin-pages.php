<?php

function edd_add_options_link() {
	global $edd_discounts_page, $edd_payments_page, $edd_settings_page, $edd_reports_page;
	
	$edd_payments_page = add_submenu_page('edit.php?post_type=download', __('Payment History', 'edd'), __('Payment History', 'edd'), 'manage_options', 'edd-payment-history', 'edd_payment_history_page');
	$edd_discounts_page = add_submenu_page('edit.php?post_type=download', __('Discount Codes', 'edd'), __('Discount Codes', 'edd'), 'manage_options', 'edd-discounts', 'edd_discounts_page');
	$edd_reports_page = add_submenu_page('edit.php?post_type=download', __('Earnings and Sales Reports', 'edd'), __('Reports', 'edd'), 'manage_options', 'edd-reports', 'edd_reports_page');
	$edd_settings_page = add_submenu_page('edit.php?post_type=download', __('Easy Digital Download Settings', 'edd'), __('Settings', 'edd'), 'manage_options', 'edd-settings', 'edd_options_page');
}
add_action('admin_menu', 'edd_add_options_link', 10);