<?php

// automatically appends the purchase link to download content, if enabled
function edd_append_purchase_link($content) {
	global $post;
	if($post->post_type == 'download' && is_singular() && is_main_query()) {
		if(!get_post_meta($post->ID, '_edd_hide_purchase_link', true)) {
			$button_text = get_post_meta($post->ID, '_edd_purchase_text', true) ? get_post_meta($post->ID, '_edd_purchase_text', true) : __('Purchase', 'edd');
			$style = get_post_meta($post->ID, '_edd_purchase_style', true) ? get_post_meta($post->ID, '_edd_purchase_style', true) : 'button';
			$color = get_post_meta($post->ID, '_edd_purchase_color', true);
			$content .= edd_get_purchase_link($post->ID, $button_text, $style, $color);
		}
	}
	return $content;
}
add_filter('the_content', 'edd_append_purchase_link');

function edd_get_purchase_link($download_id = null, $link_text = 'Purchase', $style = 'button', $color = 'blue') {
	global $edd_options, $post, $user_ID;

	$page = get_permalink($post->ID); // current page
	$link = add_query_arg('download_id', $download_id, add_query_arg('edd_action', 'add_to_cart', $page));

	if(!edd_has_user_purchased($user_ID, $download_id)) {

		if($style == 'button') {
			$link = '<a href="' . $link . '" class="edd-add-to-cart edd_button edd_' . $color . '" data-action="edd_add_to_cart" data-download-id="' . $download_id . '">';
			 	$link .= '<span class="edd_button_outer"><span class="edd_button_inner">';
					$link .= '<span class="edd_button_text"><span>' . $link_text . '</span><span style="display:none">' . __('Checkout', 'edd') . '</span></span>';
				$link .= '</span></span>';
			$link .= '</a>';
		} else {
			$link = '<a href="' . $link . '" class="edd-add-to-cart" data-action="edd_add_to_cart" data-download-id="' . $download_id . '"><span class="edd_link_text"><span>' . $link_text . '</span><span style="display:none">' . __('Checkout', 'edd') . '</span></span></a>';
		}
		if(edd_is_ajax_enabled()) {
			$link .= '<img src="' . EDD_PLUGIN_URL . 'includes/images/loading.gif" class="edd-cart-ajax" style="display: none;"/>';
			$link .= '&nbsp;<span style="display:none;" class="edd-cart-added-alert">' . __('added to your cart', 'edd') . '</span>';
		}
		return apply_filters('edd_purchase_link', $link);
	} else {
		$link = '<a href="' . $link . '" class="edd-add-to-cart" data-action="edd_add_to_cart" data-download-id="' . $download_id . '">' . __('here', 'edd') . '</a>';
		$checkout_link = '<span style="display:none;"><a href="' . get_permalink($edd_options['purchase_page']) . '">' . __('Checkout', 'edd') . '</a></span>';
		$purchase_link = '<span class="edd_already_purchased">' . __('You have already purchased this item.', 'edd') . ' <span>' . sprintf(__('Click %s to purchase again.', 'edd'), $link) . '</span>' . $checkout_link . '</span>';
		return $purchase_link;
	}
}

function edd_remove_item_url($cart_key, $post, $ajax = false) {
	global $post;
	$current_page = $ajax ? home_url() : get_permalink($post->ID);
	$remove_url = add_query_arg('cart_item', $cart_key, add_query_arg('edd_action', 'remove', $current_page));
	return apply_filters('edd_remove_item_url', $remove_url);
}

function edd_filter_success_page_content($content) {
	
	global $edd_options;
	
	if(isset($edd_options['success_page']) && isset($_GET['payment-confirmation']) && is_page($edd_options['success_page'])) {
		
		if(has_filter('edd_payment_confirm_' . $_GET['payment-confirmation'])) {
			$content = apply_filters('edd_payment_confirm_' . $_GET['payment-confirmation'], $content);
		}
	}
	return $content;
}
add_filter('the_content', 'edd_filter_success_page_content');

function edd_get_button_colors() {
	return apply_filters('edd_button_colors', array('gray', 'pink', 'blue', 'green', 'teal', 'black', 'dark gray', 'orange', 'purple', 'slate'));
}