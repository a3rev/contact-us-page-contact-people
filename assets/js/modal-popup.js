(function($) {
	$(document).ready(function() {

		setTimeout( function(){
			$('.contact_people_modal').appendTo('body');
		}, 1000 );

		$('.contact_people_modal').on( 'show.bs.modal', function (event) {
			var button     = $(event.relatedTarget);
			var form_type  = button.data('form_type');

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

			}

		});

	});
})(jQuery);