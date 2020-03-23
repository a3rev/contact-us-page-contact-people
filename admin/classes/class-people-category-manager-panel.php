<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * People Contact Manager Panel
 *
 * Table Of Contents
 *
 * admin_screen()
 */

namespace A3Rev\ContactPeople\Admin;

use A3Rev\ContactPeople\Data as Data;

class Category_Manager
{

	public static function custom_upgrade_top_message( $message, $setting_id ) {
		if ( $setting_id == 'a3_people_list_group_box' ) {
			$message = '<div class="pro_feature_top_message">'
				. sprintf( __( '<strong><a href="%s" target="_blank">Ultimate Version Feature</a></strong> The Groups feature enables admins to create Groups of profiles and insert them by shortcode or Gutenberg Contact Groups Block completely independent of the Contact Us Page display. Simply Create Groups (like Categories) and assign Profiles to any number of groups. Then the Group (with a Group location map if required) can be inserted by shortcode or Block in any page or post.', 'contact-us-page-contact-people' ), $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->pro_plugin_page_url )
				. '</div>';
		}

		return $message;
	}

	public static function admin_screen () {
		$message = '';

		add_filter( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->plugin_name . '_upgrade_top_message', array( __CLASS__, 'custom_upgrade_top_message' ), 10, 2 );

		?>
        <div id="htmlForm">
        <div style="clear:both"></div>
		<div class="wrap a3rev_manager_panel_container">
        
        <?php echo $message; ?>
		<?php
		if ( isset($_GET['action']) && $_GET['action'] == 'add_new' ) {
			self::admin_category_update();
		} elseif ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
			self::admin_category_update( absint( $_GET['id'] ) );
		} elseif ( isset($_GET['action']) && $_GET['action'] == 'view-profile' ) {
			self::admin_category_profiles( absint( $_GET['id'] ) );
		} else {
			self::admin_categories();
		}
		?>
        </div>
        </div>
		<?php
	}
	
	public static function admin_categories () {
		$all_categories = array ( array('id' => 1, 'category_name' => __('Profile Group', 'contact-us-page-contact-people' ) ) );
	?>
        <div class="icon32 icon32-a3rev-ui-settings icon32-a3revpeople-contact-settings" id="icon32-a3revpeople-category-manager"><br></div><h1><?php _e('Groups', 'contact-us-page-contact-people' ); ?> <a class="add-new-h2" href="<?php echo admin_url('admin.php?page=people-category-manager&action=add_new', 'relative');?>"><?php _e('Add New', 'contact-us-page-contact-people' ); ?></a></h1>
		<div style="clear:both;height:5px;"></div>
<div id="a3_plugin_panel_container">
	<div id="a3_plugin_panel_upgrade_area">
		<div id="a3_plugin_panel_extensions">
		<?php $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface']->plugin_extension_boxes( true ); ?>
		</div>
	</div>
	<div id="a3_plugin_panel_fields">
        <div class="a3rev_panel_container">
		<?php ob_start(); ?>
        <div style="margin-bottom:5px;"><?php _e('Create Groups, assign Profiles to Groups and insert the Group into any Post or Page by Shortcode.', 'contact-us-page-contact-people' ); ?></div>
		<form name="contact_setting" method="post" action="">
		  <table class="wp-list-table widefat fixed striped sorttable">
			<thead>
			  <tr>
				<th width="25" class="manage-column column-number" style="text-align:right;white-space:nowrap"><?php _e('No', 'contact-us-page-contact-people' ); ?></th>
				<th width="15%" class="manage-column column-title" style="text-align:left;white-space:nowrap; width:15% !important;"><?php _e('Name', 'contact-us-page-contact-people' ); ?></th>
				<th width="40%" class="manage-column column-shortcode" style="text-align:left;white-space:nowrap"><?php _e('Shortcode', 'contact-us-page-contact-people' ); ?></th>
				<th width="50" class="manage-column column-profiles" style="text-align:center;white-space:nowrap"><?php _e('Profiles', 'contact-us-page-contact-people' ); ?></th>
                <th width="60" class="manage-column column-activated" style="text-align:center;white-space:nowrap"><?php _e('Activated', 'contact-us-page-contact-people' ); ?></th>
				<th width="100" style="text-align:center" class="manage-column column-actions">&nbsp;</th>
			  </tr>
			</thead>
			<tfoot>
			  <tr>
				<th class="manage-column column-number" style="text-align:right;white-space:nowrap"><?php _e('No', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-title" style="text-align:left;white-space:nowrap; width:15% !important;"><?php _e('Name', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-shortcode" style="text-align:left;white-space:nowrap"><?php _e('Shortcode', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-profiles" style="text-align:center;white-space:nowrap"><?php _e('Profiles', 'contact-us-page-contact-people' ); ?></th>
                <th class="manage-column column-activated" style="text-align:center;white-space:nowrap"><?php _e('Activated', 'contact-us-page-contact-people' ); ?></th>
				<th style="text-align:center" class="manage-column column-actions">&nbsp;</th>
			  </tr>
			</tfoot>
			<tbody>
			<?php 
			if ( is_array($all_categories) && count($all_categories) > 0 ) {
				$i = 0;
				foreach ( $all_categories as $value ) {
					$i++;
					
					$total_profiles = 0;
			?>
			  <tr>
				<td valign="middle" class="no column-number" style="text-align:right;"><span class="number_item"><?php echo $i;?></span></td>
				<td valign="middle" style="text-align:left;" class="name column-title"><?php esc_attr_e( stripslashes( $value['category_name']) );?></td>
				<td valign="middle" class="column-shortcode">[people_group_contacts id="<?php echo $value['id'];?>" group_title="<?php esc_attr_e( stripslashes( $value['category_name']) );?>" column="3" show_map="1" show_group_title="1" ]</td>
				<td valign="middle" class="column-profiles" style="text-align:center"><?php echo $total_profiles;?></td>
                <td valign="middle" class="column-activated" style="text-align:center"><?php _e('Yes', 'contact-us-page-contact-people' ); ?></td>
				<td valign="middle" class="column-actions" align="center"><a title="<?php _e('View Profiles', 'contact-us-page-contact-people' ); ?>" href="<?php echo admin_url('admin.php?page=people-category-manager&action=view-profile&id='.$value['id'], 'relative');?>"><?php _e('View Profiles', 'contact-us-page-contact-people' ); ?></a> | <a title="<?php _e('Edit', 'contact-us-page-contact-people' ); ?>" href="<?php echo admin_url('admin.php?page=people-category-manager&action=edit&id='.$value['id'], 'relative');?>"><?php _e('Edit', 'contact-us-page-contact-people' ); ?></a> | <a title="<?php _e('Delete', 'contact-us-page-contact-people' ); ?>" href="<?php echo admin_url('admin.php?page=people-category-manager&action=del&id='.$value['id'], 'relative');?>" onclick="if(!confirm('<?php _e('Are you sure delete this category?', 'contact-us-page-contact-people' ); ?>')){return false;}else{return true;}"><?php _e('Delete', 'contact-us-page-contact-people' ); ?></a></td>
			  </tr>
			  <?php
				}
			}else{
								?>
			  <tr>
				<td valign="middle" align="center" colspan="6"><?php _e('No Groups', 'contact-us-page-contact-people' ); ?></td>
			  </tr>
			  <?php
			}
		?>
			</tbody>
		  </table>
		</form>
		<?php
	        $settings_html = ob_get_clean();
	        $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface']->panel_box( $settings_html, array(
	        	'name' 		=> __( 'List Groups', 'contact-us-page-contact-people' ),
	        	'class'		=> 'pro_feature_fields',
	        	'id'		=> 'a3_people_list_group_box',
				'is_box'	=> true,
				'alway_open'=> true,
			) );
	        ?>
		</div>
	</div>
</div>
		<?php
	}
	
	public static function admin_category_update( $category_id = 0) {
		global $people_contact_location_map_settings;
		$category_name      = '';
		$publish            = 1;
		$g_zoom             = $people_contact_location_map_settings['zoom_level'];
		$g_map_type         = $people_contact_location_map_settings['map_type'];
		$g_width_type       = $people_contact_location_map_settings['map_width_type'];
		$g_width_responsive = $people_contact_location_map_settings['map_width_responsive'];
		$g_width_fixed      = $people_contact_location_map_settings['map_width_fixed'];
		$g_height           = $people_contact_location_map_settings['map_height'];
		$bt_type            = 'add_new_category';
		$bt_value           = __('Create', 'contact-us-page-contact-people' );
		$title              = __('Add New Group', 'contact-us-page-contact-people' );
		if ( $category_id > 0 ) {
			$data = array('id' => 1, 'category_name' => __('Profile Group', 'contact-us-page-contact-people' ) );
			$category_name = $data['category_name'];
			$publish = 1;
			$bt_type = 'update_category';
			$title = __('Edit Group', 'contact-us-page-contact-people' );
			$bt_value = __('Update', 'contact-us-page-contact-people' );
		}
	?>
		<div class="icon32 icon32-posts-post" id="icon-edit"><br></div><h1><?php echo $title;?></h1>
		<div style="clear:both;"></div>
<div id="a3_plugin_panel_container">
	<div id="a3_plugin_panel_upgrade_area">
		<div id="a3_plugin_panel_extensions">
		<?php $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface']->plugin_extension_boxes( true ); ?>
		</div>
	</div>
	<div id="a3_plugin_panel_fields">
        <div class="a3rev_panel_container">
		<form action="<?php echo admin_url('admin.php?page=people-category-manager', 'relative');?>" method="post">
        	<?php if ( $category_id > 0 ) { ?><input type="hidden" value="<?php echo $category_id;?>" id="category_id" name="category_id"><?php } ?>
			<?php ob_start(); ?>
			<table class="form-table" style="margin-bottom:0;">
			  <tbody>
				<tr valign="top">
				  	<th scope="row"><label for="category_name"><?php _e('Group Name', 'contact-us-page-contact-people' ) ?></label></th>
				  	<td><input type="text" style="width:300px;" value="<?php esc_attr_e( stripslashes( $category_name ) );?>" id="category_name" name="category_name" /></td>
				</tr>
				<tr valign="top">
				  	<th scope="row"><label for="publish"><?php _e('Activate Shortcode', 'contact-us-page-contact-people' ) ?></label></th>
				  	<td>
                  		<input
								name="publish"
                                id="publish"
								class="a3rev-ui-onoff_checkbox group_activate_shortcode"
                                checked_label="<?php _e( 'ON', 'contact-us-page-contact-people' ); ?>"
                                unchecked_label="<?php _e( 'OFF', 'contact-us-page-contact-people' ); ?>"
                                type="checkbox"
								value="1"
								<?php checked( $publish, 1 ); ?>
								/>
                  		<span class="description"><?php _e('Switch ON and this group can be embedded by shortcode from the Contact shortcode insert button above the WordPress text editor on any post or page.', 'contact-us-page-contact-people' ); ?></span>
                  	</td>
				</tr>
        	  </tbody>
			</table>
			<?php
	        $settings_html = ob_get_clean();
	        $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface']->panel_box( $settings_html, array(
	        	'name' 		=> $title,
	        	'class'		=> 'pro_feature_fields',
	        	'id'		=> 'a3_people_group_box',
				'is_box'	=> true,
			) );
	        ?>

			<?php ob_start(); ?>
			<table class="form-table">
			  <tbody>
				<tr valign="top">
				  	<th scope="row"><label for="g_zoom"><?php _e('Maximum Zoom Level', 'contact-us-page-contact-people' ) ?></label></th>
				  	<td>
                  		<div class="a3rev-ui-slide-container">
                            <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                <div class="a3rev-ui-slide" id="g_zoom_div" min="1" max="19" inc="1"></div>
                            </div></div>
                            <div class="a3rev-ui-slide-result-container">
                                <input
                                    readonly="readonly"
                                    name="g_zoom"
                                    id="g_zoom"
                                    type="text"
                                    value="<?php echo esc_attr( $g_zoom ); ?>"
                                    class="a3rev-ui-slider"
                                    />
							</div>
                        </div>
                        <span class="description" style="display: block; clear: both;"><?php echo __( 'Group maps feature auto zoom which sets the map so all profile markers are visible in the map viewer on first load.  Only use this setting if you want to make the first load zoom wider than the focus (the spread of profile location markers). Move the slider to the left to do this with zoom level 1 being the world map.', 'contact-us-page-contact-people' ); ?></span>
                  	</td>
				</tr>
				<tr valign="top">
				  	<th scope="row"><label for="g_map_type"><?php _e('Map Type', 'contact-us-page-contact-people' ) ?></label></th>
				  	<td>
				  		<?php
				  		$map_type_list = array( 
							'ROADMAP' 	=> 'ROADMAP', 
							'SATELLITE' => 'SATELLITE', 
							'HYBRID' 	=> 'HYBRID',
							'TERRAIN'	=> 'TERRAIN',
						);
				  		?>
                  		<select
							name="g_map_type"
							id="g_map_type"
							style="width:120px;"
							class="a3rev-ui-select chzn-select <?php if ( is_rtl() ) { echo 'chzn-rtl'; } ?>"
							>
							<?php
							foreach ( $map_type_list as $key => $val ) {
								?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php

										selected( $g_map_type, $key );

								?>><?php echo $val ?></option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>
				<tr valign="top">
				  	<th scope="row"><label for="g_width_type"><?php _e('Map Width Type', 'contact-us-page-contact-people' ) ?></label></th>
					<td class="forminp forminp-switcher_checkbox">
                  		<input
							name="g_width_type"
                            id="g_width_type"
							class="a3rev-ui-onoff_checkbox map_width_type"
                            checked_label="<?php echo __( 'Responsive', 'contact-us-page-contact-people' ); ?>"
                            unchecked_label="<?php echo __( 'Fixed Wide', 'contact-us-page-contact-people' ); ?>"
                            type="checkbox"
							value="percent"
							<?php checked( $g_width_type, 'percent' ); ?>
							/>
					</td>
				</tr>
        	  </tbody>
			</table>
			<div class="map_width_type_percent">
			<table class="form-table">
			  <tbody>
				<tr valign="top">
				  	<th scope="row"></th>
				  	<td>
                  		<div class="a3rev-ui-slide-container">
                            <div class="a3rev-ui-slide-container-start"><div class="a3rev-ui-slide-container-end">
                                <div class="a3rev-ui-slide" id="g_width_responsive_div" min="10" max="100" inc="1"></div>
                            </div></div>
                            <div class="a3rev-ui-slide-result-container">
                                <input
                                    readonly="readonly"
                                    name="g_width_responsive"
                                    id="g_width_responsive"
                                    type="text"
                                    value="<?php echo esc_attr( $g_width_responsive ); ?>"
                                    class="a3rev-ui-slider"
                                    /> <span style="margin-left:5px;" class="description">%</span>
							</div>
                        </div>
					</td>
				</tr>
        	  </tbody>
			</table>
			</div>
			<div class="map_width_type_fixed">
			<table class="form-table">
			  <tbody>
				<tr valign="top">
				  	<th scope="row"></th>
				  	<td>
                  		<input type="text" style="width:60px;" value="<?php echo esc_attr( $g_width_fixed );?>" name="g_width_fixed" />
                  		<span style="margin-left:5px;" class="description">px</span>
                  	</td>
				</tr>
        	  </tbody>
			</table>
			</div>
			<table class="form-table">
			  <tbody>
				<tr valign="top">
				  	<th scope="row"><label for="g_height"><?php _e('Map Height', 'contact-us-page-contact-people' ) ?></label></th>
				  	<td>
                  		<input type="text" style="width:60px;" value="<?php echo esc_attr( $g_height );?>" id="g_height" name="g_height" />
                  		<span style="margin-left:5px;" class="description">px</span>
                  	</td>
				</tr>
        	  </tbody>
			</table>

			<?php
	        $settings_html = ob_get_clean();
	        $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface']->panel_box( $settings_html, array(
	        	'name' 		=> __( 'Group Google Map', 'contact-us-page-contact-people' ),
	        	'desc'		=> __( 'A Google Map showing all profile locations. When inserting this groups shortcode you have the option to show the group map. Set the Map display settings here.', 'contact-us-page-contact-people' ),
	        	'id'		=> 'a3_people_group_g_map_box',
	        	'class'		=> 'pro_feature_fields a3_people_group_g_map_container',
				'is_box'	=> true,
			) );
	        ?>

            <div style="clear:both"></div>
			<p class="submit" style="margin-bottom:0;padding-bottom:0;">
            <input type="hidden" value="<?php echo $bt_type;?>" name="<?php echo $bt_type;?>" />
            <input disabled="disabled" type="submit" value="<?php echo $bt_value;?>" class="button button-primary" id="add_edit_buttom" name="add_edit_buttom"> <input type="button" class="button" onclick="window.location='admin.php?page=people-category-manager'" value="<?php _e('Cancel', 'contact-us-page-contact-people' ); ?>" /></p>
		</form>
        </div>
	</div>
