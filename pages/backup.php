<?php
if (! defined('ABSPATH')) die('Silence is golden!');

if( current_user_can( 'activate_plugins' ) ){

?>

	<div class="wrap">

	<h2 class="wphe-title"><?php _e( 'WP Htaccess Edit' , 'wphe' );?> > <?php _e('Backup', 'wphe'); ?></h2>

	<?php


// Restore Backup 


	if( ! empty( sanitize_text_field( $_POST['submit'] ) ) && ! empty( sanitize_text_field( $_POST['restore_backup'] ) ) && check_admin_referer( 'wphe_restoreb', 'wphe_restoreb' ) ){

		$wphe_restore_result = wphe_restore_backup();
		
		if($wphe_restore_result === false){

			echo'<div class="notice notice-error"><p>'.__('Unable to restore Htaccess file! Please check file permissions and try again.', 'wphe').'</p></div>';
		
		}elseif( $wphe_restore_result === true ){

			echo'<div class="notice notice-success"><p>'.__('Htaccess file has been successfully restored!', 'wphe').'</p></div>';
			
		}else{

			echo'<div class="notice notice-error"><p><strong>'.__('Unable to restore Htaccess file!', 'wphe').'</strong></p></div>';
			echo'<div class="postbox">';
			echo'<p>'.__('Please update your Htaccess file manually with following original content. ','wphe').':</p>';
			echo'<textarea class="dat widefat">'. esc_html( $wphe_restore_result ).'</textarea>';
			echo'</div>';
		}



// Create Backup


	}elseif( !empty( sanitize_text_field( $_POST['submit'] ) ) && !empty( sanitize_text_field( $_POST['create_backup'] ) ) && check_admin_referer('wphe_createb', 'wphe_createb')){
		
		if(wphe_create_backup()){

			echo'<div class="notice notice-success"><p>'.__('Backup file was created successfully. The backup file is located in the main directory.', 'wphe').'</p></div>';

		}else{

			echo'<div  class="notice notice-error"><p>'.__('Unable to create backup! <code>wp-content</code> folder is not writeable! Change the permissions and try again.', 'wphe').'</p></div>';
			
		}



// Delete Backup


	}elseif( !empty( sanitize_text_field( $_POST['submit'] ) ) && !empty( sanitize_text_field( $_POST['delete_backup'] ) ) && check_admin_referer('wphe_deleteb', 'wphe_deleteb')){

		if( wphe_delete_backup() ){

			echo'<div  class="notice notice-success"><p>'.__('Backup file has been successfully removed.', 'wphe').'</p></div>';
		
		}else{

			echo'<div id="message" class="notice notice-error"><p>'.__('Unable to remove backup file! Please check file permissions and try again.','wphe').'</p></div>';
			
		}


// Backup defaul page


	}else{

		if( file_exists( ABSPATH.'htaccess.backup' ) ){

			?> 
			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHE_DIR; ?>_backup">

					<?php wp_nonce_field('wphe_restoreb','wphe_restoreb'); ?>

					<input type="hidden" name="restore_backup" value="restore" />

					<p class="submit">
					
					<?php _e('Do you want to restore the backup file?', 'wphe'); ?> 
					
					<br><br>
					
					<input type="submit" class="button button-primary" name="submit" value="<?php _e('Restore Backup', 'wphe'); ?>" />

					</p>
				
				</form>

			</div>

			
			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHE_DIR; ?>_backup">

					<?php wp_nonce_field('wphe_deleteb','wphe_deleteb'); ?>

					<input type="hidden" name="delete_backup" value="delete" />

					<p class="submit">
					
					<?php _e('Do you want to delete a backup file?', 'wphe'); ?> 

					<br><br>
					
					<input type="submit" class="button button-primary" name="submit" value="<?php _e('Remove Backup', 'wphe'); ?>" />

					</p>

				</form>

			</div>

			<?php
			
		}else{
			
			echo '<div class="notice notice-error"><p>'.__('Backup file not found!','wphe').'</p></div>';
			
			?>

			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHE_DIR; ?>_backup">

					<?php wp_nonce_field('wphe_createb','wphe_createb'); ?>

					<input type="hidden" name="create_backup" value="create" />

					<p class="submit">
					<?php _e('Do you want to create a new backup file?', 'wphe'); ?>
					<br><br>
					<input type="submit" class="button button-primary" name="submit" value="<?php _e('Create New', 'wphe'); ?>" />
					</p>
				
				</form>

		   </div>

			<?php
			
		}
	}
	?>
	
	</div>
	<?php

}else{

	wp_die( __( 'You do not have permission to view this page','wphe' ) );
}
