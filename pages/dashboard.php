<?php
if (!defined('ABSPATH')) die('Silence is golden.');

if( current_user_can('activate_plugins') ){

	$wphe_backup_path = ABSPATH.'htaccess.backup';
	$wphe_orig_path = ABSPATH.'.htaccess';

?>
	<div class="wrap">
	<h2 class="wphe-title"><?php _e( 'WP Htaccess Edit' , 'wphe' );?></h2>
	
	<?php
	
	// Save Htaccess file
	
	if( ! empty( sanitize_text_field( $_POST['submit'] ) ) && ! empty( sanitize_text_field( $_POST['save_htaccess'] ) ) && check_admin_referer( 'wphe_save', 'wphe_save' ) ){
		
		$wphe_new_content = $_POST['ht_content'];
		
		wphe_delete_backup();
		
		if( wphe_create_backup() ){

			if( wphe_write_htaccess( $wphe_new_content ) ){

				
				?>
				
				<div class="notice notice-success">

   					<p><strong> <?php _e('Your .htaccess file has been updated!', 'wphe'); ?> </strong> </p>

   					<p><?php _e('The original file was backed up in the main directory. Test your site thoroughly, If something is not working properly restore the original file from backup.', 'wphe'); ?></p>
				
					<form method="post" action="admin.php?page=<?php echo $WPHE_DIR; ?>">

						<?php wp_nonce_field('wphe_delete','wphe_delete'); ?>

						<input type="hidden" name="delete_backup" value="delete" />
						
						<a class="button button-primary" href="admin.php?page=<?php echo $WPHE_DIR; ?>"><span class="dashicons dashicons-yes"></span><?php _e('Success','wphe');?></a>
						
						<a class="button button-secondary" href="admin.php?page=<?php echo $WPHE_DIR; ?>_backup"><span class="dashicons dashicons-no"></span><?php _e('Restore Original','wphe');?></a>
						
						<input type="submit" class="button button-secondary" name="submit" value="<?php _e('Remove Backup', 'wphe');?>" />
					
					</form>
<br>
				</div>
				
				<?php

			}else{

				echo'<div  class="notice notice-error"><p>'.__( 'The file could not be updated!', 'wphe' ).'</p></div>';
				
			}

		}else{

			echo'<div class="notice notice-error"><p>'.__( 'Unable to create backup of the original file! <code>wp-content</code> folder is not writeable! Change the permissions of this folder and try again.', 'wphe').'</strong></p></div>';
			
		}

		unset($wphe_new_content);


// Create a new Htaccess file

	} elseif (! empty( sanitize_text_field( $_POST['submit'] ) ) && ! empty( sanitize_text_field( $_POST['create_htaccess'] ) ) && check_admin_referer( 'wphe_create', 'wphe_create' ) ){
		
		if( wphe_write_htaccess( '# Created by WP Htaccess Edit' ) === false){

			echo'<div  class="notice notice-error"><p>'.__('Unable to create new htaccess file.', 'wphe').'</p></div>';
			
        }else{

			echo'<div  class="notice notice-success"><p>'.__('Htaccess file was successfully created.', 'wphe').'</p></div>';
			
		 }

	// Clear backup 

	} elseif ( ! empty( sanitize_text_field( $_POST['submit'] ) ) && ! empty( sanitize_text_field( $_POST['delete_backup'] ) ) && check_admin_referer( 'wphe_delete', 'wphe_delete' ) ){
        
        if( wphe_delete_backup() === false ){

           echo'<div class="notice notice-error"><p>'.__( 'Unable to remove backup file! <code>wp-content</code> folder is not writeable, Change the permissions of this folder and try again.', 'wphe').'</p></div>';
        
        }else{

           echo'<div  class="notice notice-success"><p>'.__('Backup file has been successfully removed.', 'wphe').'</p></div>';
        
        }


// Edit warning and form

	} else {


		?>
		
		<div class="notice notice-warning">

			<p>
			<?php _e('<strong>Attention</strong>: The changes you make in this area are important for your site!', 'wphe');?>
			</p> 
			
			<p>
			<?php _e('For more information, please visit', 'wphe');?> <a href="http://httpd.apache.org/docs/current/howto/htaccess.html" target="_blank">Apache Tutorial: .htaccess files</a>
			</p>

		</div>

		<?php

		if( ! file_exists( $wphe_orig_path ) ){

			echo'<div class="notice notice-error">';
			echo'<p>'.__('Htaccess file does not exists!', 'wphe').'</p>';
			echo'</div>';

			$success = false;

		}else{ 

			$success = true;

			if( !is_readable( $wphe_orig_path ) ){

				echo'<div class="notice notice-error">';
				echo'<p>'.__( 'Unable to read Htaccess file!', 'wphe').'</p>';
				echo'</div>';
				$success = false;
			}

			if( $success == true ){

				@chmod( $wphe_orig_path, 0644 );

				$wphe_htaccess_content = @file_get_contents( $wphe_orig_path, false, NULL );

				if( $wphe_htaccess_content === false ){

					echo'<div class="notice notice-error">';

					echo'<p>'.__( 'Unable to read Htaccess file!', 'wphe').'</p>';

					echo'</div>';

					$success = false;

				}else{

					$success = true;
				}
			}
		}

		if($success == true){
			?>
			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHE_DIR; ?>">

					<input type="hidden" name="save_htaccess" value="save" />

					<?php wp_nonce_field('wphe_save','wphe_save'); ?>

					<h3 class="wphe-title"><?php _e('Your current Htaccess file', 'wphe');?></h3>

					<textarea name="ht_content" class="dat widefat" wrap="off"><?php echo esc_html( $wphe_htaccess_content );?></textarea>
					
					<p class="submit"><input type="submit" class="button button-primary" name="submit" value="<?php _e('Update', 'wphe');?>" /></p>
				
				</form>
			</div>

			<?php

			unset($wphe_htaccess_content);

		}else{

			?>
			<div class="postbox">

				<form method="post" action="admin.php?page=<?php echo $WPHE_DIR; ?>">

					<input type="hidden" name="create_htaccess" value="create" />

					<?php wp_nonce_field('wphe_create','wphe_create'); ?>

					<p class="submit"><?php _e('Create new <code>.htaccess</code> file?', 'wphe');?> 

						<input type="submit" class="button button-primary" name="submit" value="<?php _e('Create ', 'wphe');?>" />
					
					</p>
				
				</form>
			</div>

			<?php
		}

		unset($success);
	}
	?>
	</div>

	<?php

	unset($wphe_orig_path);
	unset($wphe_backup_path);

}else{

	wp_die( __('You do not have permission to view this page','wphe') );
}

