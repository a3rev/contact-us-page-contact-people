<?php
/**
 * People Contact Widget
 *
 * @package		People Contact
 * @category	Widgets
 * @author		A3rev
 */

namespace A3Rev\ContactPeople;

class Widget extends \WP_Widget {

	/** constructor */
	function __construct() {
		$widget_ops = array(
			'classname' => 'people_contact_widget',
			'description' => __( "Use this widget to add Business / Organization details, contact details, information, location map and a general contact us form as a widget.", 'contact-us-page-contact-people' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('people_contacts', __('Contact Us Widget', 'contact-us-page-contact-people' ), $widget_ops);
	}

	public static function widget_maps_contact_output($args){
		// No More API Key needed
		global $people_contact_widget_information,$people_contact_widget_maps,$people_contact_grid_view_icon,$people_contact_widget_email_contact_form;
		global $widget_hide_maps_frontend;

		if($widget_hide_maps_frontend == 1){ return; }

		if ( !is_array($args) )
			parse_str( $args, $args );

		extract($args);

		$mode = '';
		$widget_maps_scroll = 'true';
		$streetview = 'off';
		$map_height = $people_contact_widget_maps['widget_map_height'];
		$zoom = $people_contact_widget_maps['widget_zoom_level'];
		$type = $people_contact_widget_maps['widget_map_type'];
		$marker_title = addslashes( $people_contact_widget_information['widget_info_address'] );
		if ( $zoom == '' ) { $zoom = 6; }
		$lang = '';
		$locale = '';
		if(!empty($lang)){
			$locale = ',locale :"'.$lang.'"';
		}
		$extra_params = ',{travelMode:G_TRAVEL_MODE_WALKING,avoidHighways:true '.$locale.'}';

		if(empty($map_height)) { $map_height = 150;}

		$google_map_api_key = '';
		if ( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->is_valid_google_map_api_key() ) {
			$google_map_api_key = get_option( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->google_map_api_key_option, '' );
		}

		if ( ! empty( $google_map_api_key ) ) {
			$google_map_api_key = '&key=' . $google_map_api_key;
		}

		wp_enqueue_script('maps-googleapis','https://maps.googleapis.com/maps/api/js?v=3.exp' . $google_map_api_key );
		?>
		<div id="single_map_people_contact" style="width:100%; height: <?php echo $map_height; ?>px"></div>
		<script src="<?php echo PEOPLE_CONTACT_JS_URL; ?>/markers.js" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				function initialize() {
				<?php if($streetview == 'on'){ ?>
				<?php } else { ?>

					<?php switch ($type) {
							case 'G_NORMAL_MAP':
								$type = 'ROADMAP';
								break;
							case 'G_SATELLITE_MAP':
								$type = 'SATELLITE';
								break;
							case 'G_HYBRID_MAP':
								$type = 'HYBRID';
								break;
							case 'G_PHYSICAL_MAP':
								$type = 'TERRAIN';
								break;
							default:
								$type = 'ROADMAP';
								break;
					} ?>
					<?php

					$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($geocoords).'&sensor=false' . $google_map_api_key;
					$response = wp_remote_get( $url, array( 'timeout' => 120 ) );
					$geodata = wp_remote_retrieve_body( $response );
					$geodata = json_decode($geodata);
					$center_lat = $geodata->results[0]->geometry->location->lat;
					$center_lng = $geodata->results[0]->geometry->location->lng;

					$latlng_center = $center_lat.','.$center_lng;
					?>
					var myLatlng = new google.maps.LatLng(<?php echo $latlng_center; ?>);
					var myOptions = {
					  zoom: <?php echo $zoom; ?>,
					  center: myLatlng,
					  mapTypeId: google.maps.MapTypeId.<?php echo $type; ?>
					};
					var map = new google.maps.Map(document.getElementById("single_map_people_contact"),  myOptions);
					<?php if($widget_maps_scroll == 'true'){ ?>
					map.scrollwheel = false;
					<?php } ?>

					<?php if($mode == 'directions'){ ?>
					directionsPanel = document.getElementById("featured-route");
					directions = new GDirections(map, directionsPanel);
					directions.load("from: <?php echo $from; ?> to: <?php echo $to; ?>" <?php if($walking == 'on'){ echo $extra_params;} ?>);
					<?php
					} else { ?>

						var point = new google.maps.LatLng(<?php echo $latlng_center; ?>);
						var root = "<?php echo PEOPLE_CONTACT_URL.'/assets'; ?>";
						var callout = '<?php echo preg_replace("/[\n\r]/","<br/>",$people_contact_widget_maps['widget_maps_callout_text']); ?>';
						var the_link = '<?php echo get_permalink(get_the_id()); ?>';
						<?php $title = str_replace(array('&#8220;','&#8221;'),'"', $marker_title); ?>
						<?php $title = str_replace('&#8211;','-',$title); ?>
						<?php $title = str_replace('&#8217;',"`",$title); ?>
						<?php $title = str_replace('&#038;','&',$title); ?>
						var the_title = '<?php echo html_entity_decode($title) ?>';

					<?php
					if(is_page()){
						$custom = get_option('woo_cat_custom_marker_pages');
						if(!empty($custom)){
							$color = $custom;
						}
						else {
							$color = get_option('woo_cat_colors_pages');
							if (empty($color)) {
								$color = 'red';
							}
						}
					?>
						var color = '<?php echo $color; ?>';
						createMarker(map,point,root,the_link,the_title,color,callout);
					<?php } else { ?>
						var color = '<?php echo get_option('woo_cat_colors_pages'); ?>';
						createMarker(map,point,root,the_link,the_title,color,callout);
					<?php
					}
						if(isset($_POST['woo_maps_directions_search'])){ ?>

						directionsPanel = document.getElementById("featured-route");
						directions = new GDirections(map, directionsPanel);
						directions.load("from: <?php echo htmlspecialchars($_POST['woo_maps_directions_search']); ?> to: <?php echo $address; ?>" <?php if($walking == 'on'){ echo $extra_params;} ?>);



						directionsDisplay = new google.maps.DirectionsRenderer();
						directionsDisplay.setMap(map);
						directionsDisplay.setPanel(document.getElementById("featured-route"));

						<?php if($walking == 'on'){ ?>
						var travelmodesetting = google.maps.DirectionsTravelMode.WALKING;
						<?php } else { ?>
						var travelmodesetting = google.maps.DirectionsTravelMode.DRIVING;
						<?php } ?>
						var start = '<?php echo htmlspecialchars($_POST['woo_maps_directions_search']); ?>';
						var end = '<?php echo $address; ?>';
						var request = {
							origin:start,
							destination:end,
							travelMode: travelmodesetting
						};
						directionsService.route(request, function(response, status) {
							if (status == google.maps.DirectionsStatus.OK) {
								directionsDisplay.setDirections(response);
							}
						});

						<?php } ?>
					<?php } ?>
				<?php } ?>


				  }
				  function handleNoFlash(errorCode) {
					  if (errorCode == FLASH_UNAVAILABLE) {
						alert("Error: Flash doesn't appear to be supported by your browser");
						return;
					  }
					 }



			initialize();

			});

		</script>

	<?php
	}


