(function($) {
	$(document).ready(function() {

		$(document).on( 'click', '.people_email_inquiry_form_button', function(){

			var button = $(this);

			if ( button.hasClass( 'email_inquiry_sending' ) ) {
				return false;
			}

			button.addClass( 'email_inquiry_sending' );

			var inquiry_form_container       = button.parents( '.custom_contact_popup' );
			var inquiry_form_content         = inquiry_form_container.find( '.people_email_inquiry_content' );
			var inquiry_form_success_message = inquiry_form_container.find( '.people_email_inquiry_success_message' );
			var inquiry_form_error_message   = inquiry_form_container.find( '.people_email_inquiry_error_message' );
			var ajax_wait                    = inquiry_form_container.find( '.ajax-wait' );
			
			var contact_id       = button.data( 'contact_id' );
			var from_page_id     = button.data( 'from_page_id' );
			var name_required    = button.data( 'name_required' );
			var show_phone       = button.data( 'show_phone' );
			var phone_required   = button.data( 'phone_required' );
			var show_subject     = button.data( 'show_subject' );
			var subject_required = button.data( 'subject_required' );
			var message_required = button.data( 'message_required' );
			var show_acceptance  = button.data( 'show_acceptance' );

			var profile_email_obj = inquiry_form_content.find( '.profile_email' );
			var profile_name_obj  = inquiry_form_content.find( '.profile_name' );
			var your_name_obj     = inquiry_form_content.find( '.c_name' );
			var your_email_obj    = inquiry_form_content.find( '.c_email' );
			var your_phone_obj    = inquiry_form_content.find( '.c_phone' );
			var your_subject_obj  = inquiry_form_content.find( '.c_subject' );
			var your_message_obj  = inquiry_form_content.find( '.c_message' );
			var send_copy_obj     = inquiry_form_content.find( '.send_copy' );
			var agree_terms_obj   = inquiry_form_content.find( '.agree_terms' );

			var profile_email  = profile_email_obj.val();
			var profile_name   = profile_name_obj.val();
			var your_name      = your_name_obj.val();
			var your_email     = your_email_obj.val();
			var your_phone     = '';
			var your_subject   = '';
			var your_message   = your_message_obj.val();
			var send_copy      = 0;
			var is_agree_terms = 1;

			if ( send_copy_obj.is( ':checked' ) ) {
				send_copy = 1;
			}

			if ( 1 == show_acceptance ) {
				var is_agree_terms = 0;
				if ( agree_terms_obj.is( ':checked' ) ) {
					is_agree_terms = 1;
				}
			}
			
			var people_email_inquiry_error      = '';
			var people_email_inquiry_have_error = false;

			var filter = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

			if ( 1 == name_required ) {
				if ( your_name.replace(/^\s+|\s+$/g, '') == '' ) {
					people_email_inquiry_error += your_name_obj.attr( 'title' ) + ' ' + people_ei_default_vars.required_error + "\n";
					people_email_inquiry_have_error = true;
				}
			}

			if ( your_email.replace(/^\s+|\s+$/g, '') == '' ) {
				people_email_inquiry_error += your_email_obj.attr( 'title' ) + ' ' + people_ei_default_vars.required_error + "\n";
				people_email_inquiry_have_error = true;

			} else if ( !filter.test( your_email ) ) {
				people_email_inquiry_error      += people_ei_default_vars.email_valid_error + "\n";
				people_email_inquiry_have_error = true;
			}

			if ( 1 == show_phone ) {
				your_phone = your_phone_obj.val();

				if ( 1 == phone_required ) {
					if ( your_phone.replace(/^\s+|\s+$/g, '') == '') {
						people_email_inquiry_error      += your_phone_obj.attr( 'title' ) + ' ' + people_ei_default_vars.required_error + "\n";
						people_email_inquiry_have_error = true;
					}
				}
			}

			if ( 1 == show_subject ) {
				your_subject = your_subject_obj.val();

				if ( 1 == subject_required ) {
					if ( your_subject.replace(/^\s+|\s+$/g, '') == '') {
						people_email_inquiry_error      += your_subject_obj.attr( 'title' ) + ' ' + people_ei_default_vars.required_error + "\n";
						people_email_inquiry_have_error = true;
					}
				}
			}

			if ( 1 == message_required ) {
				if ( your_message.replace(/^\s+|\s+$/g, '') == '' ) {
					people_email_inquiry_error += your_message_obj.attr( 'title' ) + ' ' + people_ei_default_vars.required_error + "\n";
					people_email_inquiry_have_error = true;
				}
			}

			if ( 0 === is_agree_terms ) {
				people_email_inquiry_error      += people_ei_default_vars.agree_terms_error + "\n";
				people_email_inquiry_have_error = true;
			}

			if ( people_email_inquiry_have_error ) {
				button.removeClass( 'email_inquiry_sending' );
				alert( people_email_inquiry_error );
				return false;
			}

			ajax_wait.css('display','block');

			var submit_data = {
				action: 		'people_email_inquiry_submit_form',
				contact_id: 	contact_id,
				from_page_id: 	from_page_id,
				profile_email:	profile_email,
				profile_name:	profile_name,
				your_name: 		your_name,
				your_email: 	your_email,
				your_phone: 	your_phone,
				your_subject: 	your_subject,
				your_message: 	your_message,
				send_copy:		send_copy,
				security: 		people_ei_default_vars.security_nonce
			};

			$.ajax({
				type: 	'POST',
				url: 	people_ei_default_vars.ajax_url,
				data: 	submit_data,
				success: function ( response ) {

					if ( 'success' == response.status ) {
						// Success
						button.removeClass( 'email_inquiry_sending' );
						inquiry_form_content.hide();
						inquiry_form_success_message.html( response.message ).show();
						ajax_wait.css('display','none');

					} else {
						// Error
						button.removeClass( 'email_inquiry_sending' );
						inquiry_form_content.hide();
						inquiry_form_error_message.html( response.message ).show();
						ajax_wait.css('display','none');
					}
				},
				error: function( e ) {
					// Error
					button.removeClass( 'email_inquiry_sending' );
					inquiry_form_content.hide();
					inquiry_form_error_message.html( e ).show();
					ajax_wait.css('display','none');
				}
			});

			return false;
		});

	});

})(jQuery);