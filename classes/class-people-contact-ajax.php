<?php

namespace A3Rev\ContactPeople;

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Ajax
{

	public function __construct() {
		$this->add_ajax_events();
	}

	/**
	 * Hook in methods - uses WordPress ajax handlers (admin-ajax).
	 */
	public function add_ajax_events() {
		$ajax_events = array(
			'email_inquiry_submit_form' => true,
		);

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_people_' . $ajax_event, array( $this, str_replace( '-', '_', $ajax_event ) . '_ajax' ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_people_' . $ajax_event, array( $this, str_replace( '-', '_', $ajax_event ) . '_ajax' ) );
			}
		}
	}

	public function email_inquiry_submit_form_ajax() {

		$json_var = array(
			'status'  => 'error',
			'message' => people_ict_t__( 'Default Form - Contact Not Allow', __( "Sorry, you can't contact at this time.", 'contact-us-page-contact-people' ) ),
		);

		$contact_id         = absint( $_POST['contact_id'] );
		$from_page_id       = isset( $_POST['from_page_id'] ) ? absint( $_POST['from_page_id'] ) : 0;
		$profile_email      = sanitize_text_field( wp_unslash( $_POST['profile_email'] ) );
		$profile_name       = sanitize_text_field( wp_unslash( $_POST['profile_name'] ) );
		$your_name          = sanitize_text_field( wp_unslash( $_POST['your_name'] ) );
		$your_email         = sanitize_text_field( wp_unslash( $_POST['your_email'] ) );
		$your_phone         = sanitize_text_field( wp_unslash( $_POST['your_phone'] ) );
		$your_subject       = sanitize_text_field( wp_unslash( $_POST['your_subject'] ) );
		$your_message       = sanitize_textarea_field( wp_unslash( $_POST['your_message'] ) );
		$send_copy_yourself = sanitize_text_field( wp_unslash( $_POST['send_copy'] ) );

		if ( '' != trim( $your_subject ) ) {
			$subject = trim( $your_subject ). ' ' . people_ict_t__( 'Email Inquiry - from', __('from', 'contact-us-page-contact-people' ) ) . ' ' . ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
		} else {
			$subject = people_ict_t__( 'Email Inquiry - Contact from', __('Contact from', 'contact-us-page-contact-people' ) ).' '. ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
		}

		$profile_data = array(
			'subject' 			=> $subject,
			'to_email' 			=> $profile_email,
			'profile_name'		=> $profile_name,
			'profile_email'		=> $profile_email,
			'contact_name'		=> $your_name,
			'contact_email'		=> $your_email,
			'contact_phone'		=> $your_phone,
			'message'			=> $your_message,
			'from_page_id'		=> $from_page_id,
		);

		$email_result = Contact_Functions::contact_to_people( $profile_data, $send_copy_yourself );

		if ( false !== $email_result ) {
			$json_var['status']  = 'success';
			$json_var['message'] = $email_result;
		}

		wp_send_json( $json_var );

		die();
	}
}