	public static function widget_contact_form($widget_id = 0){
		global $people_contact_widget_information,$people_contact_widget_maps,$people_contact_grid_view_icon,$people_contact_widget_email_contact_form, $people_contact_widget_content_before_maps, $people_contact_widget_content_after_maps;

		$enable_widget_send_copy = true;
		if ( $people_contact_widget_email_contact_form['widget_send_copy'] == 'no') $enable_widget_send_copy = false;

		$show_acceptance = true;
		if ( isset( $people_contact_widget_email_contact_form['acceptance'] ) && $people_contact_widget_email_contact_form['acceptance'] == 'no') $show_acceptance = false;

		wp_enqueue_style( 'people_contact_style' );

		$nameError       = '';
		$emailError      = '';
		$contactError    = '';
		$agreeTermsError = '';
		//If the form is submitted
		if( isset( $_POST['submitted'] ) ) {


				//Check to make sure that the name field is not empty
				if( trim( $_POST['contactName'] ) === '' ) {
					$nameError =  __( 'You forgot to enter your name.', 'contact-us-page-contact-people' );
					$hasError = true;
				} else {
					$name = trim( sanitize_text_field( $_POST['contactName'] ) );
				}

				//Check to make sure sure that a valid email address is submitted
				if( trim( $_POST['email'] ) === '' )  {
					$emailError = __( 'You forgot to enter your email address.', 'contact-us-page-contact-people' );
					$hasError = true;
				} else if ( ! filter_var( trim($_POST['email'] ), FILTER_VALIDATE_EMAIL ) ) {
					$emailError = __( 'You entered an invalid email address.', 'contact-us-page-contact-people' );
					$hasError = true;
				} else {
					$email = trim( sanitize_text_field( $_POST['email'] ) );
				}

				//Check to make sure comments were entered
				if( trim( $_POST['comments'] ) === '' ) {
					$contactError = __( 'You forgot to enter your comments.', 'contact-us-page-contact-people' );
					$hasError = true;
				} else {
					$comments = sanitize_textarea_field( trim( $_POST['comments'] ) );
				}

				if ( $show_acceptance && ( ! isset( $_POST['agree_terms'] ) || 1 != $_POST['agree_terms'] ) ) {
					$agreeTermsError = people_ict_t__( 'Default Form - Agree Terms Error', __('You need to agree to the website terms and conditions if want to submit this inquiry', 'contact-us-page-contact-people' ) );
					$hasError = true;
				}

				//If there is no error, send the email
				if( ! isset( $hasError ) ) {

					$emailTo = $people_contact_widget_information['widget_info_email'];
					$subject = __( 'Contact Form Submission from', 'contact-us-page-contact-people' ). ' ' .$name;

					$contact_data = array(
						'subject' 			=> $subject,
						'to_email' 			=> $emailTo,
						'contact_name'		=> $name,
						'contact_email'		=> $email,
						'message'			=> $comments,
					);

					$send_copy_yourself = 0;
					if( isset( $_POST['sendCopy'] ) ) {
						$send_copy_yourself = 1;
					}

					$email_result = Contact_Functions::contact_to_site( $contact_data, $send_copy_yourself );

					$emailSent = true;

				}
		}
		?>
		<script type="text/javascript">
			<!--//--><![CDATA[//><!--
			jQuery(document).ready(function() {
				jQuery( 'form#contactForm').on('submit', function() {
					jQuery( 'form#contactForm .error').remove();
					jQuery( 'form#contactForm input.submit').prop('disabled', true);
					var hasError = false;

					jQuery( '.requiredField').each(function() {
						if(jQuery(this).val().trim() == '') {
							var labelText = jQuery(this).prev( 'label').text();
							jQuery(this).parent().append( '<span class="error"><?php _e( 'You forgot to enter your', 'contact-us-page-contact-people' ); ?> '+labelText+'.</span>' );
							jQuery(this).addClass( 'inputError' );
							hasError = true;
						} else if(jQuery(this).hasClass( 'email')) {
							var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
							if(!emailReg.test(jQuery(this).val().trim())) {
								var labelText = jQuery(this).prev( 'label').text();
								jQuery(this).parent().append( '<span class="error"><?php _e( 'You entered an invalid', 'contact-us-page-contact-people' ); ?> '+labelText+'.</span>' );
								jQuery(this).addClass( 'inputError' );
								hasError = true;
							}
						}
					});

					<?php if ( $show_acceptance ) { ?>
					var agree_terms = jQuery(this).find('.agree_terms');
					var is_agree_terms = 0;
					if ( agree_terms.is(':checked') ) {
						is_agree_terms = 1;
					}

					if ( 0 === is_agree_terms ) {
						agree_terms.parents('li.agree_inline').append( '<span class="error"><?php people_ict_t_e( 'Default Form - Agree Terms Error', __('You need to agree to the website terms and conditions if want to submit this inquiry', 'contact-us-page-contact-people' ) ); ?></span>' );
						hasError = true;
					}
					<?php } ?>

					if(!hasError) {
						jQuery(this).find('.contact-site-ajax-wait').show();
						var formInput = jQuery(this).serialize();
						jQuery.post(jQuery(this).attr( 'action'),formInput, function(data){
							jQuery( 'form#contactForm').slideUp( "fast", function() {
								jQuery(this).before( '<p class="tick"><?php _e( '<strong>Thanks!</strong> Your email was successfully sent.', 'contact-us-page-contact-people' ); ?></p>' );
							});
						});
					} else {
						jQuery( 'form#contactForm input.submit').prop('disabled',false);
					}

					return false;

				});
			});
			//-->!]]>
			</script>
            <div class="people_contact_widget_container">
	            <?php if ($people_contact_widget_content_before_maps != '') { ?>
	            <div class="content_before_maps"><?php echo wpautop( wptexturize( stripslashes( $people_contact_widget_content_before_maps ) ) );?></div>
	            <?php } ?>
				<?php
				$geocoords = $people_contact_widget_maps['widget_location'];
				?>
				<?php if ($geocoords != '') { ?>
				<div style="clear:both;"></div>
				<?php self::widget_maps_contact_output("geocoords=$geocoords"); ?>
				<?php } ?>
	            <?php if ($people_contact_widget_content_after_maps != '') { ?>
	            <div style="clear:both;"></div>
	            <div class="content_after_maps"><?php echo wpautop( wptexturize( stripslashes( $people_contact_widget_content_after_maps ) ) );?></div>
	            <?php } ?>
				<?php
				$phone_icon = $people_contact_grid_view_icon['grid_view_icon_phone'];
				if( trim($phone_icon ) == '' ) $phone_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_phone.png';
				$fax_icon = $people_contact_grid_view_icon['grid_view_icon_fax'];
				if( trim($fax_icon ) == '' ) $fax_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_fax.png';
				$mobile_icon = $people_contact_grid_view_icon['grid_view_icon_mobile'];
				if( trim($mobile_icon ) == '' ) $mobile_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_mobile.png';
				$email_icon = $people_contact_grid_view_icon['grid_view_icon_email'];
				if( trim($email_icon ) == '' ) $email_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_email.png';
				if( isset( $emailSent ) && $emailSent == true ) { ?>
					<p class="info">
					  <?php _e( 'Your email was successfully sent.', 'contact-us-page-contact-people' ); ?>
					</p>
				<?php } else { ?>
					<div style="clear:both;"></div>
					<div class="location-twitter">
					  <section id="office-location">
						<ul>
						  <?php if (isset($people_contact_widget_information['widget_info_address']) && $people_contact_widget_information['widget_info_address'] != '' ) { ?>
						  <li><h4 style="margin-bottom:10px;"><?php echo $people_contact_widget_information['widget_info_address']; ?></h4></li>
						  <?php } ?>
						  <?php if (isset($people_contact_widget_information['widget_info_phone']) && $people_contact_widget_information['widget_info_phone'] != '' ) { ?>
						  <li>
							<span><img src="<?php echo $phone_icon;?>" /></span><?php _e('Phone:', 'contact-us-page-contact-people' ); ?>
							<?php echo $people_contact_widget_information['widget_info_phone']; ?></li>
						  <?php } ?>
						  <?php if (isset($people_contact_widget_information['widget_info_fax']) && $people_contact_widget_information['widget_info_fax'] != '' ) { ?>
						  <li>
							<span><img src="<?php echo $fax_icon;?>" /></span><?php _e('Fax:', 'contact-us-page-contact-people' ); ?>
							<?php echo $people_contact_widget_information['widget_info_fax']; ?></li>
						  <?php } ?>
	                      <?php if (isset($people_contact_widget_information['widget_info_mobile']) && $people_contact_widget_information['widget_info_mobile'] != '' ) { ?>
						  <li>
							<span><img src="<?php echo $mobile_icon;?>" /></span><?php _e('Mobile:', 'contact-us-page-contact-people' ); ?>
							<?php echo $people_contact_widget_information['widget_info_mobile']; ?></li>
						  <?php } ?>
						  <?php if (isset($people_contact_widget_information['widget_info_email']) && $people_contact_widget_information['widget_info_email'] != '' ) { ?>
						  <li>
							<span><img src="<?php echo $email_icon;?>" /></span><?php _e('Email:', 'contact-us-page-contact-people' ); ?>
							<a href="mailto:<?php echo $people_contact_widget_information['widget_info_email']; ?>"><?php echo $people_contact_widget_information['widget_info_email']; ?></a></li>
						  <?php } ?>
						</ul>
					  </section>
					  <div class="clear"></div>
					</div>
					<!-- /.location-twitter -->
					<?php

					if( $people_contact_widget_email_contact_form['widget_show_contact_form'] != 1 && trim($people_contact_widget_email_contact_form['widget_input_shortcode']) != ''){
						echo '<div class="clear" style="clear:both;"></div><div class="people_widget_shortcode">';
						$widget_input_shortcode = htmlspecialchars_decode($people_contact_widget_email_contact_form['widget_input_shortcode']);
						echo do_shortcode( $widget_input_shortcode );
						echo '</div><div class="clear" style="clear:both;"></div>';
					}

	                if($people_contact_widget_email_contact_form['widget_show_contact_form'] == 1){
	                ?>
					<?php if( isset( $hasError ) ) { ?>
					<p class="alert">
					  <?php _e( 'There was an error submitting the form.', 'contact-us-page-contact-people' ); ?>
					</p>
					<?php } ?>
					<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
					  <ol class="forms">
						<li>
						  <label for="contactName">
							<?php _e( 'Name', 'contact-us-page-contact-people' ); ?> <span class="require">*</span>
						  </label>
						  <input type="text" name="contactName" id="contactName" value="<?php if( isset( $_POST['contactName'] ) ) { echo esc_attr( $_POST['contactName'] ); } ?>" class="txt requiredField" />
						  <?php if($nameError != '') { ?>
						  <span class="error"><?php echo $nameError;?></span>
						  <?php } ?>
						</li>
						<li>
						  <label for="email">
							<?php _e( 'Email', 'contact-us-page-contact-people' ); ?> <span class="require">*</span>
						  </label>
						  <input type="text" name="email" id="email" value="<?php if( isset( $_POST['email'] ) ) { echo esc_attr( $_POST['email'] ); } ?>" class="txt requiredField email" />
						  <?php if($emailError != '') { ?>
						  <span class="error"><?php echo $emailError;?></span>
						  <?php } ?>
						</li>
						<li class="textarea">
						  <label for="commentsText">
							<?php _e( 'Message', 'contact-us-page-contact-people' ); ?> <span class="require">*</span>
						  </label>
						  <textarea name="comments" id="commentsText" rows="5" class="requiredField"><?php if( isset( $_POST['comments'] ) ) { echo esc_textarea( $_POST['comments'] ); } ?></textarea>
						  <?php if( $contactError != '' ) { ?>
						  <span class="error"><?php echo $contactError; ?></span>
						  <?php } ?>
						</li>
	                    <?php if ($enable_widget_send_copy) { ?>
						<li class="inline">
	                     <label>
						  <span for="sendCopy">
	                      	<input type="checkbox" name="sendCopy" id="sendCopy" value="true"<?php if( isset( $_POST['sendCopy'] ) && $_POST['sendCopy'] == true ) { echo ' checked="checked"'; } ?> />
							<?php _e( 'Send a copy of this email to yourself', 'contact-us-page-contact-people' ); ?>
						  </span>
						 </label>
	                    </li>
	                    <?php } ?>

	                    <?php if ( $show_acceptance ) { ?>
	                    <?php $information_text = get_option( 'people_contact_widget_information_text', '' ); ?>
            			<?php if ( ! empty( $information_text ) ) { ?>
	                    <li class="inline">
	                    	<p><?php echo stripslashes( $information_text ); ?></p>
	                    </li>
	                    <?php } ?>

	                    <?php $condition_text = get_option( 'people_contact_widget_condition_text', '' ); ?>
						<?php if ( empty( $condition_text ) ) { $condition_text = __( 'I have read and agree to the website terms and conditions', 'contact-us-page-contact-people' ); } ?>
	                    <li class="agree_inline">
	                     <label>
	                     	<input type="checkbox" name="agree_terms" class="agree_terms" value="1"> <?php echo stripslashes( $condition_text ); ?>
	                     </label>
	                      <?php if($agreeTermsError != '') { ?>
						  <span class="error"><?php echo $agreeTermsError;?></span>
						  <?php } ?>
	                    </li>
	                    <?php } ?>

						<li class="buttons">
						  <input type="hidden" name="submitted" id="submitted" value="true" />
						  <input class="submit button" type="submit" value="<?php esc_attr_e( 'Send', 'contact-us-page-contact-people' ); ?>" /> <img class="contact-site-ajax-wait" src="<?php echo PEOPLE_CONTACT_IMAGE_URL; ?>/ajax-loader2.gif" border="0" style="display:none; padding:0; margin:0; vertical-align: middle;" />
						</li>
					  </ol>
					</form>
			<?php
			}
		}
		?>
		</div>
		<?php
	}

	/** @see WP_Widget */
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( trim($title) != '' ) echo $before_title . $title . $after_title;
		$this->widget_contact_form();
		echo $after_widget;
	}

	/** @see WP_Widget->update */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	/** @see WP_Widget->form */
	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
	    $title = strip_tags($instance['title']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'contact-us-page-contact-people' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
<?php
	}

}
