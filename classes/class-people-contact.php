<?php
/**
 * People Contact Class
 *
 * Table Of Contents
 *
 * People_Contact()
 * init()
 * send_a_contact()
 * load_ajax_contact_form()
 * create_contact_maps();
 */
class People_Contact {
	var $admin_page,$contact_manager;
	public $template_url = PEOPLE_CONTACT_PATH;

	public function __construct () {
		$this->init();
	}

	public function init () {
		add_action( 'wp_enqueue_scripts', array( $this, 'include_modal_popup_at_footer' ) );

		//Add Ajax Send Email
		add_action('wp_ajax_send_a_contact', array( $this, 'send_a_contact'));
		add_action('wp_ajax_nopriv_send_a_contact', array( $this, 'send_a_contact'));
	}

	public function include_modal_popup_at_footer() {
		$use_modal_popup = People_Contact_Functions::check_use_modal_popup();

		if ( $use_modal_popup ) {
			wp_enqueue_script( 'contact-people-modal-popup', PEOPLE_CONTACT_JS_URL . '/modal-popup.js', array( 'jquery' ), PEOPLE_CONTACT_VERSION, true );
		}
	}

	public function send_a_contact(){
		$profile_email = trim($_REQUEST['profile_email']);
		$profile_name = trim($_REQUEST['profile_name']);
		$c_name = trim($_REQUEST['c_name']);
		$c_email = trim($_REQUEST['c_email']);
		$c_phone = trim($_REQUEST['c_phone']);
		$c_message = trim($_REQUEST['c_message']);
		$from_page_id = isset( $_REQUEST['from_page_id'] ) ? $_REQUEST['from_page_id'] : 0;

		$send_copy = $_REQUEST['send_copy'];

		if(trim($_REQUEST['c_subject']) != ''){
			$subject = trim($_REQUEST['c_subject']). ' ' . people_ict_t__( 'Email Inquiry - from', __('from', 'contact-us-page-contact-people' ) ) . ' ' . ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
		}else{
			$subject = people_ict_t__( 'Email Inquiry - Contact from', __('Contact from', 'contact-us-page-contact-people' ) ).' '. ( function_exists('icl_t') ? icl_t( 'WP',__('Blog Title','wpml-string-translation'), get_option('blogname') ) : get_option('blogname') );
		}

		$profile_data = array(
			'subject' 			=> $subject,
			'to_email' 			=> $profile_email,
			'profile_name'		=> $profile_name,
			'profile_email'		=> $profile_email,
			'contact_name'		=> $c_name,
			'contact_email'		=> $c_email,
			'contact_phone'		=> $c_phone,
			'message'			=> $c_message,
			'from_page_id'		=> $from_page_id,
		);
		$email_result = People_Contact_Functions::contact_to_people( $profile_data, $send_copy );

		echo $email_result;

		die();
	}

