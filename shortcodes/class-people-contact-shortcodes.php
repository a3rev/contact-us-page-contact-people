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
class People_Contact_Shortcode{
	
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
		add_shortcode( 'people_contacts', array( $this, 'people_contacts_html') );
	}
	
	public function add_people_contact_button() {
		$is_post_edit_page = in_array(basename($_SERVER['PHP_SELF']), array('post.php', 'page.php', 'page-new.php', 'post-new.php'));
        if(!$is_post_edit_page)
            return;
		echo '<a href="#TB_inline?width=640&height=580&inlineId=people-contact-wrap" class="thickbox button contact_add_shortcode" title="' . __( 'Insert shortcode', 'cup_cp' ) . '"><span class="contact_add_shortcode_icon"></span>'.__('Contact', 'cup_cp').'</a>';
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
		$contacts = People_Contact_Profile_Data::get_results('show_on_main_page=1', 'c_order ASC', '', 'ARRAY_A');
		$all_categories = array ( array('id' => 1, 'category_name' => __('Profile Group', 'cup_cp') ) );
		?>
		<div id="people-contact-wrap" style="display:none">
        <div class="people-shortcode-popup-container">
        <div id="a3_plugin_shortcode_upgrade_area"><div class="a3-people-contact-logo-extensions"></div><?php echo People_Contact_Functions::extension_shortcode(); ?>
            <fieldset style="border:1px solid #DFDFDF; padding:0 20px; background: #FFF;  margin:10px;"><legend style="font-weight:bold; font-size:14px;"><?php _e('Insert Single Profile', 'cup_cp'); ?></legend>
            <div id="people-contact-content" class="people-contact-content people-contact-shortcode-container" style="text-align:left;">
            <p><label for="people_contact_item"><?php _e('Select people', 'cup_cp'); ?>:</label> 
                <select style="width:250px" id="people_contact_item" name="people_contact_item">
                <?php
				    echo '<option value="">'.__('Please select...', 'cup_cp').'</option>';	
					if( is_array($contacts) && count($contacts) > 0 ){
						foreach ($contacts as $key=>$value) {
							$profile_name =  trim( esc_attr( stripslashes($value['c_name']) ) );
							if ($profile_name == '') $profile_name = trim( esc_attr( stripslashes($value['c_title']) ) ); 
							echo '<option value="'.$value['id'].'">'.$profile_name.'</option>';
						}
					} 
				?>
                </select> <img class="people_contact_item_loader" style="display:none;" src="<?php echo PEOPLE_CONTACT_IMAGE_URL; ?>/ajax-loader.gif" border=0 />
            </p>
            
            <p><label for="people_contact_align"><?php _e('Card Alignment', 'cup_cp'); ?>:</label> <select style="width:120px" id="people_contact_align" name="people_contact_align"><option value="none" selected="selected"><?php _e('None', 'cup_cp'); ?></option><option value="left-wrap"><?php _e('Left - wrap', 'cup_cp'); ?></option><option value="left"><?php _e('Left - no wrap', 'cup_cp'); ?></option><option value="center"><?php _e('Center', 'cup_cp'); ?></option><option value="right-wrap"><?php _e('Right - wrap', 'cup_cp'); ?></option><option value="right"><?php _e('Right - no wrap', 'cup_cp'); ?></option></select> <span class="description"><?php _e('Wrap is text wrap like images', 'cup_cp'); ?></span></p>
				<p><label for="people_contact_item_width"><?php _e('Card Width', 'cup_cp'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="people_contact_item_width" name="people_contact_item_width" type="text" value="300" />px</p>
				<p><label for=""><strong><?php _e('Card External Padding', 'cup_cp'); ?></strong>:</label><br /> 
					<label for="people_contact_padding_top" style="width:auto; float:none"><?php _e('Above', 'cup_cp'); ?>:</label><input  disabled="disabled" style="width:50px;" size="10" id="people_contact_padding_top" name="people_contact_padding_top" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                	<label for="people_contact_padding_bottom" style="width:auto; float:none"><?php _e('Below', 'cup_cp'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="people_contact_padding_bottom" name="people_contact_padding_bottom" type="text" value="10" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                	<label for="people_contact_padding_left" style="width:auto; float:none"><?php _e('Left', 'cup_cp'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="people_contact_padding_left" name="people_contact_padding_left" type="text" value="0" />px &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                	<label for="people_contact_padding_right" style="width:auto; float:none"><?php _e('Right', 'cup_cp'); ?>:</label> <input disabled="disabled" style="width:50px;" size="10" id="people_contact_padding_right" name="people_contact_padding_right" type="text" value="0" />px
                </p>
           
            
			</div>
            <div style="clear:both;height:0px"></div>
            <p><input disabled="disabled" type="button" class="button button-primary" value="<?php _e('Insert Shortcode', 'cup_cp'); ?>" /> 
            <input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e('Cancel', 'cup_cp'); ?>" />
			</p>
            </fieldset>
            </div>
            
            <div id="a3_plugin_shortcode_upgrade_area"><div class="a3-people-contact-logo-extensions"></div><?php echo People_Contact_Functions::extension_shortcode(); ?>
            <fieldset style="border:1px solid #DFDFDF; padding:0 20px; background: #FFF; margin:10px;"><legend style="font-weight:bold; font-size:14px;"><?php _e('Insert Group', 'cup_cp'); ?></legend>
            <div id="people-category-content" class="people-category-content people-contact-shortcode-container" style="text-align:left;">
                <p><label for="profile_category_id"><?php _e('Select Group', 'cup_cp'); ?>:</label> 
                    <select style="width:250px" id="profile_category_id" name="profile_category_id">
                    <?php
                        echo '<option value="">'.__('Please select...', 'cup_cp').'</option>';	
                        if( is_array($all_categories) && count($all_categories) > 0 ){
                            foreach ($all_categories as $category_data) {
                                echo '<option value="'.$category_data['id'].'">'. trim( esc_attr( stripslashes( $category_data['category_name'] ) ) ).'</option>';
                            }
                        } 
                    ?>
                    </select>
                </p>
            
                <p><label for="profile_category_column"><?php _e('Profile Cards / Row', 'cup_cp'); ?>:</label> 
                	<select style="width:120px" id="profile_category_column" name="profile_category_column">
                    <?php
					echo '<option value="">'.__('Please select...', 'cup_cp').'</option>';
						echo '<option value="1">1 ' .__('Card', 'cup_cp') .' </option>';
					for ( $column = 2; $column <= 5; $column++ ) {
						echo '<option value="'.$column.'">'. $column. ' ' .__('Cards', 'cup_cp') .' </option>';
					}
                    ?>
                    </select> <span class="description"><?php _e('Empty to use global settings.', 'cup_cp'); ?></span>
				</p>
                <p><label for="enable_google_map"><?php _e('Google Map', 'cup_cp'); ?>:</label> <input disabled="disabled" type="checkbox" id="enable_google_map" name="enable_google_map" value="1" <?php if ( $people_contact_location_map_settings['hide_maps_frontend'] != 1 ) echo 'checked="checked"'; ?> /> <span><?php _e('Check to enable google location map for this group.', 'cup_cp'); ?></span>
                </p>
                <p><label for="people_show_group_title"><?php _e('Group Title', 'cup_cp'); ?>:</label> <input disabled="disabled" type="checkbox" id="people_show_group_title" name="people_show_group_title" value="1" checked="checked" /> <span><?php _e('Check to show group title above Profiles.', 'cup_cp'); ?></span>
                </p>
				
			</div>
            <div style="clear:both;height:0px"></div>
            <p><input disabled="disabled" type="button" class="button button-primary" value="<?php _e('Insert Shortcode', 'cup_cp'); ?>" /> 
            <input type="button" class="button" onclick="tb_remove(); return false;" value="<?php _e('Cancel', 'cup_cp'); ?>" />
			</p>
            </fieldset>
            </div>
        </div>
        </div>
        <style type="text/css">
		.people_value{position:relative;top:-2px;}
        .people_item {margin-right:5%;}
        </style>
		<?php
	}
	
	public function people_contacts_html( $atts ) {
		global $people_contact;
		extract( shortcode_atts( array(), $atts ) );
		$contacts = People_Contact_Profile_Data::get_results('', 'c_order ASC', '', 'ARRAY_A');
		return '<div id="people_contacts_container">'.$people_contact->create_contact_maps($contacts).'</div>';
	}
	
}
?>
