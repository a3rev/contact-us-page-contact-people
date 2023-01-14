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

class Profile_Manager
{

	public static function admin_screen () {
		global $people_contact_grid_view_icon;
		$message = '';
		if ( isset($_GET['action']) && $_GET['action'] == 'del' && isset($_GET['id']) && $_GET['id'] >= 0 ) {
			if ( isset($_GET['wp_peopel_contact_nonce']) && wp_verify_nonce($_GET['wp_peopel_contact_nonce'], 'wp_peopel_contact_delete') ) {
				Data\Profile::delete_row( absint( $_GET['id'] ) );
				$message = '<div class="updated" id=""><p>'.__('Profile Successfully deleted.', 'contact-us-page-contact-people' ).'</p></div>';
			}
		} elseif ( isset($_GET['edited_profile']) ) {
			$message = '<div class="updated" id=""><p>'.__('Profile Successfully updated.', 'contact-us-page-contact-people' ).'</p></div>';
		} elseif ( isset($_GET['created_profile']) ) {
			$message = '<div class="updated" id=""><p>'.__('Profile Successfully created.', 'contact-us-page-contact-people' ).'</p></div>';
		}
		
		$my_contacts = Data\Profile::get_results('', 'c_order ASC, id ASC', '', 'ARRAY_A');
		?>
        <div id="htmlForm">
        <div style="clear:both"></div>
		<div class="wrap a3rev_manager_panel_container">
        
        <?php echo $message; ?>
        <div class="icon32 icon32-a3rev-ui-settings icon32-a3revpeople-contact-settings" id="icon32-a3revpeople-contact-manager"><br></div><h1><?php _e('Profiles', 'contact-us-page-contact-people' ); ?> <a class="add-new-h2" href="<?php echo admin_url('admin.php?page=people-contact', 'relative');?>"><?php _e('Add New', 'contact-us-page-contact-people' ); ?></a></h1>
		<div style="clear:both;height:5px;"></div>
		<form name="contact_setting" method="post" action="">
		  <table class="wp-list-table widefat fixed striped sorttable">
			<thead>
			  <tr>
				<th width="30" class="manage-column column-sortable" style="text-align:left;white-space:nowrap"></th>
				<th width="25" class="manage-column column-number" style="text-align:right;white-space:nowrap"><?php _e('No', 'contact-us-page-contact-people' ); ?></th>
				<th width="40" class="manage-column column-image">&nbsp;</th>
				<th class="manage-column column-title" style="text-align:left;white-space:nowrap"><?php _e('Name', 'contact-us-page-contact-people' ); ?></th>
				<th width="10%" class="manage-column column-id" style="text-align:left;white-space:nowrap"><?php _e('ID', 'contact-us-page-contact-people' ); ?></th>
				<th width="18%" class="manage-column column-email" style="text-align:left;white-space:nowrap"><?php _e('Email', 'contact-us-page-contact-people' ); ?></th>
				<th width="8%" class="manage-column column-phone" style="text-align:left;white-space:nowrap"><?php _e('Phone', 'contact-us-page-contact-people' ); ?></th>
				<th width="15%" style="text-align:left" class="manage-column column-location"><?php _e('Location', 'contact-us-page-contact-people' ); ?></th>
				<th width="85" style="text-align:center" class="manage-column column-actions"></th>
			  </tr>
			</thead>
			<tfoot>
			  <tr>
				<th class="manage-column column-sortable" style="text-align:left;white-space:nowrap"></th>
				<th class="manage-column column-number" style="text-align:right;white-space:nowrap"><?php _e('No', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-image">&nbsp;</th>
				<th class="manage-column column-title" style="text-align:left;white-space:nowrap"><?php _e('Name', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-id" style="text-align:left;white-space:nowrap"><?php _e('ID', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-email" style="text-align:left;white-space:nowrap"><?php _e('Email', 'contact-us-page-contact-people' ); ?></th>
				<th class="manage-column column-phone" style="text-align:left;white-space:nowrap"><?php _e('Phone', 'contact-us-page-contact-people' ); ?></th>
				<th style="text-align:left" class="manage-column column-location"><?php _e('Location', 'contact-us-page-contact-people' ); ?></th>
				<th style="text-align:center" class="manage-column column-actions"></th>
			  </tr>
			</tfoot>
			<tbody>
			<?php 
			if ( is_array($my_contacts) && count($my_contacts) > 0 ) {
				$i = 0;
				foreach ( $my_contacts as $value ) {
					$i++;
					if ( $value['c_avatar'] != '') {
						$src = $value['c_avatar'];
					} else {
						$src = PEOPLE_CONTACT_IMAGE_URL.'/no-avatar.png';
					}
					?>
			  <tr id="recordsArray_<?php echo $value['id']; ?>" class="no-items">
				<td class="column-sortable" style="cursor:pointer;" valign="middle"><img src="<?php echo PEOPLE_CONTACT_IMAGE_URL; ?>/updown.png" style="cursor:pointer" /></td>
				<td valign="middle" class="no column-number" style="text-align:right;"><span class="number_item"><?php echo $i;?></span></td>
				<td valign="middle" class="avatar column-image" align="center"><img src="<?php echo $src; ?>" style="border:1px solid #CCC;padding:2px;background:#FFF;width:32px;" /></td>
				<td valign="middle" style="text-align:left;" class="name column-title"><?php esc_attr_e( stripslashes( $value['c_name']) );?></td>
				<td valign="middle" style="text-align:left;" class="name column-id"><?php esc_attr_e( stripslashes( $value['c_identitier']) );?></td>
				<td valign="middle" class="phone column-email"><?php esc_attr_e( stripslashes( $value['c_email']) );?></td>
				<td valign="middle" class="phone column-phone"><?php esc_attr_e( stripslashes( $value['c_phone']) );?></td>
				<td valign="middle" class="address column-location"><?php esc_attr_e( stripslashes( $value['c_address']) );?></td>
				<td valign="middle" class="column-actions" align="center"><a title="<?php _e('Edit', 'contact-us-page-contact-people' ); ?>" href="<?php echo admin_url('admin.php?page=people-contact&action=edit&id='.$value['id'], 'relative');?>"><?php _e('Edit', 'contact-us-page-contact-people' ); ?></a> | <a title="<?php _e('Delete', 'contact-us-page-contact-people' ); ?>" href="<?php echo wp_nonce_url( admin_url('admin.php?page=people-contact-manager&action=del&id='.$value['id'], 'relative'), 'wp_peopel_contact_delete', 'wp_peopel_contact_nonce' );?>" onclick="if(!confirm('<?php _e('Are you sure delete this profile?', 'contact-us-page-contact-people' ); ?>')){return false;}else{return true;}"><?php _e('Delete', 'contact-us-page-contact-people' ); ?></a></td>
			  </tr>
			  <?php
				}
			}else{
								?>
			  <tr>
				<td valign="middle" align="center" colspan="9"><?php _e('No Profile', 'contact-us-page-contact-people' ); ?></td>
			  </tr>
			  <?php
			}
		?>
			</tbody>
		  </table>
		  <?php $people_update_orders = wp_create_nonce("people_update_orders"); ?>
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
							var order = $(this).sortable("serialize") + '&action=people_update_orders&security=<?php echo $people_update_orders; ?>';
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
        </div>
        </div>
		<?php	
	}
}