	public function default_contact_form( $contact_id , $from_page_id = 0 ) {
		global $people_contact_grid_view_icon;
		global $people_email_inquiry_global_settings;

		if ( trim( $people_email_inquiry_global_settings['inquiry_contact_text_button'] ) != '') $inquiry_contact_text_button = $people_email_inquiry_global_settings['inquiry_contact_text_button'];
		else $inquiry_contact_text_button = __('SEND', 'contact-us-page-contact-people' );

		$inquiry_contact_button_class = apply_filters( 'people_inquiry_contact_button_class', '' );
		$inquiry_contact_form_class = apply_filters( 'people_inquiry_contact_form_class', '' );

		$data = People_Contact_Profile_Data::get_row( $contact_id, '', 'ARRAY_A' );

		if ($data['c_avatar'] != '') {
			$src = $data['c_avatar'];
			$c_attachment_id = $data['c_attachment_id'];
		} else {
			$src = $people_contact_grid_view_icon['default_profile_image'];
			$c_attachment_id = $people_contact_grid_view_icon['default_profile_image_attachment_id'];
		}

		$img_output = '<img width="80" class="wp-image-'.$c_attachment_id.'" src="'.$src.'" />';
		if ( function_exists( 'wp_make_content_images_responsive' ) ) {
			$img_output = wp_make_content_images_responsive( $img_output );
		}

		ob_start();
		?>
		<div class="custom_contact_popup <?php echo $inquiry_contact_form_class; ?>">
        <div style="padding:10px;">
		<div style="clear:both"></div>
        <div class="people_email_inquiry_site_name"><?php echo $people_email_inquiry_global_settings['inquiry_form_site_name']; ?></div>
        <div style="clear:both; margin-top:5px"></div>
		<div style="float:left; margin-right:20px;" class="people_email_inquiry_default_image_container"><?php echo $img_output; ?></div>
        <div style="display:block; margin-bottom:10px; padding-left:22%;" class="people_email_inquiry_product_heading_container">
			<div class="people_email_inquiry_profile_position"><?php esc_attr_e( stripslashes(  $data['c_title']) );?></div>
            <div class="people_email_inquiry_profile_name"><?php esc_attr_e( stripslashes(  $data['c_name']) );?></div>
        </div>
		<div style="clear:both;height:1em;"></div>
        <div class="people_email_inquiry_content" id="people_email_inquiry_content_<?php echo $contact_id; ?>">
        	<input type="hidden" value="<?php esc_attr_e( stripslashes( $data['c_email'] ) );?>" id="profile_email_<?php echo $contact_id; ?>" name="profile_email" />
        	<input type="hidden" value="<?php esc_attr_e( stripslashes(  $data['c_title']) );?> <?php esc_attr_e( stripslashes( $data['c_name'] ) );?>" id="profile_name_<?php echo $contact_id; ?>" name="profile_name" />
            <div class="people_email_inquiry_field">
                <label class="people_email_inquiry_label" for="c_name_<?php echo $contact_id; ?>"><?php people_ict_t_e( 'Default Form - Contact Name', __('Name', 'contact-us-page-contact-people' ) ); ?> <span class="gfield_required">*</span></label>
                <input type="text" name="c_name" id="c_name_<?php echo $contact_id; ?>" value="" /></div>
            <div class="people_email_inquiry_field">
                <label class="people_email_inquiry_label" for="c_email_<?php echo $contact_id; ?>"><?php people_ict_t_e( 'Default Form - Contact Email', __('Email', 'contact-us-page-contact-people' ) ); ?> <span class="gfield_required">*</span></label>
                <input type="text" name="c_email" id="c_email_<?php echo $contact_id; ?>" value="" /></div>
            <div class="people_email_inquiry_field">
                <label class="people_email_inquiry_label" for="c_phone_<?php echo $contact_id; ?>"><?php people_ict_t_e( 'Default Form - Contact Phone', __('Phone', 'contact-us-page-contact-people' ) ); ?> <span class="gfield_required">*</span></label>
                <input type="text" name="c_phone" id="c_phone_<?php echo $contact_id; ?>" value="" /></div>
            <div class="people_email_inquiry_field">
                <label class="people_email_inquiry_label" for="c_subject_<?php echo $contact_id; ?>"><?php people_ict_t_e( 'Default Form - Contact Subject', __('Subject', 'contact-us-page-contact-people' ) ); ?> </label>
                <input type="text" name="c_subject" id="c_subject_<?php echo $contact_id; ?>" value="" /></div>
            <div class="people_email_inquiry_field">
                <label class="people_email_inquiry_label" for="c_message_<?php echo $contact_id; ?>"><?php people_ict_t_e( 'Default Form - Contact Message', __('Message', 'contact-us-page-contact-people' ) ); ?> <span class="gfield_required">*</span></label>
                <textarea rows="3" name="c_message" id="c_message_<?php echo $contact_id; ?>"></textarea></div>
            <div class="people_email_inquiry_field">
                <?php if ( $people_email_inquiry_global_settings['send_copy'] != 'no' ) { ?>
                <label class="people_email_inquiry_label">&nbsp;</label>
                <label class="people_email_inquiry_send_copy"><input type="checkbox" name="send_copy" id="send_copy_<?php echo $contact_id; ?>" value="1" checked="checked" /> <?php people_ict_t_e( 'Default Form - Send Copy', __('Send a copy of this email to myself.', 'contact-us-page-contact-people' ) ); ?></label>
                <?php } ?>
                <a class="people_email_inquiry_form_button <?php echo $inquiry_contact_button_class; ?>" id="people_email_inquiry_bt_<?php echo $contact_id; ?>" contact_id="<?php echo $contact_id; ?>" from-page-id="<?php echo $from_page_id; ?>"><?php echo $inquiry_contact_text_button; ?></a>
            </div>
            <div style="clear:both"></div>
        </div>
		<div style="clear:both"></div>
		<div class="ajax-wait">&nbsp;</div>
        </div>
        </div>
		<?php

		$output = ob_get_clean();

		$output = People_Contact_Functions::modal_popup_container( $output );

		return $output;
	}

