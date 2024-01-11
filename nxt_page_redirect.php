<?php
/**
 * Plugin Name: nexTab JetPageRedirect
 * Description: Redirects users based on settings in a page / post meta box.
 * Version: 1.1
 * Author: nexTab | Oliver Gehrmann
 * Author URI: https://nextab.de
 */

#region Redirect functionality
function nxt_page_redirect() {
	// if the currently logged in user is an admin, don't do anything (early return)
	if(current_user_can('manage_options')) return;

	$id = get_queried_object_id();
	
	// implement redirect logic according to meta field in WP admin
	$redirect = get_post_meta($id, 'nxt-page-visibility', true);
	if(!$redirect) return;

	// Get the value of the 'nxt-pv-logged-in' meta field.
	$logged_in_only = get_post_meta($id, 'nxt-pv-logged-in', true);
	$url_logged_in = get_post_meta($id, 'nxt-pv-redirect-login', true);

	// If the page is only visible to logged in users and the user is not logged in, redirect.
	if ($logged_in_only != 'false' && !is_user_logged_in() && $url_logged_in != '') {
		wp_safe_redirect($url_logged_in, 302, 'nxt-redirect-logic-loggedin');
		// error_log('Logged in only: ' . var_export($logged_in_only, true));
		// error_log('Is user logged in: ' . var_export(is_user_logged_in(), true));
		exit;
	}

	// Get the value of the 'nxt-pv-logged-out' meta field.
	$logged_out_only = get_post_meta($id, 'nxt-pv-logged-out', true);

	// If the page is only visible to non-logged in users and the user is logged in, redirect.
	if ($logged_out_only != 'false' && is_user_logged_in() && $url_logged_in != '') {
		wp_safe_redirect($url_logged_in, 302, 'nxt-redirect-logic-loggedout');
		exit;
	}
	
	// Get the value of the 'nxt-pv-subs-only' meta field.
	$subs_only = get_post_meta($id,'nxt-pv-subs-only', true);
	$url_permission = get_post_meta($id, 'nxt-pv-redirect-permission', true);
	if(!current_user_can('manage_options') && $subs_only != 'false' && $url_permission != '' && (is_user_logged_in() && current_user_can( 'can_signup_for_events') || !is_user_logged_in())) {
		wp_safe_redirect($url_permission, 302, 'nxt-redirect-confirmed-users');
		exit;
	}

	// Get the value of the 'nxt-pv-user-roles' meta field.
	$user_roles = get_post_meta($id, 'nxt-pv-user-roles', true);

	// If the page is not visible to all users, check if the user has one of the allowed roles.
	$redirect_flag = true;
	if ($user_roles && $url_permission != '') {
		if(!is_user_logged_in()) {
			wp_safe_redirect($url_logged_in, 302, 'nxt-redirect-logic-not-loggedin');
		};
		foreach ($user_roles as $cap) {
			if (current_user_can($cap)) {
				$redirect_flag = false;
			}
		}
	} else {
		$redirect_flag = false;
	}
	if($redirect_flag) {
		wp_safe_redirect($url_permission, 302, 'nxt-redirect-logic-permission');
		exit;
	}
	return;
}
add_action('template_redirect', 'nxt_page_redirect');
#endregion Redirect functionality
