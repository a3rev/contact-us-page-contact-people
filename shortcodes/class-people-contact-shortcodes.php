<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * People Contact Shortcode
 *
 * Table Of Contents
 *
 * People_Contact_Shortcode()
 * init()
 * add_people_contact_button()
 * people_contact_generator_popup()
 * people_contacts_html()
 * people_contact_html()
 */

namespace A3Rev\ContactPeople;

class Shortcode{
	
	var $admin_page,$contact_manager;
	public $template_url = PEOPLE_CONTACT_PATH;
	
	public function __construct () {
		$this->init();
	}
	
	public function init () {
		add_action( 'wp_head', array( $this, 'add_shortcodes' ), 100 );
		add_action( 'media_buttons', array( $this, 'add_people_contact_button'), 100 );
		add_action( 'admin_footer', array( $this, 'people_contact_generator_popup') );
	}

	public function add_shortcodes() {
		add_shortcode( 'people_contact', array( $this, 'people_contact_html') );
		add_shortcode( 'people_contacts', array( $this, 'people_contacts_html') );
	}
	
	public function add_people_contact_button() {
		$is_post_edit_page = in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'));
        if(!$is_post_edit_page)
            return;
		echo '<a href="#TB_inline?width=640&height=580&inlineId=people-contact-wrap" class="thickbox button contact_add_shortcode" title="' . __( 'Insert shortcode', 'contact-us-page-contact-people' ) . '"><span class="contact_add_shortcode_icon"></span>'.__('Contact', 'contact-us-page-contact-people' ).'</a>';
		?>
        <style type="text/css">
		#TB_ajaxContent{width:auto !important;}
		#TB_ajaxContent p {
			padding:2px 0;	
			margin:6px 0;
		}
		#a3_plugin_shortcode_upgrade_area { margin-top:10px; border:2px solid #E6DB55;-webkit-border-radius:10px;-moz-border-radius:10px;-o-border-radius:10px; border-radius: 10px; padding:0; position:relative;}
	  	#a3_plugin_shortcode_upgrade_area h3{ margin-left:10px;}
		.a3-people-contact-logo-extensions { position:absolute; left:10px; top:0px; z-index:10; color:#46719D; }
		.a3-people-contact-logo-extensions:before {
		  font-family: "a3-sidebar-menu" !important;
		  font-style: normal !important;
		  font-weight: normal !important;
		  font-variant: normal !important;
		  text-transform: none !important;
		  speak: none;
		  line-height: 1;
		  -webkit-font-smoothing: antialiased;
		  -moz-osx-font-smoothing: grayscale;
			display:inline-block;
			font-size:25px !important;
			font-weight:400;
			height: 36px;
			padding: 8px 0;
			transition: all 0.1s ease-in-out 0s;
		  
		  content: "\a3" !important;
		}
	   	#a3_plugin_shortcode_extensions { background: #FFFBCC; -webkit-border-radius:10px 10px 0 0;-moz-border-radius:10px 10px 0 0;-o-border-radius:10px 10px 0 0; border-radius: 10px 10px 0 0; color: #555555; margin: 0px; padding: 4px 8px 4px 40px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8); min-height:30px; position:relative;}
		</style>
		<?php
	}
	