</div>
       	<script type="text/javascript">
		(function($) {
		$(document).ready(function() {
			if ( $("input.group_activate_shortcode:checked").val() != 1) {
				$(".a3_people_group_g_map_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
			}
			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.group_activate_shortcode', function( event, value, status ) {
				$(".a3_people_group_g_map_container").attr('style','display:none;');
				if ( status == 'true' ) {
					$(".a3_people_group_g_map_container").slideDown();
				} else {
					$(".a3_people_group_g_map_container").slideUp();
				}
			});

			if ( $("input.map_width_type:checked").val() == 'percent') {
				$(".map_width_type_fixed").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
			} else {
				$(".map_width_type_percent").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
			}
			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.map_width_type', function( event, value, status ) {
				$(".map_width_type_fixed").attr('style','display:none;');
				$(".map_width_type_percent").attr('style','display:none;');
				if ( status == 'true' ) {
					$(".map_width_type_fixed").slideUp();
					$(".map_width_type_percent").slideDown();
				} else {
					$(".map_width_type_fixed").slideDown();
					$(".map_width_type_percent").slideUp();
				}
			});
		});
		})(jQuery);
		</script>
    <?php
	}
	
	public static function admin_category_profiles ( $category_id ) {
		if ( $category_id < 1 ) return '';
		
		global $people_contact_grid_view_icon;
		
		$current_category = array('id' => 1, 'category_name' => __('Profile Group', 'contact-us-page-contact-people' ) );
		
		?>
        
        <div class="icon32 icon32-a3rev-ui-settings icon32-a3revpeople-contact-settings" id="icon32-a3revpeople-category-profiles-manager"><br></div><h1>"<?php echo esc_attr( stripslashes( $current_category['category_name'] ) ) ; ?>" <?php _e('Profiles', 'contact-us-page-contact-people' ); ?></h1>
		<div style="clear:both;height:5px;"></div>
<div id="a3_plugin_panel_container">
	<div id="a3_plugin_panel_upgrade_area">
		<div id="a3_plugin_panel_extensions">
		<?php $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface']->plugin_extension_boxes( true ); ?>
		</div>
	</div>
	<div id="a3_plugin_panel_fields">
        <div class="a3rev_panel_container">
		<?php ob_start(); ?>
        <div style="margin-bottom:5px;"><?php _e('Below are all of the Profiles currently assigned to this Group. Sort Profile order for this Group by drag and drop using the blue up - down arrow at the left of each Profile row.', 'contact-us-page-contact-people' ); ?></div>
		<form name="contact_setting" method="post" action="">
		  <table class="wp-list-table widefat fixed striped sorttable">
			<thead>
			  <tr>
				<th width="25" class="manage-column column-number" style="text-align:left;white-space:nowrap"><?php _e('No', 'contact-us-page-contact-people' ); ?></th>
				<th width="10%" class="manage-column column-title" style="text-align:left;white-space:nowrap"><?php _e('Name', 'contact-us-page-contact-people' ); ?></th>
				<th width="18%" class="manage-column column-email" style="text-align:leftwhite-space:nowrap"><?php _e('Email', 'contact-us-page-contact-people' ); ?></th>
				<th width="8%" class="manage-column column-phone" style="text-align:left;white-space:nowrap"><?php _e('Phone', 'contact-us-page-contact-people' ); ?></th>
				<th width="15%" style="text-align:left" class="manage-column column-location"><?php _e('Location', 'contact-us-page-contact-people' ); ?></th>
                <th width="8%" class="manage-column column-categories" style="text-align:left;white-space:nowrap"><?php _e('Groups', 'contact-us-page-contact-people' ); ?></th>
				<th width="10" style="text-align:center" class="manage-column column-actions">&nbsp;</th>
			  </tr>
			</thead>
			<tfoot>
			  <tr>
				<th class="manage-column column-number" style="text-align:left;white-space:nowrap"><?php _e('No', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-title" style="text-align:left;white-space:nowrap"><?php _e('Name', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-email" style="text-align:leftwhite-space:nowrap"><?php _e('Email', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-phone" style="text-align:left;white-space:nowrap"><?php _e('Phone', 'contact-us-page-contact-people' ); ?></th>
				<th style="text-align:left" class="manage-column column-location"><?php _e('Location', 'contact-us-page-contact-people' ); ?></th>
                <th class="manage-column column-categories" style="text-align:left;white-space:nowrap"><?php _e('Groups', 'contact-us-page-contact-people' ); ?></th>
				<th style="text-align:center" class="manage-column column-actions">&nbsp;</th>
			  </tr>
			</tfoot>
			<tbody>
			  <tr>
				<td valign="middle" align="center" colspan="7"><?php _e('No Profile for This Group', 'contact-us-page-contact-people' ); ?></td>
			  </tr>
			</tbody>
		  </table>
		  <?php $people_category_update_orders = wp_create_nonce("people_category_update_orders"); ?>
			<script type="text/javascript">
				(function($){
					$(function(){
						var fixHelper = function(e, ui) {
							ui.children().each(function() {
								$(this).width($(this).width());
							});
							return ui;
						};
						$(".sorttable tbody").sortable({ helper: fixHelper, placeholder: "ui-state-highlight", opacity: 0.8, cursor: 'move', update: function() {
							var order = $(this).sortable("serialize") + '&category_id=<?php echo $category_id; ?>&action=people_category_update_orders&security=<?php echo $people_category_update_orders; ?>';
							$.post("<?php echo admin_url('admin-ajax.php', 'relative'); ?>", order, function(theResponse){
								$(".people_table").find(".number_item").each(function(index){
									$(this).html(index+1);
								});
							});
						}
						});
					});
				})(jQuery);
			</script>
		</form>
		<?php
	        $settings_html = ob_get_clean();
	        $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_interface']->panel_box( $settings_html, array(
	        	'name' 		=> __( 'Profile Group', 'contact-us-page-contact-people' ),
	        	'class'		=> 'pro_feature_fields',
	        	'id'		=> 'a3_people_list_profiles_group_box',
				'is_box'	=> true,
				'alway_open'=> true,
			) );
	        ?>
		</div>
	</div>
</div>
		<?php	
	}
}
