(function($) {
	$(document).ready(function() {

		setTimeout( function(){
			$('.contact_people_modal').appendTo('body');
		}, 1000 );


		$(window).on('people-modal-scrolltop', function(e) {
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				$('.contact_people_modal,body,html').animate({ scrollTop: 0 }, 'slow');
			}
		});

		$('.contact_people_modal').on( 'show.bs.modal', function (event) {
			var button          = $(event.relatedTarget);
			var form_type       = button.data('form_type');
			var from_page_id    = button.data('from_page_id');
			var from_page_title = button.data('from_page_title');
			var from_page_url   = button.data('from_page_url');

			var modal = $(this);

			if ( 'default' == form_type ) {

				var inquiry_form_content              = modal.find( '.people_email_inquiry_content' );
				var inquiry_form_notification_message = modal.find( '.people_email_inquiry_notification_message' );
				var submit_button                     = modal.find( '.people_email_inquiry_form_button' );
				var ajax_wait                         = modal.find( '.ajax-wait' );

				inquiry_form_content.show();
				inquiry_form_notification_message.hide();
				ajax_wait.css('display','none');
				submit_button.removeClass( 'email_inquiry_sending' );

				inquiry_form_content.find('input[type="text"]').each(function(){
					$(this).val('');
				});
				inquiry_form_content.find('.c_message').val('');
				inquiry_form_content.find('.send_copy').prop( 'checked', false );
				inquiry_form_content.find('.agree_terms').prop( 'checked', false );
				inquiry_form_content.find('.people_email_inquiry_form_button').data( 'from_page_id', from_page_id );

			}

			$(window).trigger( 'people-modal-scrolltop' );

		});

	});
})(jQuery);