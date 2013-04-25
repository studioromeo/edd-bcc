<?php
/*
Plugin Name: EDD Receipts BCC
Plugin Uri: https://github.com/studioromeo/edd-bcc
Description: Extends Easy Digital Downloads to send receipts BCC too
Version: 1.0
Author: Robert Rhoades
Author Uri: http://studioromeo.co.uk
*/

/**
 * Copyright (c) 2013 Robert Rhoades. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

add_filter('edd_settings_emails', 'edd_bcc_settings_emails');
function edd_bcc_settings_emails($options) {

	$options['admin_receipt_bcc_emails'] = array(
		'id' => 'admin_receipt_bcc_emails',
		'name' => __( 'Receipt Copy Emails (BCC)', 'edd-bcc' ),
		'desc' => __( 'Enter the email address(es) that should receive a copy of the reciepts anytime a sale is made, one per line', 'edd-bcc' ),
		'type' => 'textarea'
	);

	return $options;
}

add_filter( 'edd_receipt_headers', 'edd_bcc_receipt_headers' );
add_filter( 'edd_test_purchase_headers', 'edd_bcc_receipt_headers' );
function edd_bcc_receipt_headers($headers) {
	return $headers . "Bcc:" . implode(' ,', edd_bcc_get_admin_receipt_bcc_emails()) . "\r\n";
}

function edd_bcc_get_admin_receipt_bcc_emails() {
	global $edd_options;

	$emails = isset( $edd_options['admin_receipt_bcc_emails'] ) && strlen( trim( $edd_options['admin_receipt_bcc_emails'] ) ) > 0 ? $edd_options['admin_receipt_bcc_emails'] : get_bloginfo( 'admin_email' );
	$emails = array_map( 'trim', explode( "\n", $emails ) );

	return apply_filters( 'edd_admin_receipt_bcc_emails', $emails );
}