	public function create_contact_maps($contacts = array() ) {
		global $people_contact_form_ids;

		if ( empty( $people_contact_form_ids ) ) {
			$people_contact_form_ids = array();
		}

		global $people_email_inquiry_global_settings;
		global $people_contact_global_settings, $people_contact_grid_view_layout, $people_contact_location_map_settings, $people_contact_grid_view_icon;
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		global $contact_people_page_id;
		if( !is_page() || ($contact_people_page_id != get_the_ID()) ) return;
		if( !is_array($contacts) || count ($contacts) <= 0 ) return;

		$use_modal_popup = People_Contact_Functions::check_use_modal_popup();

		wp_enqueue_script( 'jquery' );
		People_Contact_Hook_Filter::frontend_scripts_enqueue();

		$show_map = ( $people_contact_location_map_settings['hide_maps_frontend'] != 1 ) ? 1 : 0 ;

		if ( $show_map != 0 ) {
			$google_map_api_key = $people_contact_global_settings['google_map_api_key'];
			wp_enqueue_script('maps-googleapis','https://maps.googleapis.com/maps/api/js?v=3.exp&key=' . $google_map_api_key );
		}

		if ( $use_modal_popup ) {
			People_Contact_Functions::enqueue_modal_scripts();
		}

		$unique_id = rand(100,10000);

		$profile_email_page_link = '#';

		global $post;

		$grid_view_col = $people_contact_global_settings['grid_view_col'];

		$phone_icon = $people_contact_grid_view_icon['grid_view_icon_phone'];
		if( trim($phone_icon ) == '' ) $phone_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_phone.png';
		$fax_icon = $people_contact_grid_view_icon['grid_view_icon_fax'];
		if( trim($fax_icon ) == '' ) $fax_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_fax.png';
		$mobile_icon = $people_contact_grid_view_icon['grid_view_icon_mobile'];
		if( trim($mobile_icon ) == '' ) $mobile_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_mobile.png';
		$email_icon = $people_contact_grid_view_icon['grid_view_icon_email'];
		if( trim($email_icon ) == '' ) $email_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_email.png';
		$website_icon = $people_contact_grid_view_icon['grid_view_icon_website'];
		if( trim($website_icon ) == '' ) $website_icon = PEOPLE_CONTACT_IMAGE_URL.'/p_icon_website.png';

			$zoom_level           = $people_contact_location_map_settings['zoom_level'];
			$map_type             = $people_contact_location_map_settings['map_type'];
			$map_width_type       = $people_contact_location_map_settings['map_width_type'];
			$map_width_responsive = $people_contact_location_map_settings['map_width_responsive'];
			$map_width_fixed      = $people_contact_location_map_settings['map_width_fixed'];
			$map_height           = $people_contact_location_map_settings['map_height'];

		if ( '' == $map_type ) {
			$map_type = 'ROADMAP';
		}
		if ( $zoom_level <= 0 ) {
			$zoom_level = 16;
		}
		if ( $map_height <= 0 ) {
			$map_height = '400';
		}

		if ( $map_width_type == 'px' ) {
			$map_width_type = 'px';
			if ( $map_width_fixed <= 0 ) {
				$map_width = 100;
			} else {
				$map_width = $map_width_fixed;
			}
		} else {
			$map_width_type = '%';
			$map_width = $map_width_responsive;
		}
		?>

		<script type="text/javascript">
		<?php
		if ( $show_map != 0 ) {
			?>
			var infowindow = null;

			jQuery(document).ready(function() {
				initialize<?php echo $unique_id; ?>();
			});

			function initialize<?php echo $unique_id; ?>() {

				if ( sites<?php echo $unique_id; ?>.length < 1 ) return false;

				var myOptions = {
					zoom: <?php echo $zoom_level;?>,
					mapTypeId: google.maps.MapTypeId.<?php echo $map_type;?>
				}
				var map = new google.maps.Map(document.getElementById("map_canvas<?php echo $unique_id; ?>"), myOptions);

				setMarkers<?php echo $unique_id; ?>(map, sites<?php echo $unique_id; ?>);
				infowindow = new google.maps.InfoWindow({
					content: "loading..."
				});
				var bikeLayer = new google.maps.BicyclingLayer();
				bikeLayer.setMap(map);
			}

			var sites<?php echo $unique_id; ?> = [];
			<?php
			$i = 0;
			if(is_array($contacts) && count($contacts) > 0 ){
				foreach($contacts as $key=>$value){
					$profile_id = $value['id'];

					$update_lat_lng = false;

					if ( 0 == $value['enable_map_marker'] ) continue;

					if ( (trim($value['c_latitude']) == '' || trim($value['c_longitude']) == '' ) && trim($value['c_address']) != '') {
						$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($value['c_address']).'&sensor=false';
						$geodata = file_get_contents($url);
						$geodata = json_decode($geodata);
						$value['c_latitude'] = $geodata->results[0]->geometry->location->lat;
						$value['c_longitude'] = $geodata->results[0]->geometry->location->lng;

						$update_lat_lng = true;
					}

					if ( trim( $value['c_latitude'] ) == '' || trim( $value['c_longitude'] ) == '' ) continue;

					if ( $update_lat_lng ) {
						$contacts[$key]['c_latitude'] = $value['c_latitude'];
						$contacts[$key]['c_longitude'] = $value['c_longitude'];
						People_Contact_Profile_Data::set_lat_lng( $profile_id, $value['c_latitude'], $value['c_longitude'] );
					}

					$i++;

					if ( $value['c_avatar'] != '' ) {
						$src = $value['c_avatar'];
					} else {
						$src = $people_contact_grid_view_icon['default_profile_image'];
					}

					if ( class_exists('People_Contact_3RD_ContactForm_Functions') && People_Contact_3RD_ContactForm_Functions::check_enable_3rd_contact_form() ) {
						if ( '' == trim( $value['c_shortcode'] ) && '' == trim($people_email_inquiry_global_settings['contact_form_type_shortcode']) ) {
							$value['c_email'] = '';
						}
					}
					$profile_item = "['".esc_attr( stripslashes( $value['c_name']))."',".$value['c_latitude'].",".$value['c_longitude'].",".$i.",'".esc_attr( stripslashes( $value['c_address']))."',".$profile_id.",'".$src."','".trim(esc_attr( stripslashes( $value['c_phone'])))."','".esc_attr( stripslashes( $value['c_title']))."','".trim(esc_attr( stripslashes( $value['c_fax'])))."','".trim(esc_attr( stripslashes( $value['c_mobile'])))."','".trim(esc_url( stripslashes( $value['c_website'])))."','".trim(esc_attr( stripslashes( $value['c_email'])))."']";
			?>
			sites<?php echo $unique_id; ?>.push(<?php echo $profile_item; ?>);
			<?php
				}
				if ( $i < 1 ) {
					$show_map = 0;
				}
			} else {
				$show_map = 0;
			}
			?>

			function setMarkers<?php echo $unique_id; ?>(map, markers) {
				var infotext = '';
				var bounds = new google.maps.LatLngBounds ();
				jQuery.each( markers, function ( i, sites ) {
					var current_object = jQuery("div.people_item<?php echo $unique_id; ?>.people_item_id" + sites[5]);
					var siteLatLng = new google.maps.LatLng(sites[1], sites[2]);
					bounds.extend (siteLatLng);
					infotext = '<div class="infowindow"><p class="info_title">'+sites[8]+'</p><div class="info_avatar"><img src="'+sites[6]+'" /></div><div><p class="info_title2">'+sites[0]+'</p>';
					if (sites[4] != '') infotext += '<p class="info_address">'+sites[4]+'</p>';

					if (sites[12] != '') {
						infotext += '<p><span class="p_icon_email"><img src="<?php echo $email_icon;?>" style="width:auto;height:auto" /></span> ';
						infotext += '<a data-toggle="modal" href="#contact_people_modal_'+sites[5]+'"><?php echo $people_contact_grid_view_icon['grid_view_email_text']; ?></a>';
						infotext += '</p>';
					}

					infotext += '</div></div>';
					var marker = new google.maps.Marker({
						position: siteLatLng,
						map: map,
						title: sites[0],
						zIndex: sites[3],
						html: infotext,
						c_id: sites[5]/*,
						icon :  "/images/market.png"*/
					});
					if ( typeof(sites[1]) != 'undefined' && sites[1] != '' && typeof(sites[2]) != 'undefined' && sites[2] != '' ) {
						current_object.on( 'click', function(i){
							var target = jQuery( i.target );
							if ( ! target.is( "a" ) ) {
								div_container = jQuery(this).parents('#people_contacts_container');
								jQuery('html, body').animate({
									scrollTop: div_container.offset().top - 40
								}, 200 );
							}
							map.setCenter(siteLatLng);
							infowindow.setContent(marker.html);
							infowindow.open(map, marker);
						});
					}

					if (sites[12] != '') {
						google.maps.event.addListener(marker, "click", function () {
						var c_id = this.c_id;
							jQuery( '#contact_people_bt_'+c_id+'_<?php echo $unique_id; ?>' ).click();

							return false;
						})
					}

					if ( typeof(sites[1]) != 'undefined' && sites[1] != '' && typeof(sites[2]) != 'undefined' && sites[2] != '' ) {
						google.maps.event.addListener(marker, 'mouseout', function() {
						   //infowindow.close();
						});
						google.maps.event.addListener(marker, "mouseover", function () {
							infowindow.setContent(this.html);
							infowindow.open(map, this);
						});
					}
				});
				map.setCenter(bounds.getCenter());
				map.fitBounds(bounds);

				google.maps.event.addListenerOnce(map, 'idle', function(){
					if( map.getZoom() > <?php echo (int) $zoom_level; ?> ){
						map.setZoom(<?php echo $zoom_level;?>);
					}
				});
			}
			<?php } ?>

			var popupWindow<?php echo $unique_id; ?>=null;

			function profile_popup<?php echo $unique_id; ?>(url, target_link ){
				window.open(url, target_link);
			}

			function profile_parent_disable<?php echo $unique_id; ?>() {
				if(popupWindow<?php echo $unique_id; ?> && !popupWindow<?php echo $unique_id; ?>.closed)
				popupWindow<?php echo $unique_id; ?>.focus();
			}
		</script>
    	<?php
		wp_enqueue_script( 'jquery-masonry' );

		global $wp_version;
		$cur_wp_version = preg_replace('/-.*$/', '', $wp_version);
		?>
        <script type="text/javascript">
        jQuery(window).load(function(){
			var grid_view_col = <?php echo $grid_view_col;?>;
			var screen_width = jQuery('body').width();
			if(screen_width <= 750 && screen_width >= 481 ){
				grid_view_col = 2;
			}
			jQuery('.people_box_content<?php echo $unique_id; ?>').imagesLoaded(function(){
				jQuery('.people_box_content<?php echo $unique_id; ?>').masonry({
					itemSelector: '.people_item<?php echo $unique_id; ?>',
					<?php if ( version_compare( $cur_wp_version, '3.9', '<' ) ) { ?>
					columnWidth: jQuery('.people_box_content<?php echo $unique_id; ?>').width()/grid_view_col
					<?php } else { ?>
					columnWidth: '.people-grid-sizer'
					<?php } ?>
				});
			});
		});
		jQuery(window).resize(function() {
			var grid_view_col = <?php echo $grid_view_col;?>;
			var screen_width = jQuery('body').width();
			if(screen_width <= 750 && screen_width >= 481 ){
				grid_view_col = 2;
			}
			jQuery('.people_box_content<?php echo $unique_id; ?>').imagesLoaded(function(){
				jQuery('.people_box_content<?php echo $unique_id; ?>').masonry({
					itemSelector: '.people_item<?php echo $unique_id; ?>',
					<?php if ( version_compare( $cur_wp_version, '3.9', '<' ) ) { ?>
					columnWidth: jQuery('.people_box_content<?php echo $unique_id; ?>').width()/grid_view_col
					<?php } else { ?>
					columnWidth: '.people-grid-sizer'
					<?php } ?>
				});
			});
		});
		</script>
		<?php
		$html = '';
		if( $show_map != 0 ){
			$html .= '<div style="clear:both"></div>';
			$html .= '<div class="people-entry">';
			$html .= '<div style="clear:both"></div>';

			$html .= '<div id="map_canvas'.$unique_id.'" class="map_canvas_container" style="width: '.$map_width.$map_width_type.'; height: '.$map_height.'px;float:left;"></div>';
			$html .= '<div style="clear:both;margin-bottom:0em;" class="custom_title"></div>';
			$html .= '<div style="clear:both;height:15px;"></div>';
			$html .= '</div>';
		}

		$grid_view_team_title = trim($people_contact_global_settings['grid_view_team_title']);

		if ( $grid_view_team_title != '' ) {
			$html .= '<div class="custom_box_title"><h1 class="p_title">'.$grid_view_team_title.'</h1></div>';
		}
		$html .= '<div style="clear:both;margin-bottom:1em;"></div>';
		$html .= '<div class="people_box_content people_box_content'.$unique_id.' pcol'.$grid_view_col.' '.( $show_map != 0 ? 'has_map' : '' ).'"><div class="people-grid-sizer"></div>';
		if(is_array($contacts) && count($contacts) > 0 ){
			foreach($contacts as $key=>$value){
				$profile_id = $value['id'];

				if($value['c_avatar'] != ''){
					$src = $value['c_avatar'];
					$c_attachment_id = $value['c_attachment_id'];
				}else{
					$src = $people_contact_grid_view_icon['default_profile_image'];
					$c_attachment_id = $people_contact_grid_view_icon['default_profile_image_attachment_id'];
				}

				$html .= '<div class="people_item people_item'.$unique_id.' people_item_id'.$profile_id.' '.( 0 != $value['enable_map_marker'] && '' != trim( $value['c_latitude'] ) && '' != trim( $value['c_longitude'] ) ? 'has_marker' : '' ).'">';
				$html .= '<div class="people-entry-item">';
				$html .= '<div style="clear:both;"></div>';
				$html .= '<div class="people-content-item">';

				$img_output = '<img class="wp-image-'.$c_attachment_id.'" src="'.$src.'" />';
				if ( function_exists( 'wp_make_content_images_responsive' ) ) {
					$img_output = wp_make_content_images_responsive( $img_output );
				}

				if ( $people_contact_grid_view_layout['thumb_image_position'] == 'top' && $people_contact_grid_view_layout['item_title_position'] == 'below' ) {
					$html .= '<div class="p_content_left">'.$img_output.'</div>';
					$html .= '<h3 class="p_item_title">'.esc_attr( stripslashes( $value['c_title'])).'</h3>';
				} else {
					$html .= '<h3 class="p_item_title">'.esc_attr( stripslashes( $value['c_title'])).'</h3>';
					$html .= '<div class="p_content_left">'.$img_output.'</div>';
				}
				$html .= '<div class="p_content_right">';
				$html .= '<h3 class="p_item_name">'.esc_attr( stripslashes( $value['c_name'])).'</h3>';
				if ( trim($value['c_about']) != '') {
				$html .= '<div class="p_about_profile">';
				$html .= wpautop(wptexturize( stripslashes( $value['c_about'] ) ) );
				$html .= '</div>';
				}

				$html .= '<div class="p_contact_details">';
				if ( trim($value['c_phone']) != '') {
				$html .= '<p style="margin-bottom:5px;"><span class="p_icon_phone"><img src="'.$phone_icon.'" style="width:auto;height:auto" /></span> '. esc_attr( stripslashes( $value['c_phone'] ) ).'</p>';
				}
				if ( trim($value['c_fax']) != '') {
				$html .= '<p style="margin-bottom:5px;"><span class="p_icon_fax"><img src="'.$fax_icon.'" style="width:auto;height:auto" /></span> '. esc_attr( stripslashes( $value['c_fax'] ) ).'</p>';
				}
				if ( trim($value['c_mobile']) != '') {
				$html .= '<p style="margin-bottom:5px;"><span class="p_icon_mobile"><img src="'.$mobile_icon.'" style="width:auto;height:auto" /></span> '. esc_attr( stripslashes($value['c_mobile'] ) ).'</p>';
				}
				if ( trim($value['c_website']) != '') {
				$html .= '<p style="margin-bottom:5px;"><span class="p_icon_website"><img src="'.$website_icon.'" style="width:auto;height:auto" /></span> <a href="'. esc_url( stripslashes($value['c_website'] ) ).'" target="_blank">'.( function_exists('icl_t') ? icl_t( 'a3 Contact People', 'Profile Cards - Website Link Text', __('Visit Website', 'contact-us-page-contact-people' ) ) : __('Visit Website', 'contact-us-page-contact-people' ) ).'</a></p>';
				}

				$have_modal_popup = false;
				$profile_modal_id = 'contact_people_modal_' . $profile_id;

				if ( trim($value['c_email']) != '') {

					$have_modal_popup = true;
					$html .= '<p style="margin-bottom:0px;"><span class="p_icon_email"><img src="'.$email_icon.'" style="width:auto;height:auto" /></span> <a data-toggle="modal" id="contact_people_bt_'.$profile_id.'_'.$unique_id.'" href="#'.$profile_modal_id.'">'.$people_contact_grid_view_icon['grid_view_email_text'].'</a></p>';
				}

				if ( $use_modal_popup && $have_modal_popup && ! in_array( $profile_id, $people_contact_form_ids ) ) {
					$people_contact_form_ids[] = $profile_id;
					$html .= '<div class="modal fade contact_people_modal" id="'.$profile_modal_id.'" tabindex="-1" role="dialog" aria-labelledby="'.$profile_modal_id.'Title" aria-hidden="true" style="display: none;">';

					if ( class_exists('People_Contact_3RD_ContactForm_Functions') && People_Contact_3RD_ContactForm_Functions::check_enable_3rd_contact_form() ) {
						$html .= People_Contact_3RD_ContactForm_Functions::people_contact_profile_email_show_form( $profile_id, $post->ID );
					} else {
						$html .= self::default_contact_form( $profile_id, $post->ID );
					}
					$html .= '</div>';
				}

				$html .= '</div>';
				$html .= '</div>';

				$html .= '</div>';
				$html .= '<div style="clear:both;"></div>';
				$html .= '</div>';
				$html .= '</div>';
			}
		}
		$html .= '</div>';
		$html .= '<div style="clear:both"></div>';
		return $html;
	}
}
?>