	public function people_contact_generator_popup() {
		global $people_contact_location_map_settings;
		$contacts = Data\Profile::get_results('', 'c_order ASC', '', 'ARRAY_A');
		$all_categories = array ( array('id' => 1, 'category_name' => __('Profile Group', 'contact-us-page-contact-people' ) ) );
		?>
		<div id="people-contact-wrap" style="display:none">
        	<fieldset style="border:1px solid #DFDFDF; padding:0 20px; background: #FFF; margin-top:15px;"><legend style="font-weight:bold; font-size:14px;"><?php _e('Insert Single Profile', 'contact-us-page-contact-people' ); ?></legend>
            <div id="people-contact-content" class="people-contact-content people-contact-shortcode-container" style="text-align:left;">
                <p><label for="people_contact_item"><?php _e('Select Profile', 'contact-us-page-contact-people' ); ?>:</label> 
                    <select style="width:300px" id="people_contact_item" name="people_contact_item">
                    <?php
                        echo '<option value="">'.__('Please select...', 'contact-us-page-contact-people' ).'</option>';	
                        if( is_array($contacts) && count($contacts) > 0 ){
                            foreach ($contacts as $key=>$value) {
                                $profile_name =  trim( esc_attr( stripslashes($value['c_name']) ) );
                                if ($profile_name == '') $profile_name = trim( esc_attr( stripslashes($value['c_title']) ) );

                                $c_identitier =  trim( esc_attr( stripslashes( $value['c_identitier'] ) ) );
								if ( strlen( $c_identitier ) > 30 ) {
									$c_identitier = substr( $c_identitier, 0, 30 ) . '...';
								}

								if ( '' !== $c_identitier ) {
									$c_identitier = ' - ' . $c_identitier;
								}

                                echo '<option value="'.$value['id'].'">'.$profile_name . $c_identitier .'</option>';
                            }
                        } 
                    ?>
                    </select> <img class="people_contact_item_loader" style="display:none;" src="<?php echo PEOPLE_CONTACT_IMAGE_URL; ?>/ajax-loader.gif" border=0 />
                </p>
            
                <p><label for="people_contact_align"><?php _e('Card Alignment', 'contact-us-page-contact-people' ); ?>:</label> <select style="width:120px" id="people_contact_align" name="people_contact_align"><option value="none" selected="selected"><?php _e('None', 'contact-us-page-contact-people' ); ?></option><option value="left-wrap"><?php _e('Left - wrap', 'contact-us-page-contact-people' ); ?></option><option value="left"><?php _e('Left - no wrap', 'contact-us-page-contact-people' ); ?></option><option value="center"><?php _e('Center', 'contact-us-page-contact-people' ); ?></option><option value="right-wrap"><?php _e('Right - wrap', 'contact-us-page-contact-people' ); ?></option><option value="right"><?php _e('Right - no wrap', 'contact-us-page-contact-people' ); ?></option></select> <span class="description"><?php _e('Wrap is text wrap like images', 'contact-us-page-contact-people' ); ?></span></p>
				<p><label for="people_contact_item_width"><?php _e('Card Width', 'contact-us-page-contact-people' ); ?>:</label> <input style="width:50px;" size="10" id="people_contact_item_width" name="people_contact_item_width" type="text" value="300" />px</p>
				<p><label for=""><strong><?php _e('Card External Padding', 'contact-us-page-contact-people' ); ?></strong>:</label><br /> 
                        <label for="people_contact_padding_top" style="width:auto; float:none"><?php _e('Above', 'contact-us-page-contact-people' ); ?>:</label><input style="width:50px;" size="10" id="people_contact_padding_top" name="people_contact_padding_top" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="people_contact_padding_bottom" style="width:auto; float:none"><?php _e('Below', 'contact-us-page-contact-people' ); ?>:</label> <input style="width:50px;" size="10" id="people_contact_padding_bottom" name="people_contact_padding_bottom" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="people_contact_padding_left" style="width:auto; float:none"><?php _e('Left', 'contact-us-page-contact-people' ); ?>:</label> <input style="width:50px;" size="10" id="people_contact_padding_left" name="people_contact_padding_left" type="text" value="0" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <label for="people_contact_padding_right" style="width:auto; float:none"><?php _e('Right', 'contact-us-page-contact-people' ); ?>:</label> <input style="width:50px;" size="10" id="people_contact_padding_right" name="people_contact_padding_right" type="text" value="0" />px
                </p>
			</div>
            <div style="clear:both;height:0px"></div>
            <p><input type="button" class="button button-primary" value="<?php _e('Insert Shortcode', 'contact-us-page-contact-people' ); ?>" onclick="people_contact_add_shortcode();"/> 
            <input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e('Cancel', 'contact-us-page-contact-people' ); ?>" />
			</p>
            </fieldset>
            
            <div id="a3_plugin_shortcode_upgrade_area"><div class="a3-people-contact-logo-extensions"></div><?php echo Contact_Functions::extension_shortcode(); ?>
            <fieldset style="border:1px solid #DFDFDF; padding:0 20px; background: #FFF; margin:10px;"><legend style="font-weight:bold; font-size:14px;"><?php _e('Insert Group', 'contact-us-page-contact-people' ); ?></legend>
            <div id="people-category-content" class="people-category-content people-contact-shortcode-container" style="text-align:left;">
                <p><label for="profile_category_id"><?php _e('Select Group', 'contact-us-page-contact-people' ); ?>:</label> 
                    <select style="width:250px" id="profile_category_id" name="profile_category_id">
                    <?php
                        echo '<option value="">'.__('Please select...', 'contact-us-page-contact-people' ).'</option>';	
                        if( is_array($all_categories) && count($all_categories) > 0 ){
                            foreach ($all_categories as $category_data) {
                                echo '<option value="'.$category_data['id'].'">'. trim( esc_attr( stripslashes( $category_data['category_name'] ) ) ).'</option>';
                            }
                        } 
                    ?>
                    </select>
                </p>
            
                <p><label for="profile_category_column"><?php _e('Profile Cards / Row', 'contact-us-page-contact-people' ); ?>:</label> 
                	<select style="width:120px" id="profile_category_column" name="profile_category_column">
                    <?php
					echo '<option value="">'.__('Please select...', 'contact-us-page-contact-people' ).'</option>';
						echo '<option value="1">1 ' .__('Card', 'contact-us-page-contact-people' ) .' </option>';
					for ( $column = 2; $column <= 5; $column++ ) {
						echo '<option value="'.$column.'">'. $column. ' ' .__('Cards', 'contact-us-page-contact-people' ) .' </option>';
					}
                    ?>
                    </select> <span class="description"><?php _e('Empty to use global settings.', 'contact-us-page-contact-people' ); ?></span>
				</p>
                <p><label for="enable_google_map"><?php _e('Google Map', 'contact-us-page-contact-people' ); ?>:</label> <input disabled="disabled" type="checkbox" id="enable_google_map" name="enable_google_map" value="1" <?php if ( $people_contact_location_map_settings['hide_maps_frontend'] != 1 ) echo 'checked="checked"'; ?> /> <span><?php _e('Check to enable google location map for this group.', 'contact-us-page-contact-people' ); ?></span>
                </p>
                <p><label for="people_show_group_title"><?php _e('Group Title', 'contact-us-page-contact-people' ); ?>:</label> <input disabled="disabled" type="checkbox" id="people_show_group_title" name="people_show_group_title" value="1" checked="checked" /> <span><?php _e('Check to show group title above Profiles.', 'contact-us-page-contact-people' ); ?></span>
                </p>
				
			</div>
            <div style="clear:both;height:0px"></div>
            <p><input disabled="disabled" type="button" class="button button-primary" value="<?php _e('Insert Shortcode', 'contact-us-page-contact-people' ); ?>" /> 
            <input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e('Cancel', 'contact-us-page-contact-people' ); ?>" />
			</p>
            </fieldset>
		</div></div>
        <script type="text/javascript">
		
		function people_contact_add_shortcode(){
			var select_people = jQuery("#people_contact_item").val();
			if (select_people == '') {
				alert('<?php _e('Please select People', 'contact-us-page-contact-people' ); ?>');
				return false;	
			}
			var people_contact_align = jQuery("#people_contact_align").val();
			var people_contact_item_width = jQuery("#people_contact_item_width").val();
			var people_contact_padding_top = jQuery("#people_contact_padding_top").val();
			var people_contact_padding_bottom = jQuery("#people_contact_padding_bottom").val();
			var people_contact_padding_left = jQuery("#people_contact_padding_left").val();
			var people_contact_padding_right = jQuery("#people_contact_padding_right").val();
			var people_contact_style = 'style="';
			var wrap = '';
			if (people_contact_align == 'center') people_contact_style += 'float:none;margin:auto;display:block;';
			else if (people_contact_align == 'left-wrap') people_contact_style += 'float:left;';
			else if (people_contact_align == 'right-wrap') people_contact_style += 'float:right;';
			else people_contact_style += 'float:'+people_contact_align+';';
			
			if(people_contact_align == 'left-wrap' || people_contact_align == 'right-wrap') wrap = 'wrap="true" ';
				
			if (parseInt(people_contact_item_width) > 0) people_contact_style += 'width:'+parseInt(people_contact_item_width)+'px;';
			if (parseInt(people_contact_padding_top) >= 0) people_contact_style += 'padding-top:'+parseInt(people_contact_padding_top)+'px;';
			if (parseInt(people_contact_padding_bottom) >= 0) people_contact_style += 'padding-bottom:'+parseInt(people_contact_padding_bottom)+'px;';
			if (parseInt(people_contact_padding_left) >= 0) people_contact_style += 'padding-left:'+parseInt(people_contact_padding_left)+'px;';
			if (parseInt(people_contact_padding_right) >= 0) people_contact_style += 'padding-right:'+parseInt(people_contact_padding_right)+'px;';
				
			var win = window.dialogArguments || opener || parent || top;
			win.send_to_editor('[people_contact id="' + select_people + '" ' + people_contact_style + '" ' + wrap + ']');
		}
		
		</script>
        <style type="text/css">
		.people_value{position:relative;top:-2px;}
        .people_item {margin-right:5%;}
        </style>
		<?php
	}
	
	public function people_contacts_html( $atts ) {
		global $people_contact;
		$contacts = Data\Profile::get_results('show_on_main_page=1', 'c_order ASC', '', 'ARRAY_A');
		return '<div id="people_contacts_container">'.$people_contact->create_contact_maps($contacts).'</div>';
	}
	
	public function people_contact_html( $atts ) {
		global $people_contact;
		
		$atts = array_merge(array(
			 'id' 				=> 0,
			 'style' 			=> 'float:none;width:300px;padding-top:10px;padding-bottom:10px;padding-left:0px;padding-right:0px;',
			 'wrap'				=> 'false'
        ), $atts );

        // XSS ok
		$id    = esc_attr( $atts['id'] );
		$style = esc_attr( $atts['style'] );

		return $people_contact->create_people_contact($id, $style, $atts['wrap'] );
	}
	
}
